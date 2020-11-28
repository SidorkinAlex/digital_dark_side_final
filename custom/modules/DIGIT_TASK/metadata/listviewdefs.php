<?php
$module_name = 'DIGIT_TASK';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'DATE_PLAN' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_DATE_PLAN',
    'width' => '10%',
    'default' => true,
  ),
  'DATE_FACT' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_DATE_FACT',
    'width' => '10%',
    'default' => true,
  ),
  'CONTROL' => 
  array (
    'type' => 'bool',
    'default' => true,
    'width' => '2%',
    'label' => 'LBL_CONTROL',
  ),
  'DIGIT_TASKS_CLASS' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_DIGIT_TASKS_CLASS',
    'width' => '10%',
    'default' => true,
  ),
  'PARENT_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PARENT_DIGITAL_TASK_NAME',
    'id' => 'PARENT_ID',
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
