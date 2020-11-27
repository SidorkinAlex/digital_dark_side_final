<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.09.20
 * Time: 10:38
 */
class CustomGetPopupFields
{
    public $filter_fields = [];
    public $seed;
    public $ret_array;

    /**
     * CustomGetPopupFields constructor.
     * @param array $filter_fields
     * @param SugarBean $seed
     * @param array $ret_array
     */
    public function __construct(array $filter_fields, SugarBean $seed,array $ret_array)
    {
        $this->filter_fields = $filter_fields;
        $this->seed = $seed;
        $this->ret_array = $ret_array;

    }

    /**
     * Возвращает массив для формирования запроса
     * @return array
     */
    public function getCustomRetArray()
    {

        foreach ($this->filter_fields as $k => $v) {
            if (array_key_exists('rel_link', $this->seed->field_name_map[$k])) {

              $this->relLink($k, $v);

            }
        }

        return $this->ret_array;
    }

    public function relLink($k, $v){
        // получаем связь
        $rellObj = SugarRelationshipFactory::getInstance()->getRelationship($this->seed->field_name_map[$k]['rel_link']);
        if (!empty($rellObj->def['lhs_table']) && $this->seed->table_name == $rellObj->def['lhs_table']) {
            $this->seedKey = 'lhs';
            $subKey = 'rhs';
        } elseif (!empty($rellObj->def['rhs_table']) && $this->seed->table_name == $rellObj->def['rhs_table']) {
            $this->seedKey = 'rhs';
            $subKey = 'lhs';
        }
        $table = $rellObj->getRelationshipTable();
        $hach_table = generator_letters(3);
        // преодразуем массив переменных в строку для запроса
        if (is_array($this->seed->field_name_map[$k]['detail_format'])) {
            $selectedFilds = "`" . implode("` , ' ' , `", $this->seed->field_name_map[$k]['detail_format']) . '`';
        } else {
            $GLOBALS['log']->fatal("error vardef type in: {$this->seed->module_dir} -> {$k} [ 'detail_format' ]  should be array");
            return false;
        }
        $joinFild = $rellObj->def["join_key_{$subKey}"];
        $joinFildSeed = $rellObj->def["join_key_{$this->seedKey}"];
        $this->ret_array['select'] .= ",
                        ( SELECT GROUP_CONCAT({$selectedFilds})
                          FROM `{$table}` `{$hach_table}`
                          RIGHT JOIN `{$this->seed->field_name_map[$k]['table']}` ON `{$this->seed->field_name_map[$k]['table']}`.`id` = `{$hach_table}`.`{$joinFild}`
                          WHERE
                          `{$hach_table}`.`deleted`='0' AND
                          `{$hach_table}`.`{$joinFildSeed}`= `{$this->seed->table_name}`.`id` AND
                          `{$hach_table}`.`{$rellObj->def['relationship_role_column']}` = '{$rellObj->def['relationship_role_column_value']}'

                        ) as `{$k}`
                    ";
    }
}