<?php   ##                                                   ##
/**
 * Created by PhpStorm.
 * User: SeedTeam
 * Date: 20.11.2019
 * Time: 10:02
 */

require_once 'custom/include/CustomClass/Custom_Audit.php';
require_once 'custom/include/CustomClass/Custom_data_modull.php';
require_once 'modules/HRPAC_VACANCY/HRPAC_VACANCY.php';

class Custom_podbor_vacansy_candidates {

    public $vacancy_id;
    public $candidates_id;
    public $vacancy_data;
    public $candidates_data;
    public $stage_data;
    public $this_stage;
    public $map_var=['vacancy_id' , 'candidates_id' , 'vacancy_data' , 'candidates_data', 'stage_data','this_stage'];
    public $temporarily;
    public $vacancy_array_panel;

    public function __construct(){

    }

/*
 * загружаем данные в объект в класс
 * @param string $vacancy_id id вакансии
 * @param string $candidates_id  id кандидата
 */
    public function get_data($vacancy_id, $candidates_id) {
        global $db;

        $this->vacancy_id=$vacancy_id;
        $this->candidates_id=$candidates_id;
        // получаем данные кандидата
        $seedCandidates= BeanFactory::getBean('HRPAC_CANDIDATES',$candidates_id);

        foreach($seedCandidates->field_name_map as $k =>$v) {

            $this->candidates_data[$v['name']]= $seedCandidates->{$v['name']};
        }
        // получаем данные вакансии
        $seedVacancy= BeanFactory::getBean('HRPAC_VACANCY',$vacancy_id);

        foreach($seedVacancy->field_name_map as $k =>$v) {

            $this->vacancy_data[$v['name']]= $seedVacancy->{$v['name']};
            if($v['name']=='currency_id'){
                $currency_list=get_currency_list();
                $this->vacancy_data['currency_id'] = $currency_list[$this->vacancy_data['currency_id']]['symbol'];
            }
        }
        $sql="
        SELECT `hss`.*, `ssc`.`sort`,`ssc`.`done`, `ssc`.`this_stage`, `ssc`.`date_start_stage`
        FROM `hrpac_selection_stage_candidates_1` `ssc`
        INNER JOIN `hrpac_selection_stage` `hss` ON `ssc`.`hrpac_selection_stage_id`= `hss`.`id` AND `hss`.`deleted`='0'
        WHERE
        `ssc`.`deleted`='0'
        AND `ssc`.`hrpac_vacancy_id`='{$vacancy_id}'
        AND `ssc`.`hrpac_candidates_id`='{$candidates_id}'
        ";
        $result=$db->query($sql,1);
        $rows=array();
        while (($row=$db->fetchByAssoc($result)) != null) {
            $rows[$row['sort']] = $row;
        }
        ksort($rows);
        $this->stage_data=$rows;
        $this->this_stage=$this->get_id_this_stage($vacancy_id, $candidates_id);
    }
    /*
     * Возвращает данные связи и данные вакансии и Кандидата
     * @return array возвращает массив данных из связи
     */
    public function pull_data() {
        $return=array();
        foreach ($this->map_var as $k => $v) {
            $return{$v}=$this->{$v};
        }
        return $return;
    }

    /*
     * Получаем данные по всем кандидатом вакансии
     * @param string $vacancy_id id вакансии
     * @return array возвращает массив данных по всем кандидатам для данной вакансии
     */
    public function pull_data_for_vacancy($vacancy_id) {
        global $db;
        $sql="
        SELECT `hrpac_candidates_id`
        FROM `hrpac_vacancy_hrpac_candidates_1`
        WHERE
        `deleted`='0'
        AND
        `hrpac_vacancy_id`='{$vacancy_id}'
        ";

        $result=$db->query($sql,1);
        $rows=array();
        while (($row=$db->fetchByAssoc($result)) != null) {
            $this->get_data($vacancy_id,$row['hrpac_candidates_id']);
            $rows[]=$this->pull_data();
        }
        return $rows;
    }

    /*
     * Получаем данные по всем вакансиям кандидата
     * @param string $candidates_id id кандидата
     * @return array возвращает массив данных по всем вакансиям кандидата
     */
    public function pull_data_for_candidates($candidates_id){
        global $db;
        $sql="
        SELECT `hrpac_vacancy_id`
        FROM `hrpac_vacancy_hrpac_candidates_1`
        WHERE
        `deleted`='0'
        AND
        `hrpac_candidates_id`='{$candidates_id}'
        ";

        $result=$db->query($sql,1);
        $rows=array();
        while (($row=$db->fetchByAssoc($result)) != null) {
            $this->get_data($row['hrpac_vacancy_id'],$candidates_id);
            $rows[]=$this->pull_data();
        }
        return $rows;
    }

    /*
     * Создангие связи кандидата и  этапов подбора из шаблона указанного в вакансии
     * @param string $vacancy_id id вакансии
     * @param string $candidates_id  id кандидата
     *
     */
    public function add_relate_candidater_stage($vacancy_id, $candidates_id) {
        global $db ,$timedate;
        // получаем id шаблона этапов подбора
        $sql="
        SELECT `stage_templates_id`
        FROM `stage_templates_hrpac_vacancy`
        WHERE
        `deleted`='0'
        AND
        `hrpac_vacancy_id`='{$vacancy_id}'
        LIMIT 1
        ";
        $stage_templates_id=$db->getOne($sql,1);
        // получаем список id этапов
        $sql="
        SELECT `hrpac_selection_stage_id` , `sort`
        FROM `stage_templates_hrpac_selection_stage_1`
        WHERE
        `stage_templates_id`='{$stage_templates_id}'
        AND
        `deleted`='0'
        ";
        $result=$db->query($sql,1);
        $rows=array();
        while (($row=$db->fetchByAssoc($result)) != null) {
            $rows[] = $row;
        }
        $query="INSERT INTO `hrpac_selection_stage_candidates_1` (`id`, `date_modified`, `deleted`, `hrpac_candidates_id`, `sort`, `done`, `hrpac_vacancy_id`, `hrpac_selection_stage_id`, `this_stage`, `date_start_stage`) VALUES";
        $query_data=array();
        foreach ($rows as $k => $v) {
            $date_start_stage='NULL';
            $this_stage='0';
            if ($v['sort']=='1'){
                //при создании связи вакансии и кандидата текущим этапом делаем 1
                $this_stage='1';
                $date_start_stage = "'".gmdate($timedate->get_db_date_time_format()) ."'";
            }
            $new_id=create_guid();
            $date_modifide = gmdate($timedate->get_db_date_time_format());
            $query_data[]="('{$new_id}', '{$date_modifide}', '0', '{$candidates_id}', '{$v['sort']}', NULL, '{$vacancy_id}', '{$v['hrpac_selection_stage_id']}', '{$this_stage}', {$date_start_stage})";
        }
        $query=$query.' '.implode(',', $query_data);
        $db->query($query,1);
    }

    /*
     * Удаление связи кандидата и  этапов подбора из шаблона указанного в вакансии
     * @param string $vacancy_id id вакансии
     * @param string $candidates_id  id кандидата
     *
     */
    public function delete_relate_candidater_stage($vacancy_id, $candidates_id) {
        global $db ,$timedate;

        $query="UPDATE `hrpac_selection_stage_candidates_1`
                SET `deleted`='1'
                WHERE
                `hrpac_candidates_id`='{$candidates_id}'
                AND
                `hrpac_vacancy_id`='{$vacancy_id}'";
        $db->query($query,1);
    }
        /*
         * Получение id Текущего этапа для вакансии и кандидата
         * @param string $vacancy_id id вакансии
         * @param string $candidates_id  id кандидата
         *
         */
    public function get_id_this_stage($vacancy_id, $candidates_id){
        global $db, $timedate;
        $sql="SELECT `ssc`.hrpac_selection_stage_id
        FROM `hrpac_selection_stage_candidates_1` `ssc`
        WHERE
        `ssc`.`deleted`='0'
        AND `ssc`.`hrpac_vacancy_id`='{$vacancy_id}'
        AND `ssc`.`hrpac_candidates_id`='{$candidates_id}'
        AND `ssc`.`this_stage`='1'";
        $this_stage=$db->getOne($sql,1);
        return $this_stage;
    }

    public function closed_stage($vacancy_id, $candidates_id, $stage_id){
        global $db, $timedate;
        $sql="UPDATE hrpac_selection_stage_candidates_1
        SET `done`='1', `this_stage`='0'
        WHERE
        `hrpac_vacancy_id`='{$vacancy_id}'
        AND `hrpac_candidates_id`='{$candidates_id}'
        AND `hrpac_selection_stage_id`= '{$stage_id}'
        AND `deleted`='0'
        ";
        $db->query($sql,1);
    }
    public function update_query_new_stage($vacancy_id, $candidates_id,$new_stage_id){
        global $db, $timedate;
        $date_start_stage = gmdate($timedate->get_db_date_time_format());
        $sql="
            UPDATE hrpac_selection_stage_candidates_1
            SET `this_stage`='1', `date_start_stage`='{$date_start_stage}'
            WHERE
            `hrpac_vacancy_id`='{$vacancy_id}'
            AND `hrpac_candidates_id`='{$candidates_id}'
            AND `hrpac_selection_stage_id`= '{$new_stage_id}'
            AND `deleted`='0'
        ";
        $db->query($sql,1);
    }



    public function change_this_stage_for_candidates($vacancy_id, $candidates_id,$new_stage_id){
        global $sugar_config;

        //Отправка письма нанимающему менеджеру если кандидат перешёл на этап Резюме у заказчика (HRCRM-586)
        if ($sugar_config['temp_actionSendEmail']['customer_CV'] == $new_stage_id) {
            require_once 'custom/include/CustomClass/custom_send_email.php';
            $temp_ase = new temp_actionSendEmail();
            $temp_ase->start_action($vacancy_id, $candidates_id, 'customer_CV');
        }

        $old_stage_id=$this->get_id_this_stage($vacancy_id, $candidates_id);
        //помечаем текущий этап вырполненным
        $this->closed_stage($vacancy_id, $candidates_id,$old_stage_id);
        //меняем статус на текущий
        $this->update_query_new_stage($vacancy_id, $candidates_id,$new_stage_id);
        //проверяем есть ли промежуточные этапы
        if($new_stage_id!=$sugar_config['closet_stage']) {
            $this->search_intermediate_stages($vacancy_id, $candidates_id);
        }
        if($new_stage_id == $sugar_config['final_stage']){
            HRPAC_VACANCY::check_final_amount($vacancy_id);
        }

        ##  сохранение в истории
        $this->save_history($vacancy_id, $candidates_id, $new_stage_id);
    }

    public function search_intermediate_stages($vacancy_id, $candidates_id){
        global $db;
        $sql="
            SELECT `sort`
            FROM `hrpac_selection_stage_candidates_1`
            WHERE
            `hrpac_vacancy_id`='{$vacancy_id}'
            AND `hrpac_candidates_id`='{$candidates_id}'
            AND `this_stage`= '1'
            AND `deleted`='0'
        ";
        $sort=$db->getOne($sql,1);
        // сбрасываем данные по этапам, котрые идут после выбранного этапа
        $sql="
            UPDATE `hrpac_selection_stage_candidates_1`
            SET `done`= NULL , `date_start_stage`= NULL
            WHERE
             `hrpac_vacancy_id`='{$vacancy_id}'
            AND `hrpac_candidates_id`='{$candidates_id}'
            AND `sort`> '{$sort}'
            AND `deleted`='0'
            AND (
              `done` IS NOT NULL
              OR `date_start_stage`IS NOT NULL
            )
        ";
        //сбрасываем предидущие этапы если в них была ввеедена информация о старте или выполнении этапа
        $db->query($sql,1);
        $sql="
            SELECT `id`, `sort`
            FROM `hrpac_selection_stage_candidates_1`
            WHERE
             `hrpac_vacancy_id`='{$vacancy_id}'
            AND `hrpac_candidates_id`='{$candidates_id}'
            AND `sort`< '{$sort}'
            AND `deleted`='0'
            AND (`done`<> '1' OR `done` IS NULL )
            ORDER BY `sort`
        ";
        //получаем список незавершенных этапов
        $result=$db->query($sql,1);

        //перебераем каждую из записей и проставляем необходимые данные
        while ($row = $db->fetchByAssoc($result)) {
            $sql="
                SELECT `date_start_stage`
                FROM `hrpac_selection_stage_candidates_1`
                WHERE
                 `hrpac_vacancy_id`='{$vacancy_id}'
                AND `hrpac_candidates_id`='{$candidates_id}'
                AND `sort`> '{$row['sort']}'
                AND `deleted`='0'
                AND `date_start_stage` IS NOT NULL
                ORDER BY `sort` ASC
                LIMIT 1
            ";
            $date_stage=$db->getOne($sql,1);
            $sql="
                UPDATE `hrpac_selection_stage_candidates_1`
                SET `done`= '1' , `date_start_stage`= '{$date_stage}'
                WHERE
                 `id`='{$row['id']}'
            ";
            $db->query($sql,1);

        }

    }


    public function set_category_and_reasons($vacancy_id, $candidates_id,$new_stage_id,$rejection_categories,$rejection_reasons){
        //добавляем причину отказа и категорию отказа
        global $db;
        $sql="
            UPDATE hrpac_selection_stage_candidates_1
            SET `rejection_categories`='{$rejection_categories}', `rejection_reasons`='{$rejection_reasons}'
            WHERE
            `hrpac_vacancy_id`='{$vacancy_id}'
            AND `hrpac_candidates_id`='{$candidates_id}'
            AND `hrpac_selection_stage_id`= '{$new_stage_id}'
            AND `deleted`='0'
        ";
        $db->query($sql,1);

    }

    public function set_reason_for_return($vacancy_id, $candidates_id, $reason_for_return){
        global $db, $sugar_config;
        $sql="
            UPDATE hrpac_selection_stage_candidates_1
            SET `reason_for_return`='{$reason_for_return}'
            WHERE
            `hrpac_vacancy_id`='{$vacancy_id}'
            AND `hrpac_candidates_id`='{$candidates_id}'
            AND `hrpac_selection_stage_id`= '{$sugar_config['closet_stage']}'
            AND `deleted`='0'
        ";
        $db->query($sql,1);

    }

    public function pull_data_for_user($status=array(),$filter=array()){
        global $current_user,$db;
        if(empty($status)){
            $status=HRPAC_VACANCY_STATUSES::get_statuses();
        }
        $where_filter=array();
        //Преобразуем id статусов из ключей в значения
        $status_filter=array_keys ($status);
        //добавляем в общий фильтр
        $where_filter[]='`hrpac_vacancy_statuses_id_c` IN ('."'". implode('\',\'',$status_filter)."')";
        //ели не пустой фильтр перебераем условия
        if (!empty($filter)) {
            //перебираем переданные фильтры
            foreach ($filter as $k => $v) {
                $where_filter[]='`'.$k.'` LIKE \'%'.$v.'%\'';
            }
        }
        $WHERE=implode(' AND ',$where_filter);
        $sql="
            SELECT `id`
            FROM `hrpac_vacancy`
            WHERE
            {$WHERE}
            AND `deleted`='0'
        ";
        //print_array($sql);
       $result=$db->query($sql,1);
        while (($row=$db->fetchByAssoc($result)) != null) {
            $rows[]=$this->pull_data_for_panel($row['id']);
        }

        //print_array(var_export($rows,1),0,1);
        return $rows;

    }

    /*
  * Получение строки финального массива для панели подбора(1 элемент вакансии)
  * @param string $vacancy_id id вакансии
  * @return array массив вакансии со связанными кандидатами и этапами (1 вакансия)
  *
  */
    public function pull_data_for_panel($vacancy_id)
    {
        global $db;
        $sql="
        SELECT `hrpac_candidates_id`
        FROM `hrpac_vacancy_hrpac_candidates_1`
        WHERE
        `deleted`='0'
        AND
        `hrpac_vacancy_id`='{$vacancy_id}'
";

        $result=$db->query($sql,1);
        $candidates_ids=array();
        while (($row = $db->fetchByAssoc($result)) != null) {

            $candidates_ids[] = $row['hrpac_candidates_id'];
        }
        $rows=$this->get_data_panel($vacancy_id,$candidates_ids);
        //print_array($rows,0,1);
        return $rows;

    }

    /*
      * Удаление связи кандидата и  этапов подбора из шаблона указанного в вакансии
      * @param string $vacancy_id id вакансии
      * @param array $candidates_ids id кандидатов
      * @return array массив вакансии со связанными кандидатами и этапами (1 вакансия)
      *
      */
    public function get_data_panel($vacancy_id,$candidates_ids){
        global $db;

        $return = [];
        $seedVacancy = BeanFactory::getBean('HRPAC_VACANCY',$vacancy_id);
        foreach($seedVacancy->field_name_map as $k => $v){
            $return[$v['name']] = $seedVacancy->{$v['name']};
            if ($v['name'] == 'currency_id'){
                $return['currency_id']=CustomCurrency::get_currensy_symbol($seedVacancy->currency_id);
            }
        }

        if (!empty($candidates_ids)){
            foreach ($candidates_ids as $v){
                $candidat_data=[];
                $candidat_data=Custom_data_modull::set('HRPAC_CANDIDATES', $v);
                $candidat_data['date_vacancy_candidates_rel']=$this->get_date_vacancy_candidates_rel($vacancy_id,$v);
                $return['candidates'][] = $candidat_data;
            }
        }

        $return['stages'] = $this->get_stage_panel($vacancy_id,$candidates_ids);

        return $return;
    }

    /**
     * @return mixed
     */
    public function get_date_vacancy_candidates_rel($vacancy_id,$candidat_id)
    {
        global $db, $current_user,$timedate;
        $sql="
        SELECT `date_modified`
        FROM `hrpac_vacancy_hrpac_candidates_1`
        WHERE
        `hrpac_vacancy_id`='{$vacancy_id}'
        AND `hrpac_candidates_id`='{$candidat_id}'
        AND `deleted`=0
        LIMIT 1
        ";
        $result=$db->getOne($sql,1);
        $user_offset=$timedate->getUserUTCOffset();
        $time_unix=strtotime($result);
        $time_unix=$time_unix + ($user_offset*60);
        $finish_date=date($timedate->get_db_date_time_format(),$time_unix);
        return $finish_date;
    }

    /**
     * @return mixed
     */
    public static function get_stage_panel($vacancy_id){
        $stages=Custom_podbor_vacansy_candidates::get_stage_list_for_vacancy($vacancy_id);
        $candidates_arr=Custom_podbor_vacansy_candidates::get_thisStage_candidates_for_vacansy($vacancy_id);
        if(!empty($candidates_arr)){
            foreach ($candidates_arr as $k => $v){
                $stages[$k]['candidates_ids'] = $v;
            }
        }
        return $stages;
    }

    public static function get_thisStage_candidates_for_vacansy($vacancy_id){
        global $db;

        $sql = "SELECT *
                FROM `hrpac_selection_stage_candidates_1`
                WHERE
                `hrpac_vacancy_id`='{$vacancy_id}'
                AND
                `this_stage`='1'
                AND
                `deleted`='0'
";
        $result = $db->query($sql,1);
        $rows = [];
        while (($row = $db->fetchByAssoc($result)) != null){
            $rows[$row['hrpac_selection_stage_id']][] = $row['hrpac_candidates_id'];
        }

        return $rows;
    }

    public static function get_stage_list_for_vacancy($vacancy_id){
        global $db;
        $sql="SELECT ss.*, ts.`sort`
                FROM `stage_templates_hrpac_vacancy` tv
                INNER JOIN `stage_templates_hrpac_selection_stage_1` ts ON ts.`stage_templates_id`=tv.`stage_templates_id` AND ts.`deleted`='0'
                RIGHT JOIN `hrpac_selection_stage` ss ON ss.`id`=ts.`hrpac_selection_stage_id` AND ss.`deleted`='0'
                WHERE
                  tv.`hrpac_vacancy_id`='{$vacancy_id}'
                  AND
                  tv.`deleted`='0'
                  ORDER BY ts.`sort` ASC
                ";
        $result=$db->query($sql,1);
        $rows=array();
        while (($row=$db->fetchByAssoc($result)) != null) {

            $rows[$row['id']]= $row;
            $rows[$row['id']]['candidates_ids']= NULL;
        }
        return $rows;
    }

    public static function get_max_stage_percent($hrpac_vacancy_id) {
        global $db, $sugar_config;
        $closet_stage = isset($sugar_config['closet_stage']) ? $sugar_config['closet_stage'] : '';
        $final_stage = isset($sugar_config['final_stage']) ? $sugar_config['final_stage'] : '';
        $sql = "
        SELECT MAX(h1.sort) as `sort`, MAX(h2.sort) as `max`
        FROM `hrpac_selection_stage_candidates_1` as h1, `hrpac_selection_stage_candidates_1` as h2
        WHERE
        h1.`deleted`='0'
        AND
        h1.`hrpac_vacancy_id`='{$hrpac_vacancy_id}'
        AND
        h1.hrpac_candidates_id NOT IN (SELECT h3.`hrpac_candidates_id` FROM `hrpac_selection_stage_candidates_1` h3 WHERE h3.`date_start_stage` IS NOT NULL AND h3.`deleted`='0' AND h3.`hrpac_selection_stage_id`= '{$closet_stage}' AND  h3.`hrpac_vacancy_id`='{$hrpac_vacancy_id}')
        AND
        h1.`date_start_stage` IS NOT NULL
        AND
        h2.`deleted`='0'
        AND
        h2.`hrpac_vacancy_id`='{$hrpac_vacancy_id}'
        ";
        $sql = "
                SELECT  MAX(h1.sort / h2.sort)# h1.sort  as `progress` , MAX(h2.sort) as `max_stage`
                FROM `hrpac_selection_stage_candidates_1` as h1, `hrpac_selection_stage_candidates_1` as h2
                WHERE
                h1.`deleted`='0'
                AND
                h1.`hrpac_vacancy_id`='{$hrpac_vacancy_id}'
                AND
                h1.`hrpac_selection_stage_id` =(
                                         SELECT h4.`hrpac_selection_stage_id`
                                         FROM `hrpac_selection_stage_candidates_1` as h4
                                         WHERE h4.`deleted` = '0'
                                         AND h4.`hrpac_vacancy_id`='{$hrpac_vacancy_id}'
                                         AND h4.`hrpac_candidates_id`=h1.`hrpac_candidates_id`
                                         AND h4.`hrpac_selection_stage_id` NOT IN ('{$closet_stage}','{$final_stage}') #этап не равен отказу и принят
                                         AND h4.date_start_stage IS NOT NULL #дата старта этапа начата
                                         AND h4.`this_stage` = '1'
                                          ORDER BY h4.`sort` DESC
                                         LIMIT 1
                                       ) # получаем максимальный достигнутый этап кандидатом за исключением этапа принят
                AND h2.`hrpac_candidates_id` = h1.`hrpac_candidates_id`
                AND
                h2.`deleted`='0'
                AND
                h2.`hrpac_vacancy_id`='{$hrpac_vacancy_id}'
                AND
                h2.`hrpac_selection_stage_id` =(
                                         SELECT h5.`hrpac_selection_stage_id`
                                         FROM `hrpac_selection_stage_candidates_1` as h5
                                         WHERE h5.`deleted` = '0'
                                         AND h5.`hrpac_vacancy_id`='{$hrpac_vacancy_id}'
                                         AND h5.`hrpac_candidates_id`=h2.`hrpac_candidates_id`
                                         AND h5.`hrpac_selection_stage_id`<> '{$closet_stage}' #этап не равен отказу
                                          ORDER BY h5.`sort` DESC
                                         LIMIT 1
                                       ) # получаем максимальный достигнутый этап кандидатом
        ";
        $result = $db->getOne($sql,1);
        $result=$result*100;
        //print_array($sql,1);
        return $result;
    }


    /**
     * @return void
     * сохранение перевода на этап в истории
     */
    private function save_history($vacancy_id, $candidat_id, $stage_id){
        ##  сохранение  истории -- добавление записи в аудит
        $vacancy    =   new HRPAC_VACANCY();
        $vacancy->retrieve($vacancy_id);

        $stage      =   new HRPAC_SELECTION_STAGE();
        $stage->retrieve($stage_id);

        global  $app_list_strings;
        $message    =   "{$app_list_strings['moduleList']['HRPAC_CANDIDATES_CHANGE_STAGE'   ]} '{$stage->name}' ".
                        "{$app_list_strings['moduleList']['HRPAC_VACANCY_TITLE'             ]}: {$vacancy->name}, {$vacancy->project_link_id}, {$vacancy->stack}";
        Custom_Audit::save([
            'table'         =>  'hrpac_candidates',
            'parent_id'     =>  $candidat_id,
            'field_name'    =>  '',
            'value_old'     =>  '',
            'value_new'     =>  $message,
        ]);
    }

}