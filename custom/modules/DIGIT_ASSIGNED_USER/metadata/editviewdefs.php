<?php
$module_name = 'DIGIT_ASSIGNED_USER';
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
        ),
        1 => 
        array (
          0 => 'description',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'typical_responses',
            'studio' => 'visible',
            'label' => 'LBL_TYPICAL_RESPONSES',
          ),
          1 => 
          array (
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'digit_task_name',
            'studio' => 'visible',
            'label' => 'LBL_DIGIT_TASK_NAME',
          ),
          1 => 
          array (
            'name' => 'executor_name',
            'studio' => 'visible',
            'label' => 'LBL_EXECUTOR_NAME',
          ),
        ),
      ),
    ),
  ),
);
;
?>
