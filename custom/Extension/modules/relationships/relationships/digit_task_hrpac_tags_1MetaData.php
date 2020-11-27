<?php
// created: 2020-10-17 10:05:45
$dictionary["digit_task_hrpac_tags_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'digit_task_hrpac_tags_1' => 
    array (
      'lhs_module' => 'DIGIT_TASK',
      'lhs_table' => 'digit_task',
      'lhs_key' => 'id',
      'rhs_module' => 'HRPAC_TAGS',
      'rhs_table' => 'hrpac_tags',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'digit_task_hrpac_tags',
      'join_key_lhs' => 'digit_task_id',
      'join_key_rhs' => 'hrpac_tags_id',
    ),
  ),
  'table' => 'digit_task_hrpac_tags',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'digit_task_id',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'hrpac_tags_id',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'digit_task_hrpac_tags_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'digit_task_hrpac_tags_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'digit_task_id',
        1 => 'hrpac_tags_id',
      ),
    ),
  ),
);