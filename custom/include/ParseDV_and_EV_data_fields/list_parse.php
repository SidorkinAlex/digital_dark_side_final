<?php
/**
 * Created by PhpStorm.
 * User: seedteam
 * Date: 27.03.20
 * Time: 15:07
 *
 */
/*
 *
 * Отключено переведено на другой механизм
 */
/*
$array_type = [];
foreach ($this->ss->_tpl_vars['sugarconfig'] as $k => $v) {
    $array_type[$k] = $v['type'];
}
foreach ($this->data['data'] as $k => $v) {
    foreach ($v as $field_name => $value) {
        require_once 'custom/include/CustomClass/Custom_data_modull.php';
        $fild_type_Class = Custom_data_modull::getSugarField_class($array_type[$field_name]);
        include_once $fild_type_Class['file'];
        //проверяем есть ли для типа сактомная обработка списка
        if (method_exists($fild_type_Class['class_name'], 'list_logik')) {
            // если есть задаем значение для списка
            $this->data['data'][$k][$field_name] = $fild_type_Class['class_name']::list_logik($this->data['data'][$k], $this->data['pageData']['bean']['moduleDir']);
        }
    }
}
*/