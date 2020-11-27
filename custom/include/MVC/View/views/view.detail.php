<?php
/**
 * Created by PhpStorm.
 * User: seedteam
 * Date: 13.03.20
 * Time: 11:59
 */
require_once 'include/MVC/View/views/view.detail.php';

class CustomViewDetail extends ViewDetail {
    public function display()
    {
        if (empty($this->bean->id)) {
            sugar_die($GLOBALS['app_strings']['ERROR_NO_RECORD']);
        }
        $this->dv->process();
        $this->afterDVprocess();
        echo $this->dv->display();
    }

    public function afterDVprocess() {
        foreach ($this->dv->fieldDefs as $k=>$v) {
            if(isset($v['rel_link']) && !empty($v['rel_link'])){
                $array=table_sql_list($this->dv->fieldDefs[$k]['table'],$this->dv->fieldDefs[$k]['fields_in_table']);

                $this->dv->fieldDefs[$k]['value']=implode(',',findArray($array,$this->dv->fieldDefs[$k]['value'],$this->dv->fieldDefs[$k]['detail_format']));
            }
        }

    }
}