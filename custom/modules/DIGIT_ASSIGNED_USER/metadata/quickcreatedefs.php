<?php
$module_name = 'DIGIT_ASSIGNED_USER';
$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
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
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'studio' => 'visible',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'typical_responses',
            'studio' => 'visible',
            'label' => 'LBL_TYPICAL_RESPONSES',
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
