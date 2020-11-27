<?php
// created: 2020-10-17 09:03:29
$dictionary["digit_project_hrpac_tags"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'digit_project_hrpac_tags' => 
    array (
      'lhs_module' => 'DIGIT_PROJECT',
      'lhs_table' => 'digit_project',
      'lhs_key' => 'id',
      'rhs_module' => 'HRPAC_TAGS',
      'rhs_table' => 'hrpac_tags',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'digit_project_hrpac_tags',
      'join_key_lhs' => 'tag_id',
      'join_key_rhs' => 'project_id',
    ),
  ),
  'table' => 'digit_project_hrpac_tags',
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
      'name' => 'tag_id',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'project_id',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'digit_project_hrpac_tagsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'digit_project_hrpac_tags_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
          0 => 'tag_id',
          1 => 'project_id',
      ),
    ),
  ),
);