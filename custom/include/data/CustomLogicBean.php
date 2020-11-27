<?php

##                                                                    ##
##  РЕАЛИЗАЦИЯ КАСТОМНОЙ ЛОГИКИ                                       ##
##                                                                    ##

/**
 * Created by PhpStorm.
 * User: seedteam
 * Date: 31.03.20
 * Time: 8:20
 */
class CustomLogicBean{

    public function customBeanLogicAll(SugarBean $bean, $event, $arguments){
        foreach ($bean->field_name_map as $k => $v){
            if(!empty($v['last_comments'])){
                $bean->{$k} = HRPAC_COMMENTS::get_last_coment($bean);
            }
            if ($v['type'] == 'MeansCommunication') {
                $ids = Means_of_Communication::get_value($bean->module_dir,$bean->id);
                $bean->{$k} = $ids;
            }
            if ($v['type'] === 'HRPAC_CANDIDATES_JOBS') {
                $bean->{$k} = HRPAC_CANDIDATES_JOBS::get_value($bean->id);
            }
        }
    }
}