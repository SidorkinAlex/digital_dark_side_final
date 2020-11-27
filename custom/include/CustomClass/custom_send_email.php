<?php

require_once ('modules/AOW_Actions/actions/actionSendEmail.php');


/**
 * Class temp_actionSendEmail
 *
 * Переписанный класс отправки email из процессов
 * Предназначен для отправки письма нанимающему менеджеру по кандидату через вакансию.
 */
class temp_actionSendEmail extends actionSendEmail {

    /**
     * @param $vacancy_id
     * @param $candidate_id
     * @param $email_template
     *
     * Старт отправки письма
     *
     */
    public function start_action($vacancy_id, $candidate_id, $email_type)
    {
        global $sugar_config;

        //Собираем парметры для отправки письма
        if ($email_type == 'vacancy') {
            $email_template = $sugar_config['temp_actionSendEmail']['other_vacancy_email_template'];
            $email_send_field = 'assigned_user_name';
        } else if ($email_type == 'customer_CV') {
            $email_template = $sugar_config['temp_actionSendEmail']['customer_CV_email_template'];
            $email_send_field = 'manager_id';
        }


        $params = array(
            'individual_email' => '0',
            'email_template' => $email_template,
            'email_to_type' =>
                array(
                    0 => 'to',
                ),
            'email_target_type' =>
                array(
                    0 => 'Related Field',
                ),
            'email' =>
                array(
                    0 => $email_send_field,
                ),
//            'candidate_id' => $candidate_id,
        );


        $seed_vacansy = new HRPAC_VACANCY();
        $seed_vacansy->retrieve($vacancy_id);

        $seed_candidate = new HRPAC_CANDIDATES();
        $seed_candidate->retrieve($candidate_id);

        $this->run_action($seed_vacansy, $seed_candidate, $params);
    }

    /**
     * @param SugarBean $bean
     * @param array $params
     * @param bool $in_save
     * @return bool
     *
     * Переписанный метод из процессов для отправки писма
     */
    public function run_action(SugarBean $bean, SugarBean $seed_candidate, $params = array())
    {
        include_once __DIR__ . '/../../EmailTemplates/EmailTemplate.php';

        global $db;

        $this->clearLastEmailsStatus();

        $emailTemp = new EmailTemplate();
        $emailTemp->retrieve($params['email_template']);

        if ($emailTemp->id == '') {
            return false;
        }

        $emails = $this->getEmailsFromParams($bean, $params);

        if (!isset($emails['to']) || empty($emails['to'])) {
            return false;
        }

        $attachments = $this->getAttachments($emailTemp);

        $ret = true;

        $this->parse_template($seed_candidate, $emailTemp);
//        $this->parse_template($bean, $emailTemp);
        if ($emailTemp->text_only == '1') {
            $email_body_html = $emailTemp->body;
        } else {
            $email_body_html = $emailTemp->body_html;
        }

        $q = "
            SELECT
                `hrpac_skills`.`name`
            FROM
                `hrpac_skills`
            LEFT JOIN 
                `hrpac_skills_hrpac_vacancy` ON `hrpac_skills`.`id` = `hrpac_skills_hrpac_vacancy`.`hrpac_skills_id`
            WHERE
                `hrpac_skills`.`deleted` = 0
                AND `hrpac_skills_hrpac_vacancy`.`deleted` = 0
                AND `hrpac_skills_hrpac_vacancy`.`skill_type` = 'stack'
                AND `hrpac_skills_hrpac_vacancy`.`hrpac_vacancy_id` = '{$bean->id}'
        ";

        $res = $db->query($q);

        while ($row = $db->fetchByAssoc($res)) {
            $result[] = $row['name'];
        }
        $stack_skills_str = implode(', ', $result);

        $email_body_html = str_replace('$custom_hrpac_vacancy_stack_skills_ids', $stack_skills_str, $email_body_html);

        if (!$this->sendEmail($emails['to'], $emailTemp->subject, $email_body_html, $emailTemp->body, $bean, $emails['cc'], $emails['bcc'], $attachments)) {
            $ret = false;
            $this->lastEmailsFailed++;
        } else {
            $this->lastEmailsSuccess++;
        }

        return $ret;
    }



    private function getEmailsFromParams(SugarBean $bean, $params)
    {
        $emails = array();
        //backward compatible
        if (isset($params['email_target_type']) && !is_array($params['email_target_type'])) {
            $email = '';
            switch ($params['email_target_type']) {
                case 'Email Address':
                    $params['email'] = array($params['email']);
                    break;
                case 'Specify User':
                    $params['email'] = array($params['email_user_id']);
                    break;
                case 'Related Field':
                    $params['email'] = array($params['email_target']);
                    break;
            }
            $params['email_target_type'] = array($params['email_target_type']);
            $params['email_to_type'] = array('to');
        }
        //end backward compatible
        if (isset($params['email_target_type'])) {
            foreach ($params['email_target_type'] as $key => $field) {
                switch ($field) {
                    case 'Email Address':
                        if (trim($params['email'][$key]) != '') {
                            $emails[$params['email_to_type'][$key]][] = $params['email'][$key];
                        }
                        break;
                    case 'Specify User':
                        $user = new User();
                        $user->retrieve($params['email'][$key]);
                        $user_email = $user->emailAddress->getPrimaryAddress($user);
                        if (trim($user_email) != '') {
                            $emails[$params['email_to_type'][$key]][] = $user_email;
                            $emails['template_override'][$user_email] = array('Users' => $user->id);
                        }

                        break;
                    case 'Users':
                        $users = array();
                        switch ($params['email'][$key][0]) {
                            case 'security_group':
                                if (file_exists('modules/SecurityGroups/SecurityGroup.php')) {
                                    require_once('modules/SecurityGroups/SecurityGroup.php');
                                    $security_group = new SecurityGroup();
                                    $security_group->retrieve($params['email'][$key][1]);
                                    $users = $security_group->get_linked_beans('users', 'User');
                                    $r_users = array();
                                    if ($params['email'][$key][2] != '') {
                                        require_once('modules/ACLRoles/ACLRole.php');
                                        $role = new ACLRole();
                                        $role->retrieve($params['email'][$key][2]);
                                        $role_users = $role->get_linked_beans('users', 'User');
                                        foreach ($role_users as $role_user) {
                                            $r_users[$role_user->id] = $role_user->name;
                                        }
                                    }
                                    foreach ($users as $user_id => $user) {
                                        if ($params['email'][$key][2] != '' && !isset($r_users[$user->id])) {
                                            unset($users[$user_id]);
                                        }
                                    }
                                    break;
                                }
                            //No Security Group module found - fall through.
                            // no break
                            case 'role':
                                require_once('modules/ACLRoles/ACLRole.php');
                                $role = new ACLRole();
                                $role->retrieve($params['email'][$key][2]);
                                $users = $role->get_linked_beans('users', 'User');
                                break;
                            case 'all':
                            default:
                                $db = DBManagerFactory::getInstance();
                                $sql = "SELECT id from users WHERE status='Active' AND portal_only=0 ";
                                $result = $db->query($sql);
                                while ($row = $db->fetchByAssoc($result)) {
                                    $user = new User();
                                    $user->retrieve($row['id']);
                                    $users[$user->id] = $user;
                                }
                                break;
                        }
                        foreach ($users as $user) {
                            $user_email = $user->emailAddress->getPrimaryAddress($user);
                            if (trim($user_email) != '') {
                                $emails[$params['email_to_type'][$key]][] = $user_email;
                                $emails['template_override'][$user_email] = array('Users' => $user->id);
                            }
                        }
                        break;
                    case 'Related Field':
                        $emailTarget = $params['email'][$key];
                        $relatedFields = array_merge($bean->get_related_fields(), $bean->get_linked_fields());
                        $field = $relatedFields[$emailTarget];
                        if ($field['type'] == 'relate') {
                            $linkedBeans = array();
                            $idName = $field['id_name'];
                            $id = $bean->$idName;
                            $linkedBeans[] = BeanFactory::getBean($field['module'], $id);
                        } else {
                            if ($field['type'] == 'link') {
                                $relField = $field['name'];
                                if (isset($field['module']) && $field['module'] != '') {
                                    $rel_module = $field['module'];
                                } else {
                                    if ($bean->load_relationship($relField)) {
                                        $rel_module = $bean->$relField->getRelatedModuleName();
                                    }
                                }
                                $linkedBeans = $bean->get_linked_beans($relField, $rel_module);
                            } else {
                                $linkedBeans = $bean->get_linked_beans($field['link'], $field['module']);
                            }
                        }
                        if ($linkedBeans) {
                            foreach ($linkedBeans as $linkedBean) {
                                if (!empty($linkedBean)) {
                                    $rel_email = $linkedBean->emailAddress->getPrimaryAddress($linkedBean);
                                    if (trim($rel_email) != '') {
                                        $emails[$params['email_to_type'][$key]][] = $rel_email;
                                        $emails['template_override'][$rel_email] = array($linkedBean->module_dir => $linkedBean->id);
                                    }
                                }
                            }
                        }
                        break;
                    case 'Record Email':
                        $recordEmail = $bean->emailAddress->getPrimaryAddress($bean);
                        if ($recordEmail == '' && isset($bean->email1)) {
                            $recordEmail = $bean->email1;
                        }
                        if (trim($recordEmail) != '') {
                            $emails[$params['email_to_type'][$key]][] = $recordEmail;
                        }
                        break;
                }
            }
        }
        return $emails;
    }

}

