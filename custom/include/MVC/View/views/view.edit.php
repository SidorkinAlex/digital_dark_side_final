<?php
/**
 * Created by PhpStorm.
 * User: seedteam
 * Date: 13.03.20
 * Time: 13:44
 */
require_once 'include/MVC/View/views/view.edit.php';
class CustomViewEdit extends ViewEdit {
    public function display()
    {
        $this->ev->process();
        $this->afterEVprocess();
        echo $this->ev->display($this->showTitle);
    }

    /*
     * Место для костамизации данных в переменной fields
     *
     */

    public function afterEVprocess() {
        foreach ($this->ev->fieldDefs as $k=>$v) {
            if(isset($v['type'])){
                switch ($v['type']) {
                    case 'MeansCommunication':
                        if(!is_array($v['value'])) {
                            $this->ev->fieldDefs[$k]['value'] = [];
                        }
                        break;
                    case 'relate':
                        if(!empty($v['table']) && !empty($v['fields_in_table'])) {
                            $array=table_sql_list($v['table'],$v['fields_in_table']);
                            $this->ev->fieldDefs[$k]['option']=$array;
                        }
                        break;
                    default:
                        break;
                }
            }
            // обработчик rel_link
            if(isset($v['rel_link']) && !empty($v['rel_link'])){
                //Добавляем опшены в поля связи
                $array=table_sql_list($this->ev->fieldDefs[$k]['table'],$this->ev->fieldDefs[$k]['fields_in_table']);
                $this->ev->fieldDefs[$k]['option']=$array;
                //добавляем значение в виде текста
                $this->ev->fieldDefs[$k]['names_val']=implode(',',findArray($array,$this->ev->fieldDefs[$k]['value'],$this->ev->fieldDefs[$k]['detail_format']));
                //приводим массив к строке, что бы не падал фронт
                if(!empty($this->ev->fieldDefs[$k]['value'])) {
                    foreach ($this->ev->fieldDefs[$k]['value'] as $kk => $vv) {
                        $this->ev->fieldDefs[$k]['value'][$kk] = (string)$vv;
                    }
                }
            }
        }
    }
}