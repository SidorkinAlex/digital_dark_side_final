<?php   ##                                                                            ##

/*
 *  Класс кастомного аудита
 */
class Custom_Audit {

    /*
     * Сохраняет запись в аудите заданного модуля
     * массив $data должен имет следующие поля:
     * $data['module']      --  модуль, над элементом которого производится аудит       [*]
     * $data['parent_id']   --  ид аудируемого элемента                                 [*]
     * $data['field_name']  --  имя поля                                                [ ]
     * $data['value_old']   --  старое значение поля                                    [ ]
     * $data['value_new']   --  новое значение поля                                     [ ]
     *
     * пример: Custom_Audit::audit([
     *      'table'         =>  $bean->table_name,
     *      'parent_id'     =>  $bean->id,
     *      'field_name'    =>  'some_name',
     *      'value_old'     =>  'old_value',
     *      'value_new'     =>  'new_value',
     *  ]);
     */
    public  static function save(array $data): boolean{
        global $db, $current_user;
        $table  =   isset($data['table']) ? $data['table'] : '';
        $result =   !empty($table);
        if ($result){
            $id         =   create_guid();
            $parent_id  =   isset($data['parent_id' ]) ? $data['parent_id' ] : '';
            $created_by =   $current_user->id;
            $field_name =   isset($data['field_name']) ? $data['field_name'] : '';
            $value_old  =   str_replace("'", "\'", isset($data['value_old' ]) ? $data['value_old' ] : '');
            $value_new  =   str_replace("'", "\'", isset($data['value_new' ]) ? $data['value_new' ] : '');

            $table     .=   '_audit';
            $query      =   "
    insert into `{$table}` (`id`,      `parent_id`, `date_created`,   `created_by`,    `field_name`,  /* `data_type`, */ `before_value_string`, `after_value_string`, `before_value_text`, `after_value_text` )
                    values ('{$id}', '{$parent_id}', NOW(),         '{$created_by}', '{$field_name}', /* `data_type`, */ '{$value_old}',        '{$value_new}',       '{$value_old}',      '{$value_new}'     )";
            $db->query($query, 1);
        }
        return  $result;
    }

}
