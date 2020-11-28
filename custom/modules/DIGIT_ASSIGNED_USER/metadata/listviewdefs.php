<?php
$module_name = 'DIGIT_ASSIGNED_USER';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'DIGIT_TASK_NAME' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_DIGIT_TASK_NAME',
    'id' => 'DIGIT_TASK_ID',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'TYPICAL_RESPONSES' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPICAL_RESPONSES',
    'width' => '10%',
    'default' => true,
  ),
  'EXECUTOR_NAME' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_EXECUTOR_NAME',
    'id' => 'USER_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
);
;
?>
