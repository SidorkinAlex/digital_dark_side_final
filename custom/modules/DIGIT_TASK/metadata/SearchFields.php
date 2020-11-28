<?php
// created: 2020-11-28 22:54:33
$searchFields['DIGIT_TASK'] = array (
  'name' => 
  array (
    'query_type' => 'default',
  ),
  'current_user_only' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'assigned_user_id',
    ),
    'my_items' => true,
    'vname' => 'LBL_CURRENT_USER_FILTER',
    'type' => 'bool',
  ),
  'assigned_user_id' => 
  array (
    'query_type' => 'default',
  ),
  'range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_entered' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_modified' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_start' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_start' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_start' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_stop' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_stop' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_stop' => 
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'tags_ids' => 
  array (
    'query_type' => 'format',
    'operator' => 'subquery',
    'no_quotes' => true,
    'subquery' => '
                SELECT 
                   `digit_task_hrpac_tags`.`digit_task_id` 
                FROM 
                    `digit_task_hrpac_tags`
                WHERE 
                    `digit_task_hrpac_tags`.`deleted` = 0
                    AND `digit_task_hrpac_tags`.`hrpac_tags_id` IN (\'{0}\')
            ',
    'db_field' => 
    array (
      0 => 'id',
    ),
  ),
  'digit_workshop_id' => 
  array (
    'query_type' => 'format',
    'operator' => 'subquery',
    'no_quotes' => true,
    'subquery' => '
                SELECT
                    `digit_assigned_user`.`digit_task_id`
                FROM
                    `digit_assigned_user`
                LEFT JOIN
                    `users` ON `digit_assigned_user`.`user_id_c` = `users`.`id` AND `users`.`deleted` = 0
                LEFT JOIN
                    `digit_workshop` ON `users`.`digit_workshop_id` = `digit_workshop`.`id` AND `digit_workshop`.`deleted` = 0
                WHERE
                    `digit_assigned_user`.`deleted` = 0
                    AND `users`.`digit_workshop_id` IN (\'{0}\')
            ',
    'db_field' => 
    array (
      0 => 'id',
    ),
  ),
  'digit_section_id' => 
  array (
    'query_type' => 'format',
    'operator' => 'subquery',
    'no_quotes' => true,
    'subquery' => '
                SELECT
                    `digit_assigned_user`.`digit_task_id`
                FROM
                    `digit_assigned_user`
                LEFT JOIN
                    `users` ON `digit_assigned_user`.`user_id_c` = `users`.`id` AND `users`.`deleted` = 0
                LEFT JOIN
                    `digit_section` ON `users`.`digit_section_id` = `digit_section`.`id` AND `digit_section`.`deleted` = 0
                WHERE
                    `digit_assigned_user`.`deleted` = 0
                    AND `users`.`digit_section_id` IN (\'{0}\')
            ',
    'db_field' => 
    array (
      0 => 'id',
    ),
  ),
  'digit_block_id' => 
  array (
    'query_type' => 'format',
    'operator' => 'subquery',
    'no_quotes' => true,
    'subquery' => '
                SELECT
                    `digit_assigned_user`.`digit_task_id`
                FROM
                    `digit_assigned_user`
                LEFT JOIN
                    `users` ON `digit_assigned_user`.`user_id_c` = `users`.`id` AND `users`.`deleted` = 0
                LEFT JOIN
                    `digit_block` ON `users`.`digit_block_id` = `digit_block`.`id` AND `digit_block`.`deleted` = 0
                WHERE
                    `digit_assigned_user`.`deleted` = 0
                    AND `users`.`digit_block_id` IN (\'{0}\')
            ',
    'db_field' => 
    array (
      0 => 'id',
    ),
  ),
);