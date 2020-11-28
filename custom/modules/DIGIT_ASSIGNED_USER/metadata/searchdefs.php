<?php
$module_name = 'DIGIT_ASSIGNED_USER';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 'name',
      1 => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
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
        'default' => true,
        'width' => '10%',
      ),
      'date_entered' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'digit_task_name' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_DIGIT_TASK_NAME',
        'id' => 'DIGIT_TASK_ID',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'digit_task_name',
      ),
      'date_modified' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_modified',
      ),
      'modified_user_id' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'modified_user_id',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'type',
      ),
      'created_by' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => true,
        'name' => 'created_by',
      ),
      'executor_name' => 
      array (
        'type' => 'relate',
        'studio' => 'visible',
        'label' => 'LBL_EXECUTOR_NAME',
        'id' => 'USER_ID_C',
        'link' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'executor_name',
      ),
      'description' => 
      array (
        'type' => 'text',
        'studio' => 'visible',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'description',
      ),
      'typical_responses' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPICAL_RESPONSES',
        'width' => '10%',
        'default' => true,
        'name' => 'typical_responses',
      ),
      'current_user_only' => 
      array (
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'width' => '10%',
        'default' => true,
        'name' => 'current_user_only',
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
