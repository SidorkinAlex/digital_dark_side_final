<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.11.20
 * Time: 3:04
 */
include_once COREDIR . 'custom/mobile/views/View.php';

class detailViewDIGIT_TASK extends View
{
    public function process()
    {
        global $db;
        global $app_list_strings;

            $seedDigit_Task=BeanFactory::getBean('DIGIT_TASK',$this->router->request['record']);
            $data_detail=[];
            foreach ($seedDigit_Task->field_name_map as $field_name => $field_arr){
                if($field_arr['type'] == 'link'){

                }elseif ($field_arr['type'] == 'enum'){
                    $data_detail[$field_name] =$app_list_strings[$field_arr['options']][$seedDigit_Task->$field_name];
                } else{
                    $data_detail[$field_name] =$seedDigit_Task->$field_name;
                }
            }
        $data_detail['record']=$this->router->request['record'];
        $this->contentBox->assign('data_detail',$data_detail);
    }
}