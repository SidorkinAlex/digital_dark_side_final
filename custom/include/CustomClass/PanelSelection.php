<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class PanelSelection {

    /**
     * Получение данных по одной вакансии (данные по кандидатам)
     * @param string $current_vacancy_id
     * @param array $selection_stages_ids
     * @param int $count количество записей в одной итерации если меньше 1 то отдаем все записи
     * @param int $iteration итерация получения
     * @return array
     */
    public function getOneVacancyData ($current_vacancy_id = '', $selection_stages_ids = array(), int $count=0, int $iteration=0) {
        require_once "custom/include/CustomClass/SimpleCandidate.php";
        require_once "include/utils/array_utils.php";

        global $db, $sugar_config, $timedate;

        $candidates = []; //Данные по кандидатам
        $selection_stages_ids_sql = ''; //Если есть фильтрация по этапам подбора
        $sel_stage_candidates_count = []; //Количество кандидатов на каждом этапе

        //Проверяем надоли фильтровать по этапам подбора
        if (!empty($selection_stages_ids)) {
            if (!is_array($selection_stages_ids)) {
                [$selection_stages_ids];
            }

            $selection_stages_ids = "'" . implode("','", $selection_stages_ids) . "'";
            $selection_stages_ids_sql = "AND `hrpac_selection_stage_candidates_1`.`hrpac_selection_stage_id` IN ({$selection_stages_ids})";
        }

        $LIMIT='';
        // если количество больше 0 то добавляем лимит в запрос
        if($count > 0){
            $offset=$count*$iteration;
            $LIMIT="LIMIT {$offset},{$count}";
        }

        $q = "
            SELECT
                `hrpac_selection_stage_candidates_1`.`id` AS `id`,
                `hrpac_selection_stage_candidates_1`.`hrpac_selection_stage_id` AS 'candidates_this_stage',
                `hrpac_selection_stage_candidates_1`.`hrpac_candidates_id` AS `candidate_id`,
                `hrpac_vacancy_hrpac_candidates_1`.`date_modified`
            FROM
                 `hrpac_vacancy_hrpac_candidates_1`
            INNER JOIN
                `hrpac_selection_stage_candidates_1` ON `hrpac_vacancy_hrpac_candidates_1`.`hrpac_candidates_id` = `hrpac_selection_stage_candidates_1`.`hrpac_candidates_id`
                                                            AND `hrpac_vacancy_hrpac_candidates_1`.`hrpac_vacancy_id` = `hrpac_selection_stage_candidates_1`.`hrpac_vacancy_id`
                                                            AND `hrpac_selection_stage_candidates_1`.`deleted` = 0
            
            WHERE
                `hrpac_vacancy_hrpac_candidates_1`.`deleted`=0
                AND `hrpac_vacancy_hrpac_candidates_1`.`hrpac_vacancy_id` = '{$current_vacancy_id}'
                AND `hrpac_selection_stage_candidates_1`.`this_stage` = 1
                AND `hrpac_selection_stage_candidates_1`.`hrpac_selection_stage_id` <> '{$sugar_config['stageTemplateChange']['cancel_stage']}'
                {$selection_stages_ids_sql}
            ORDER BY
                `hrpac_vacancy_hrpac_candidates_1`.`date_modified` DESC
        {$LIMIT}
        ";

        $result = $db->query($q, true);
        while ($row = $db->fetchByAssoc($result)){
            $simpleCandidate = new SimpleCandidate();
            $simpleCandidate->retrieve($row['candidate_id']);
            $simpleCandidate->setThisStage($row['candidates_this_stage']);
            $sel_stage_candidates_count[$row['candidates_this_stage']] += 1;
            $candidates[] = object_to_array_recursive($simpleCandidate);
        }

        //Получаем этапы подбора у вакансии
        $sel_stages_vacancy = $this->getVacancySelectionStages($current_vacancy_id);

        $return_vacancy_data = [
            'vacancy_selection_stages' => $sel_stages_vacancy,
            'candidates' => $candidates,
            'sel_stage_candidates_count' => $sel_stage_candidates_count,
            'users_date_format' => $timedate->get_user_date_format(),
            'users_UTC_offset' => $timedate->getUserUTCOffset(),
        ];

        return $return_vacancy_data;
    }

    /**
     * Получаем первичные данные по всем вакансиям у рекрутера
     * @return array
     */
    public function getAllVacancy() {
        global $current_user, $db, $sugar_config;

        $statuses_for_panel = $sugar_config['vacansy_status_for_panel'];

        if(empty($statuses_for_panel)){
            $statuses_for_panel = $this->getAllVacancyStatuses();
        }

        $statuses_for_panel = array_keys($statuses_for_panel);

        $where_filter[]='`hrpac_vacancy_statuses_id_c` IN ('."'". implode('\',\'',$statuses_for_panel)."')";
        $where_filter[]=$this->getWhereFromUser();
        $WHERE = implode(' AND ', $where_filter);

        //Получаем все вакансии по рекрутеру
        $q = "
            SELECT
                `hrpac_vacancy`.`id` AS `vacancy_id`,
                `hrpac_vacancy_names`.`name` AS `vacancy_name`,
                `project`.`id` AS `project_id`,
                `project`.`name` AS `project_name`
            FROM
                `hrpac_vacancy`
            LEFT JOIN
                `project` ON `hrpac_vacancy`.`project_id_c` = `project`.`id` AND `project`.`deleted` = 0
            LEFT JOIN 
                `hrpac_vacancy_names` ON `hrpac_vacancy`.`hrpac_vacancy_names_id_c` = `hrpac_vacancy_names`.`id` AND `hrpac_vacancy_names`.`deleted` = 0
            WHERE
                {$WHERE}
                AND `hrpac_vacancy`.`deleted` = 0
        ";

        $res = $db->query($q, true);
        $ret = [];
        $vacancies_ids = [];
        while ($row = $db->fetchByAssoc($res)) {
            $ret[$row['vacancy_id']]['vacancy_name'] = $row['vacancy_name'];

            $project = !empty($row['project_id']) ? [ $row['project_id'] => $row['project_name'] ] : null ;
            $ret[$row['vacancy_id']]['project'] = $project;

            $vacancies_ids[] = $row['vacancy_id'];
        }

        if (empty($ret)) {
            $ret['error'] = 'no_vacancy';
        } else {

            //Получаем навыки по вакансиям
            $skills = $this->getStackSkillsForVacancies($vacancies_ids);

            //Получаем Кол-во кандидатов по этапам подбора
            $sel_stages_cand = $this->getCandidateCountForVacancyBySelStage($vacancies_ids);

            //Собираем данные в один массив.
            foreach ($vacancies_ids as $vac_id) {
                $ret[$vac_id]['skills'] = $skills[$vac_id]; //Записываем склиы по вакансии
                $ret[$vac_id]['sel_stage_candidates_count'] = $sel_stages_cand[$vac_id]['sel_stage_candidates_count']; //Записываем этапы подбора и кол-во кандидатов по ним
                $ret[$vac_id]['count_all_candidates_for_vacancy'] = $sel_stages_cand[$vac_id]['count_all_candidates_for_vacancy']; //Сумму всех кандидатов по вакансии
            }
        }
        return $ret;

    }

    /**
     * Список всех статусов вакансии
     * @return array
     */
    public function getAllVacancyStatuses() {
        return array_keys(HRPAC_VACANCY_STATUSES::get_statuses());
    }

    /**
     * ПОлучение списка навыков по списку вакансий
     * @param array $vacancy_ids
     * @param string $skill_type
     * @return bool
     */
    public function getStackSkillsForVacancies($vacancy_ids = [], $skill_type = 'stack') {
        global $db;
        $ret = [];
        if (!is_array($vacancy_ids)) {
            [$vacancy_ids];
        }

        if(!empty($vacancy_ids)) {
            $vacancy_ids = "'" . implode( "','", $vacancy_ids) . "'";
            $skills_type_sql = !empty($skill_type) ? "AND `hrpac_skills_hrpac_vacancy`.`skill_type` = '{$skill_type}'" : '';

            //Получаем навыки по вакансиям
            $q = "
                SELECT
                    `hrpac_skills`.`id` AS `skill_id`,
                    `hrpac_skills`.`name` AS `skill_name`,
                    `hrpac_skills_hrpac_vacancy`.`hrpac_vacancy_id` AS `vacancy_id`
                FROM
                    `hrpac_skills_hrpac_vacancy`
                LEFT JOIN
                    `hrpac_skills` ON `hrpac_skills_hrpac_vacancy`.`hrpac_skills_id` = `hrpac_skills`.`id` AND `hrpac_skills`.`deleted` = 0
                WHERE
                     `hrpac_skills_hrpac_vacancy`.`hrpac_vacancy_id` IN ({$vacancy_ids}) 
                     AND `hrpac_skills_hrpac_vacancy`.`deleted` = 0  
                     {$skills_type_sql}
            ";
            $res = $db->query($q, true);
            while ($row = $db->fetchByAssoc($res)) {
                $ret[$row['vacancy_id']][$row['skill_id']] = $row['skill_name'];
            }
        }
        return $ret;
    }

    /**
     * Получение списка этапов подбора с кол-вом кандидатов на нём.
     * @param array $vacancy_ids
     * @return array
     */
    public function getCandidateCountForVacancyBySelStage($vacancy_ids = []) {
        global $db;
        $ret = [];
        $all_vacancy_candidates = 0;
        if (!is_array($vacancy_ids)) {
            [$vacancy_ids];
        }
        if(!empty($vacancy_ids)) {
//            $vacancy_ids = "'" . implode( "','", $vacancy_ids) . "'";
            foreach ($vacancy_ids as $vac_id) {
                $q = "
                    SELECT
                        `hrpac_vacancy_id` AS `vacancy_id`,
                        `hrpac_selection_stage_id` AS sel_stage_id,
                        count(`hrpac_candidates_id`) AS cand_count
                    FROM
                        `hrpac_selection_stage_candidates_1`
                    WHERE
                        `deleted` = 0
                        AND `this_stage` = 1
                        AND `hrpac_vacancy_id` = '{$vac_id}'
                    GROUP BY
                        `hrpac_selection_stage_id`
                ";

                $res = $db->query($q, true);
                $all_vacancy_candidates = 0;
                while ($row = $db->fetchByAssoc($res)) {
                    $ret[$row['vacancy_id']]['sel_stage_candidates_count'][$row['sel_stage_id']] = $row['cand_count'];
                    $all_vacancy_candidates += $row['cand_count'];
                    $ret[$row['vacancy_id']]['count_all_candidates_for_vacancy'] = $all_vacancy_candidates;
                }
            }

        }
        return $ret;
    }

    /**
     * Получение данных по этапам подбора у вакансии.
     * @param $vacancy_id
     * @return array
     */
    public function getVacancySelectionStages($vacancy_id) {
        global $db;
        $sql="
            SELECT 
                `hrpac_selection_stage`.`id`,
                `hrpac_selection_stage`.`name`,
                `hrpac_selection_stage`.`stage_icon`,
                `hrpac_selection_stage`.`color`,
                `hrpac_selection_stage`.`required_position`,
                `hrpac_selection_stage`.`required_stage`,
                `stage_templates_hrpac_selection_stage_1`.`sort`
            FROM 
                `stage_templates_hrpac_vacancy`
            INNER JOIN 
                `stage_templates_hrpac_selection_stage_1` `stage_templates_hrpac_selection_stage_1` ON `stage_templates_hrpac_selection_stage_1`.`stage_templates_id` = `stage_templates_hrpac_vacancy`.`stage_templates_id` AND `stage_templates_hrpac_selection_stage_1`.`deleted` = '0'
            RIGHT JOIN 
                `hrpac_selection_stage` ON `hrpac_selection_stage`.`id` = `stage_templates_hrpac_selection_stage_1`.`hrpac_selection_stage_id` AND `hrpac_selection_stage`.`deleted` = '0'
            WHERE
                `stage_templates_hrpac_vacancy`.`hrpac_vacancy_id` = '{$vacancy_id}'
                AND `stage_templates_hrpac_vacancy`.`deleted`='0'
            ORDER BY 
                `stage_templates_hrpac_selection_stage_1`.`sort` ASC
        ";
        $result = $db->query($sql,true);
        $ret=array();
        while (($row=$db->fetchByAssoc($result)) != null) {

            $ret[$row['id']] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'sort' => $row['sort'],
                'stage_icon' => $row['stage_icon'],
                'color' => $row['color'],
                'required_position' => $row['required_position'],
                'required_stage' => $row['required_stage']
            ];
        }
        return $ret;
    }

    public function getWhereFromUser() :string {
        global $sugar_config;
        global $current_user;

        $where=[];
        $acl = new ACLRole();
        foreach ($acl->getUserRoles($current_user->id, false) as $usersRole){
            if(key_exists($usersRole->id,$sugar_config['panelSelectionUserFilters'])){
                $where=$sugar_config['panelSelectionUserFilters'][$usersRole->id];
            }

        }
        if(empty($where)){
            $where[]='`hrpac_vacancy`.`assigned_user_id` = "{{current_user}}"';
        }
        $sql ='';
        $sql="(" .  implode(" OR " , $where ) . ")";
        $sql=str_replace('{{current_user}}', $current_user->id, $sql);
        return $sql;
    }

}