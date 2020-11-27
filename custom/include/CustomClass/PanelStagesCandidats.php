<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 09.09.20
 * Time: 18:31
 */
class PanelStagesCandidats {

    /**
     * получение максимальное количество этапов по кандидатам привязанным к вакансии
     * и данных по этапам в том числе колличеству кандидатов на нем
     * @param guid $vacansy_id
     * @return array =
     * [
     *      [
     *          vacancy_id => HRPAC_VACANCY id,
     *          selection_stage_id => HRPAC_SELECTION_STAGE id,
     *          selection_stage_name => HRPAC_SELECTION_STAGE name,
     *          stage_icon => HRPAC_SELECTION_STAGE stage_icon,
     *          selection_stage_color => HRPAC_SELECTION_STAGE color,
     *          selection_stage_required_position => HRPAC_SELECTION_STAGE required_position,
     *          selection_stage_required_stage => HRPAC_SELECTION_STAGE required_stage,
     *          hssc_sort => HRPAC_SELECTION_STAGE_CANDIDATES_1 sort,
     *          candidates_count => Количество кандидатов на этапе,
     *          in_stage_templates => Этап находится в шаблоне этапа подбора скязанным с данной вакансией,
     *      ],
     *      [
     *          ...
     *      ],
     * ]
     *
     *
     */
    public function getStagesFromVacansy($vacansy_id){
        global $db;
        $sql="
                SELECT
                        `hssc`.`hrpac_vacancy_id` AS `vacancy_id`,
                        `hrpac_selection_stage`.`id` AS `id`,
                        `hrpac_selection_stage`.`name` AS `name`,
                        `hrpac_selection_stage`.`stage_icon` AS `stage_icon`,
                        `hrpac_selection_stage`.`color` AS `color`,
                        `hrpac_selection_stage`.`required_position` AS `required_position`,
                        `hrpac_selection_stage`.`required_stage` AS `required_stage`,
                        `hssc`.`sort` AS `sort`,
                (SELECT COUNT(id) 
                 FROM `hrpac_selection_stage_candidates_1`
                 WHERE 
                 `deleted` ='0'
                 AND
                 `hrpac_vacancy_id` = '{$vacansy_id}'
                 AND
                 `hrpac_selection_stage_id` = `hrpac_selection_stage`.`id`
                 AND
                 `this_stage` = '1'
                ) AS `count`,
                (
                 SELECT `hrpac_selection_stage`.`id` IN (
                                                            SELECT `hrpac_selection_stage_id`
                                                            FROM `stage_templates_hrpac_selection_stage_1`
                                                            WHERE
                                                            `stage_templates_id`=`sthv`.`stage_templates_id`
                                                            AND 
                                                            `deleted` = '0'
                                                        )
                ) AS `in_stage_templates`
                    FROM
                        `hrpac_selection_stage_candidates_1` AS `hssc`
                    RIGHT JOIN 
                `hrpac_selection_stage` ON `hrpac_selection_stage`.`id` = `hssc`. `hrpac_selection_stage_id` AND `hrpac_selection_stage`.`deleted` = '0'
                    RIGHT JOIN `stage_templates_hrpac_vacancy` `sthv` ON `sthv`.`hrpac_vacancy_id`='{$vacansy_id}' AND `sthv`.`deleted` = '0'
                    WHERE
                        hssc.`deleted` = 0
                        AND hssc.`hrpac_vacancy_id` = '{$vacansy_id}'
                    GROUP BY
                        hssc.`hrpac_selection_stage_id`
                    ORDER BY 
                `hssc`.`sort` ASC
        ";
        $result = $db->query($sql,1);
        $arr=[];
        while ($row = $db->fetchByAssoc($result)) {
            $arr[]=$row;
        }
        return $arr;
    }

    /**
     * Список кандидатов на этапе
     * @param guid $vacansy_id
     * @param guid $stage_id
     * @return array [id => guid, name => ФИО Кандидата ]
     */
    public function getCcandidatesStage($vacansy_id,$stage_id){
        global $db;
        $sql="
               SELECT `hc`.`id`, `hc`.`name`
               FROM `hrpac_selection_stage_candidates_1` `hshc1`
               INNER JOIN `hrpac_candidates` `hc` ON `hc`.`id` = `hshc1`.`hrpac_candidates_id` AND `hc`.`deleted` = '0'
               WHERE
               `hshc1`.`hrpac_selection_stage_id` = '{$stage_id}'
               AND 
               `hshc1`.`hrpac_vacancy_id` = '{$vacansy_id}'
               AND 
               `hshc1`.`this_stage` = '1'
               AND 
               `hshc1`.`deleted` = '0'
               ORDER BY `date_start_stage` DESC
        ";
        $result = $db->query($sql,1);
        $arr=[];
        while ($row = $db->fetchByAssoc($result)) {
            $arr[]=$row;
        }
        return $arr;
    }
}