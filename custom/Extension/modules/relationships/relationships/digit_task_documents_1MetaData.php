<?php
// created: 2020-10-17 09:03:49
$dictionary["digit_task_documents_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'digit_task_documents_1' => 
    array (
      'lhs_module' => 'DIGIT_TASK',
      'lhs_table' => 'digit_task',
      'lhs_key' => 'id',
      'rhs_module' => 'Documents',
      'rhs_table' => 'documents',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'digit_task_documents',
      'join_key_lhs' => 'digit_task_id',
      'join_key_rhs' => 'documents_id',
    ),
  ),
  'table' => 'digit_task_documents',
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
      'name' => 'documents_id',
      'type' => 'varchar',
      'len' => 36,
    ),
    5 => 
    array (
      'name' => 'document_revision_id',
      'type' => 'varchar',
      'len' => '36',
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'digit_task_documents_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'digit_task_documents_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'digit_task_id',
        1 => 'documents_id',
      ),
    ),
  ),
);