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
  'DATE_START' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_START',
    'width' => '10%',
    'default' => true,
  ),
  'DATE_STOP' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_STOP',
    'width' => '10%',
    'default' => true,
  ),
  'STATUS' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'DIGIT_PROJECT_NAME' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_DIGIT_PROJECT_NAME',
    'id' => 'DIGIT_PROJECT_ID',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'PRIORITY' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_PRIORITY',
    'width' => '10%',
    'default' => true,
  ),
  'COMPLEXITY' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_COMPLEXITY',
    'width' => '10%',
    'default' => true,
  ),
  'SOURCE' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_SOURCE',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'TASK_MANAGER_NAME' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_TASK_MANAGER_NAME',
    'id' => 'USER_ID_C',
    'link' => true,
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
);
;
?>
