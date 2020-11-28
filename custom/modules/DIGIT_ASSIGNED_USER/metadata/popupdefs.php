<?php
$popupMeta = array (
    'moduleMain' => 'DIGIT_ASSIGNED_USER',
    'varName' => 'DIGIT_ASSIGNED_USER',
    'orderBy' => 'digit_assigned_user.name',
    'whereClauses' => array (
  'name' => 'digit_assigned_user.name',
  'digit_task_name' => 'digit_assigned_user.digit_task_name',
  'executor_name' => 'digit_assigned_user.executor_name',
  'assigned_user_id' => 'digit_assigned_user.assigned_user_id',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'digit_task_name',
  5 => 'executor_name',
  6 => 'assigned_user_id',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'digit_task_name' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_DIGIT_TASK_NAME',
    'id' => 'DIGIT_TASK_ID',
    'link' => true,
    'width' => '10%',
    'name' => 'digit_task_name',
  ),
  'executor_name' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_EXECUTOR_NAME',
    'id' => 'USER_ID_C',
    'link' => true,
    'width' => '10%',
    'name' => 'executor_name',
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
  ),
),
);
