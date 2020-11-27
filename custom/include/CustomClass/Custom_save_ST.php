<?php
/**
 * Created by PhpStorm.
 * User: seedteam
 * Date: 14.11.19
 * Time: 21:13
 */
class Custom_save_ST
{
    public $fields;
    public $is_new;
    public $chek_rell_arr;
    public function __construct($var)
    {
        $this->fields=$var;
        $this->is_new=true;
        $this->chek_rell_arr=array();
    }

    public function save_ob(){
        $seedST= new STAGE_Templates();
        //print_array($this->fields['record']);
        if(!empty($this->fields['record'])){
            $this->is_new=false;
            $seedST->retrieve($this->fields['record']);
            //print_array( $seedST);
        }
        // перебераем реквест в поисках полей
        foreach ($seedST->field_name_map as $k=>$v){
            if(isset($this->fields[$k])) {
                $seedST->$k=$this->fields[$k];
            }
        }
        $seedST->check_main_template();
        $seedST->save();
        if($this->is_new){
            $this->add_relate($seedST->id, $this->fields['select_stage_data']);
        } else{
            //print_array($seedST->id,0,1);
            $this->check_rel($seedST->id, $this->fields['select_stage_data']);
            $this->set_chenged_stage_template_list($seedST->id);

        }
    }
    //даляем существующие связи создаем новые
    public function check_rel($id_ST,$arr){
        global $db;
        //print_array($id_ST,0,1);
        if(is_array($arr)) {
            $sql="UPDATE `stage_templates_hrpac_selection_stage_1`
            SET `deleted`='1'
                        WHERE 
                        `stage_templates_id`='{$id_ST}'
                        AND 
                        `deleted`='0'";
            $db->query($sql,1);
            $this->add_relate($id_ST,$arr);
        }
    }
    public function update_rell($arr)
    {
        global $db,$timedate;
        foreach ($arr as $k => $v) {
            $date_modifide = gmdate($timedate->get_db_date_time_format());
            $sql="UPDATE `stage_templates_hrpac_selection_stage_1` 
                    SET `sort`='{$v['sort']}'
                    WHERE
                    `id`='{$v['id']}'
                    AND 
                    `deleted`='0'";
            $db->query($sql,1);
        }
    }
// добавляем новые связи
    public function add_relate($id_ST,$arr){
        global $timedate, $db;
        // если массив то разбераем его и добавляем связи
        if(is_array($arr)) {
            $sql = "INSERT INTO `stage_templates_hrpac_selection_stage_1` (`id`, `date_modified`, `deleted`, `stage_templates_id`, `hrpac_selection_stage_id`, `sort`) VALUES";
            $arr_values=array();
            foreach ($arr as $k => $v) {
                if(!empty($v['id']) & !empty($v['sort'])) {
                    $guid = create_guid();
                    $date_modifide = gmdate($timedate->get_db_date_time_format());
                    $arr_values[]= "('{$guid}', '{$date_modifide}', '0', '{$id_ST}', '{$v['id']}' , '{$v['sort']}')";
                }
            }
            $sql=$sql.implode(",", $arr_values);
            $db->query($sql,1);
        }
    }

    public function set_chenged_stage_template_list($stage_template_id) {
        global $db;
        $q = "
            UPDATE
                `stage_templates`
            SET
                `stage_templates`.`chenged_stage_template_list` = 1
            WHERE
                `stage_templates`.`id` = '{$stage_template_id}'
        ";
        $db->query($q, true);
    }
}