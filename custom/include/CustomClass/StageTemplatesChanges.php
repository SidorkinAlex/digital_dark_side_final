<?php

/**
 * Class StageTemplatesChanges
 *
 * Класс выполняет функцию проверки и изменения списков этапов подюора для кандидатов
 * у которых изменился список этапов подбора (через вакансию)
 */

class StageTemplatesChanges {

    public $vacansy_id;

    /**
     * @param $stage_template_id
     * @return bool
     *
     * Основной метод в котороый передатёся ID шаблона этапов подбора в котором произошло изменение списка этапов подбора.
     */
    public function doChanges($stage_template_id) {
        global $db, $sugar_config;


        //ID шаблона этапов подбра
//        $stage_template_id = '759e20d8-d46b-7891-5465-5e61f8fb2cdc';

        //Получаем список с новыйми этапами шаблона подбора
        $q = "
            SELECT
                `stage_templates_hrpac_selection_stage_1`.`hrpac_selection_stage_id`,
                `stage_templates_hrpac_selection_stage_1`.`sort`
            FROM
                `stage_templates_hrpac_selection_stage_1`
            WHERE
                `stage_templates_hrpac_selection_stage_1`.`deleted` = 0
                AND `stage_templates_hrpac_selection_stage_1`.`stage_templates_id` = '{$stage_template_id}'
            ORDER BY
                `stage_templates_hrpac_selection_stage_1`.`sort`
        ";

        $res = $db->query($q, true);

        while ($row = $db->fetchByAssoc($res)) {
            $new_selection_stages[$row['sort']] = $row['hrpac_selection_stage_id'];
        }

        $stop_statuses = "'" . $sugar_config['db_values']['vacancy_statuses']['closed'] . "','" . $sugar_config['db_values']['vacancy_statuses']['cancelled'] . "'";

        //выбираем вакаснии к которым привязан шаблон этапов
        $query = "
            SELECT
                `stage_templates_hrpac_vacancy`.`hrpac_vacancy_id`
            FROM
                `stage_templates_hrpac_vacancy`
            LEFT JOIN 
                `hrpac_vacancy` ON `stage_templates_hrpac_vacancy`.`hrpac_vacancy_id` = `hrpac_vacancy`.`id`
            WHERE
                `stage_templates_hrpac_vacancy`.`deleted` = 0
                AND `stage_templates_hrpac_vacancy`.`stage_templates_id` = '{$stage_template_id}' 
                AND `hrpac_vacancy`.`hrpac_vacancy_statuses_id_c` NOT IN ({$stop_statuses})
        ";

        $result = $db->query($query, true);

        $vacancies = '';
        while ($row = $db->fetchByAssoc($result)) {
            $vacancies .= "'{$row['hrpac_vacancy_id']}',";
        }

        $vacancies = substr($vacancies, 0, -1);

        //По вакансиям, к которым привязан шаблон, собираем кандидатов у которых надо сменить карту шаблона
        if (!empty($vacancies)) {
            $query = "
                SELECT
                    `hrpac_selection_stage_candidates_1`.`hrpac_selection_stage_id`,
                    `hrpac_selection_stage_candidates_1`.`hrpac_candidates_id`,
                    `hrpac_selection_stage_candidates_1`.`hrpac_vacancy_id`,
                    `hrpac_selection_stage_candidates_1`.`sort`,
                    `hrpac_selection_stage_candidates_1`.`done`,
                    `hrpac_selection_stage_candidates_1`.`date_start_stage`,
                    `hrpac_selection_stage_candidates_1`.`this_stage`
                FROM
                    `hrpac_selection_stage_candidates_1`
                WHERE
                    `hrpac_selection_stage_candidates_1`.`deleted` = 0
                    AND `hrpac_selection_stage_candidates_1`.`hrpac_vacancy_id` IN ({$vacancies})
                ORDER BY 
                    `hrpac_selection_stage_candidates_1`.`sort`
            ";


            $result = $db->query($query, true);

            //получаем масив с ID кандидата, ID этапа подбора, сортировкой и закрыт ли этап.
            while ($row = $db->fetchRow($result)) {
                $stages_sort[$row['hrpac_vacancy_id']][$row['hrpac_candidates_id']][$row['sort']] = $row['hrpac_selection_stage_id'];
                $stages_done[$row['hrpac_vacancy_id']][$row['hrpac_candidates_id']][$row['hrpac_selection_stage_id']] = array ('done' => $row['done'], 'date_start_stage' => $row['date_start_stage'], 'this_stage' => $row['this_stage']);
            }




            foreach ($stages_sort as $vacancy_id => $vacancy_data) {
                foreach ($vacancy_data as $candidate_id => $candidates_stage_data) {

                    $cancel_stage_id = $sugar_config['stageTemplateChange']['cancel_stage'];

                    //Если у пользователя текущий этап - Отказ, то отменяем удаление этапов
                    if ($stages_done[$vacancy_id][$candidate_id][$cancel_stage_id]['this_stage'] != 1) {

                        $diff_del_stages = array_diff($candidates_stage_data, $new_selection_stages);
                        $temp_old_stages = $this->removeStage($diff_del_stages, $candidates_stage_data, $stages_done[$vacancy_id][$candidate_id]); //Удаление этапов предназанченных для удаления

                        $temp_old_stages = $this->changeStagesPlace($new_selection_stages, $temp_old_stages, $stages_done[$vacancy_id][$candidate_id]); //Добавление новых этапов и изменение порядка этапов

                        $return_data[$vacancy_id][$candidate_id] = $temp_old_stages;

                    }
                }
            }

            return $return_data; //возвращаем список с изменёнными шаблонами этапов по каждому кандидату

        } else {
            return false;
        }
    }

    /**
     * @param $remove_stages
     * @param $old_stages
     * @param $old_stages_done
     * @return mixed
     *
     * Удаление НЕ пройденных этапов подбора, которые в списке на удаление
     */
    private function removeStage($remove_stages, $old_stages, $old_stages_done) {
        foreach ($remove_stages as $sort => $stage_id) {
            //Если этап из старго спика не пройден, то удалем его
            if (empty($old_stages_done[$stage_id]['done']) && empty($old_stages_done[$stage_id]['this_stage'])) {
                unset($old_stages[$sort]);
                unset($old_stages_done[$stage_id]);
            }
        }
        return $old_stages;
    }

    /**
     * @param $new_stages_list
     * @param $old_stages_list
     * @param $old_stages_done
     * @return array
     *
     * Добавление новых этапов и изменение последовательности существующих в зависимости от того что в новом списке
     */
    private function changeStagesPlace ($new_stages_list, $old_stages_list, $old_stages_done) {
//        $last_done_stage = array();
        $result = array();
        $result_sort = 0;
        $this_stage_sort_in_new_list = 0;

        foreach ($old_stages_list as $old_sort => $old_stage_id) {
            if (!empty($old_stages_done[$old_stage_id]['this_stage'])) {
                $this_stage_sort_in_new_list = array_search($old_stage_id, $new_stages_list);
            }


            if (!empty($old_stages_done[$old_stage_id]['done']) || !empty($old_stages_done[$old_stage_id]['this_stage'])) {                      //если в старом списке этап пройден

                $temp_key = array_search($old_stage_id, $new_stages_list);      //ищем этот этап вновом списке
                if ($temp_key) unset($new_stages_list[$temp_key]);              //если находим то убираем этот этап из нового списка, т.к. использовать мы его не можем потому, что он пройден в старом списке

                $result_sort = $old_sort;                                       //Записываем сортировку результат для дальнейших действий
//                $last_done_stage['sort'] = $result_sort;                        //Записываем последний пройденный этап
//                $last_done_stage['stage_id'] = $old_stage_id;

                $this_stage = $old_stages_done[$old_stage_id]['this_stage'] ? 1 : 0;
                $done = $old_stages_done[$old_stage_id]['done'] ? 1 : 0;
                //Записываем старый пройденный этап в результат
                $result[$old_sort] = array(
                    'stage_id' => $old_stage_id,
                    'done' => $done,
                    'date_start_stage' => $old_stages_done[$old_stage_id]['date_start_stage'],
                    'this_stage' => $this_stage,
                );
            }
        }

        if ($this_stage_sort_in_new_list != 0) {
            foreach ($new_stages_list as $new_sort => $new_stage_id) {
                if ($new_sort > $this_stage_sort_in_new_list) {
                    $result_sort++;                                             //Увеличиваем счетчик сортировки для резуьтата
                    $result[$result_sort] = array(
                        'stage_id' => $new_stage_id,
                        'done' => 0,
                    );
                }
            }
        } else {
            #### Добрались до не пройденных этапов
            foreach ($new_stages_list as $new_sort => $new_stage_id) {
                if ($new_sort < $result_sort) {
                    continue;
                }
                $result_sort++;                                             //Увеличиваем счетчик сортировки для резуьтата
                $result[$result_sort] = array(
                    'stage_id' => $new_stage_id,
                    'done' => 0,
                );

            }
        }
        return $result;
    }

    /**
     * @param $s
     *
     * Для дебага.
     */

    private function echoDebug($s) {
        echo "<br><h2>$s</h2><br>";
    }

    /**
     * @param $t
     * Для дебага
     */
    private function cstmVarDump($t) {

        global $db;

        $r = [];

        foreach ($t as $k => $v) {
            $q = "
            SELECT 
              hrpac_selection_stage.`name` AS `name`
            FROM hrpac_selection_stage
            WHERE
                hrpac_selection_stage.id = '{$v}'
                AND hrpac_selection_stage.deleted = 0
        ";

            $res = $db->query($q, 1);

            if ($row = $db->fetchByAssoc($res)) {
                $d = !empty($row['done']) ? $row['done'] : ' ';
                $r[] = $k . ' ' . $d . ' ' . $row['name'];
            }
        }

        file_put_contents('cache/StageTemplatesChanges.log', "\n======\n" . var_export($r, 1) . "\n====", FILE_APPEND);
    }
}