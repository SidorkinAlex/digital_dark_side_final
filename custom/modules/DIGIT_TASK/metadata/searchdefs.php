<?php
$module_name = 'DIGIT_TASK';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'default' => true,
        'name' => 'status',
      ),
      'date_start' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_START',
        'width' => '10%',
        'default' => true,
        'name' => 'date_start',
      ),
      'date_stop' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_STOP',
        'width' => '10%',
        'default' => true,
        'name' => 'date_stop',
      ),
      'priority' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_PRIORITY',
        'width' => '10%',
        'default' => true,
        'name' => 'priority',
      ),
      'complexity' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_COMPLEXITY',
        'width' => '10%',
        'default' => true,
        'name' => 'complexity',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'type',
      ),
      'source' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_SOURCE',
        'width' => '10%',
        'default' => true,
        'name' => 'source',
      ),
      'task_manager_name' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_TASK_MANAGER_NAME',
        'id' => 'USER_ID_C',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'task_manager_name',
      ),
      'control' => 
      array (
        'type' => 'bool',
        'default' => true,
        'width' => '2%',
        'label' => 'LBL_CONTROL',
        'name' => 'control',
      ),
      'capacity' => 
      array (
        'type' => 'int',
        'default' => true,
        'label' => 'LBL_CAPACITY',
        'width' => '10%',
        'name' => 'capacity',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'width' => '10%',
        'default' => true,
      ),
      'tags_ids' => 
      array (
        'type' => 'multienum',
        'studio' => 'visible',
        'label' => 'LBL_HRPAC_TAGS',
        'function' => 
        array (
          'name' => 'table_list_arr',
          'params' => 
          array (
            0 => 'hrpac_tags',
            1 => 
            array (
              0 => 'name',
            ),
            2 => '
                                `my_tag` = 0
                                OR (
                                    `my_tag` = 1
                                    AND `assigned_user_id` = \'\'
                                )
                            ',
            3 => 'name',
          ),
        ),
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'tags_ids',
      ),
        'digit_workshop_id' =>
            array(
                'name' => 'digit_workshop_id',
                'type' => 'multienum',
                'studio' => 'visible',
                'label' => 'LBL_DIGIT_WORKSHOP_NAME',
                'function' =>
                    array(
                        'name' => 'table_list_arr',
                        'params' =>
                            array(
                                0 => 'digit_workshop',
                                1 =>
                                    array(
                                        0 => 'name',
                                    ),
                                2 => "",
                                3 => 'name',
                            ),
                    ),
                'link' => true,
                'width' => '10%',
                'default' => true,
            ),
        'digit_section_id' =>
            array(
                'name' => 'digit_section_id',
                'type' => 'multienum',
                'studio' => 'visible',
                'label' => 'LBL_DIGIT_SECTION_NAME',
                'function' =>
                    array(
                        'name' => 'table_list_arr',
                        'params' =>
                            array(
                                0 => 'digit_section',
                                1 =>
                                    array(
                                        0 => 'name',
                                    ),
                                2 => "",
                                3 => 'name',
                            ),
                    ),
                'link' => true,
                'width' => '10%',
                'default' => true,
            ),
        'digit_block_id' =>
            array(
                'name' => 'digit_block_id',
                'type' => 'multienum',
                'studio' => 'visible',
                'label' => 'LBL_DIGIT_SECTION_NAME',
                'function' =>
                    array(
                        'name' => 'table_list_arr',
                        'params' =>
                            array(
                                0 => 'digit_block',
                                1 =>
                                    array(
                                        0 => 'name',
                                    ),
                                2 => "",
                                3 => 'name',
                            ),
                    ),
                'link' => true,
                'width' => '10%',
                'default' => true,
            ),
    ),
    'advanced_search' => 
    array (
      0 => 'name',
      1 => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
;
?>
