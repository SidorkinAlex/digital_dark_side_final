<?php
$module_name = 'DIGIT_TASK';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'date_start',
            'comment' => 'Date record created',
            'label' => 'LBL_DATE_START',
          ),
          1 => 
          array (
            'name' => 'date_stop',
            'comment' => 'Date record created',
            'label' => 'LBL_DATE_STOP',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'comment' => '',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'priority',
            'comment' => '',
            'label' => 'LBL_PRIORITY',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'source',
            'comment' => '',
            'label' => 'LBL_SOURCE',
          ),
          1 => 
          array (
            'name' => 'task_manager_name',
            'studio' => 'visible',
            'label' => 'LBL_TASK_MANAGER_NAME',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'capacity',
            'label' => 'LBL_CAPACITY',
          ),
          1 => 
          array (
            'name' => 'control',
            'label' => 'LBL_CONTROL',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'complexity',
            'comment' => '',
            'label' => 'LBL_COMPLEXITY',
          ),
          1 => 
          array (
            'name' => 'type',
            'comment' => '',
            'label' => 'LBL_TYPE',
          ),
        ),
        6 => 
        array (
          0 => 'description',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'digit_project_name',
            'studio' => 'visible',
            'label' => 'LBL_DIGIT_PROJECT_NAME',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
;
?>
