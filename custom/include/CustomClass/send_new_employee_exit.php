<?php

require_once "custom/include/CustomClass/Custom_podbor_vacansy_candidates.php";
class send_new_employee_exit
{

    /**
     * @param $seed_nee
     * @param $candidate_id
     * @param $vacancy_id
     * @param string $type
     *
     * Общий метод для запуска генерации файла и отправки письма
     */
    function start_send_email_and_xlsx($seed_nee, $candidate_id, $vacancy_id, $type = 'offer')
    {
        global $sugar_config, $timedate, $current_user;

        $cpvc = new Custom_podbor_vacansy_candidates();

//        $stage_id = $cpvc->get_id_this_stage($vacancy_id, $candidate_id);
//
//        //Завершаем выполнение если этап любой кроме Подготовка к выходу.
//        if (!in_array($stage_id, $sugar_config['new_employee_exit']['stages_to_send_email'])) {
//            return;
//        }

        $for_email = $this->create_xlsx($seed_nee, $candidate_id, $vacancy_id); //создание excel файла и сбор данных для отправки письма

        $file_name = 'Заявка на выход нового сотрудника - ' . $for_email['fio'] . '.xlsx';   //название файла дл яотображения в письме

        $attachments[] = $this->create_note($seed_nee->id, $file_name); //делаем заметку с вложением, чтоб скормить мейлеру

//        $date_exit = $timedate->to_display_date($seed_nee->date_exit);
        $date_exit = $seed_nee->date_exit;

        //В зависимости от типа выбираем email на который отправлять и тему письма
        if ($type == 'offer') {
            $emailTo = $sugar_config['new_employee_exit']['offer'];
            $emailSubject = "Отправлен оффер сотруднику {$for_email['last_name']} {$for_email['first_name']} {$date_exit}";
        } else {
            $emailTo = $sugar_config['new_employee_exit']['prepare_to_exit'];
            if ($sugar_config['send_email_to_current_user']) {
                $emailTo[] = $current_user->email1;
            }
            $emailSubject = "Заявка на выход нового сотрудника {$for_email['last_name']} {$for_email['first_name']} {$date_exit}";
        }


        //Тело письма одинаковое для оффера и подготовки к выходу
        $emailBody = $altemailBody = "
        Коллеги, добрый день!
        <br><br>
        <b>{$date_exit}</b> в БЮ {$for_email['bu_name']}, {$for_email['department_name']} выходит <b>{$for_email['last_name']} {$for_email['first_name']}</b> ({$for_email['vacancy_name']}).
        <br><br>
        Ответственный рекрутер: {$for_email['recruter_name']}
        <br><br>
        {$seed_nee->description}
        <br><br>
        С уважением, отдел рекрутмента
        <br>
        <br>
        <br>
        Письмо отправлено автоматически из системы HR-CRM
    ";

        //Отправка письма
        $this->nee_send_email($emailTo, $emailSubject, $emailBody, $altemailBody, $attachments);
    }


    /**
     * @param $seed_nee
     * @param $candidate_id
     * @param $vacancy_id
     * @return array
     * @throws PHPExcel_Exception
     *
     * Метод создающий xlsx файл
     */
    function create_xlsx($seed_nee, $candidate_id, $vacancy_id)
    {
        require_once 'custom/include/PHPExcel/PHPExcel.php';

        global $db, $timedate, $current_user, $app_list_strings;

        $c = 0;
        $for_email = [];

        $date_end_contract = (!empty($seed_nee->date_end_contract) && $seed_nee->date_end_contract != 'NULL') ? $seed_nee->date_end_contract : '';

//        $result[++$c] = ['A' => 'Дата выхода', 'B' => $timedate->to_display_date($seed_nee->date_exit)];
        $result[++$c] = ['A' => 'Дата выхода', 'B' => $seed_nee->date_exit];
        $result[++$c] = ['A' => 'Форма оформления', 'B' => $app_list_strings['layout_form_list'][$seed_nee->layout_form]];
        $result[++$c] = ['A' => 'Договор до', 'B' => $date_end_contract];
        $result[++$c] = 'space';


        $q = "
            SELECT
                `hrpac_vacancy_names`.`name` AS `vacancy_name`,
                `hrpac_vacancy`.`assigned_user_id` AS `assigned_user_id`,
                `hrpac_business_units`.`name` AS `bu_name`,
                `hrpac_departments`.`name` AS `department_name`,
                `users`.`first_name` AS `user_first_name`,
                `users`.`last_name` AS `user_last_name`
            FROM
                `hrpac_vacancy`
            LEFT JOIN
                `hrpac_business_units` ON `hrpac_vacancy`.`hrpac_business_units_id_c` = `hrpac_business_units`.`id`
                    AND `hrpac_business_units`.`deleted` = 0
            LEFT JOIN
                `hrpac_departments` ON `hrpac_vacancy`.`hrpac_departments_id_c` = `hrpac_departments`.`id`
                    AND `hrpac_departments`.`deleted` = 0
            LEFT JOIN
                `users` ON `hrpac_vacancy`.`user_id1_c` = `users`.`id`
                    AND `users`.`deleted` = 0
            LEFT JOIN
                `hrpac_vacancy_names` ON `hrpac_vacancy`.`hrpac_vacancy_names_id_c` = `hrpac_vacancy_names`.`id`
                    AND `hrpac_vacancy_names`.`deleted` = 0
            WHERE
                `hrpac_vacancy`.`deleted` = 0
                AND `hrpac_vacancy`.`id` = '{$vacancy_id}'
        ";

        $res = $db->query($q, true);

        if ($row = $db->fetchByAssoc($res)) {
            $result[++$c] = ['A' => 'БЮ', 'B' => $row['bu_name']];
            $result[++$c] = ['A' => 'Департамент/Отдел', 'B' => $row['department_name']];
            $result[++$c] = ['A' => 'Должность', 'B' => $row['vacancy_name']];
            $result[++$c] = ['A' => 'Грейд', 'B' => $app_list_strings['hr_grade_list'][$seed_nee->grade]];
            $result[++$c] = ['A' => 'Руководитель', 'B' => $row['user_last_name'] . ' ' . $row['user_first_name']];
            $result[++$c] = ['A' => 'Группа проектов', 'B' => $app_list_strings['group_of_projects_list'][$seed_nee->group_of_projects]];

            $for_email['bu_name'] = $row['bu_name'];
            $for_email['department_name'] = $row['department_name'];
            $for_email['vacancy_name'] = $row['vacancy_name'];

            if (!empty($row['assigned_user_id'])){
                $recruter = new User();
                $recruter->retrieve($row['assigned_user_id']);
                $for_email['recruter_name'] = $recruter->full_name;
            }
        }

        $result[++$c] = ['A' => 'Группа рассылки', 'B' => ''];
        $result[++$c] = 'space';


        $phone_type = Means_of_Communication::get_tupe_id('phone');

        $q = "
            SELECT
                `hrpac_candidates`.`name`,
                `hrpac_candidates`.`last_name`,
                `hrpac_candidates`.`first_name`,
                `hrpac_candidates`.`middle_name`,
                `means_of_communication`.`value` AS `user_phone`,
                `hrpac_candidates`.`birth_date`
            FROM
                `hrpac_candidates`
            LEFT JOIN 
                `means_of_communication` ON `hrpac_candidates`.`id` = `means_of_communication`.`parent_id`
                    AND `means_of_communication`.`deleted` = 0
                    AND `means_of_communication`.`value_type`='{$phone_type}'
            WHERE
                `hrpac_candidates`.`deleted` = 0
                AND `hrpac_candidates`.`id` = '{$candidate_id}'
            ORDER BY 
                `means_of_communication`.`sort`
            LIMIT 1
        ";
        $res = $db->query($q, true);

        if ($row = $db->fetchByAssoc($res)) {
            $result[++$c] = ['A' => 'Фамилия', 'B' => $row['last_name']];
            $result[++$c] = ['A' => 'Имя', 'B' => $row['first_name']];
            $result[++$c] = ['A' => 'Отчество', 'B' => $row['middle_name']];
            $result[++$c] = 'space';
            $result[++$c] = ['A' => 'Мобильный телефон', 'B' => $row['user_phone']];
            $result[++$c] = ['A' => 'Дата рождения', 'B' => $timedate->to_display_date($row['birth_date'])];

            $for_email['fio'] = $row['name'];
            $for_email['last_name'] = $row['last_name'];
            $for_email['first_name'] = $row['first_name'];

        }

        $result[++$c] = 'space';
        $result[++$c] = ['A' => 'Номер места', 'B' => $seed_nee->place_number];
        $result[++$c] = ['A' => 'Симкарта', 'B' => ''];
        $result[++$c] = 'space';
        $result[++$c] = ['A' => 'Дополнительно', 'B' => $seed_nee->description];


        $xls = new PHPExcel();

        //$xls->getProperties()->setTitle("Название");
        //$xls->getProperties()->setSubject("Тема");
        //$xls->getProperties()->setCreator("Автор");
        //$xls->getProperties()->setManager("Руководитель");
        $xls->getProperties()->setCompany("TSConsulting");
        //$xls->getProperties()->setCategory("Группа");
        //$xls->getProperties()->setKeywords("Ключевые слова");
        //$xls->getProperties()->setDescription("Примечания");
        //$xls->getProperties()->setLastModifiedBy("Автор изменений");
        //$xls->getProperties()->setCreated("25.03.2019");

        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        //$sheet->setTitle('Название листа');


        //Рамка у ячеек
        $border = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '000000')
                )
            )
        );
        $sheet->getStyle("A1:B" . $c)->applyFromArray($border);

        //Фон
        $bg_green = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '01B050')
            )
        );

        //Ширина ячеек
        $sheet->getColumnDimensionByColumn("A")->setAutoSize(true);
        //$sheet->getColumnDimensionByColumn("B")->setAutoSize(true);
        $sheet->getColumnDimension("B")->setWidth(100);


        foreach ($result as $row => $value) {

            if (is_array($value)) {
                foreach ($value as $col => $val) {
                    if ($col == 'A') {
                        $sheet->getStyle($col . $row)->applyFromArray($bg_green);
                    }
                    $sheet->getRowDimension($row)->setRowHeight(15);
                    $sheet->setCellValueExplicit($col . $row, $val, PHPExcel_Cell_DataType::TYPE_STRING);
                }
            } else {
                // пропуск строки
            }
        }


        $objWriter = new PHPExcel_Writer_Excel2007($xls);
        $objWriter->save('upload/' . $seed_nee->id);

        return $for_email;

    }

    /**
     * @param $nee_id
     * @param $file_name
     * @return Note
     *
     * Создание заметки с вложением для привязки файла к письму
     */
    function create_note($nee_id, $file_name)
    {
//    $file_path = 'upload/'. $nee_id .'.xlsx';

        $note = new Note();
        $note->id = create_guid();
//    $note->date_entered = $attachment->date_entered;
//    $note->date_modified = $attachment->date_modified;
//    $note->modified_user_id = $attachment->modified_user_id;
//    $note->assigned_user_id = $attachment->assigned_user_id;
        $note->new_with_id = true;
        $note->parent_id = $nee_id;
        $note->parent_type = 'HRPAC_NEW_EPLOYEE_EXIT';
        $note->name = 'Nee email';;
        $note->filename = $file_name;
        $note->file_mime_type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $fileLocation = "upload://{$nee_id}";
        $dest = "upload://{$note->id}";
        if (!copy($fileLocation, $dest)) {
            $GLOBALS['log']->debug("EMAIL 2.0: could not copy attachment file to $fileLocation => $dest");
        }
        $note->save();

        return $note;
    }

    /**
     * @param $emailTo
     * @param $emailSubject
     * @param $emailBody
     * @param $altemailBody
     * @param array $attachments
     * @return bool
     *
     * Отправка письма
     */
    function nee_send_email($emailTo, $emailSubject, $emailBody, $altemailBody, $attachments = array())
    {
        require_once('modules/Emails/Email.php');
        require_once('include/SugarPHPMailer.php');

        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        $mail->From = $defaults['email'];
        isValidEmailAddress($mail->From);
        $mail->FromName = $defaults['name'];
        $mail->ClearAllRecipients();
        $mail->ClearReplyTos();
        $mail->Subject = from_html($emailSubject);
        $mail->Body = $emailBody;
        $mail->AltBody = $altemailBody;
        $mail->handleAttachments($attachments);
        $mail->prepForOutbound();

        if (empty($emailTo)) {
            return false;
        }
        foreach ($emailTo as $to) {
            $mail->AddAddress($to);
        }

        $mail->send();

    }

}