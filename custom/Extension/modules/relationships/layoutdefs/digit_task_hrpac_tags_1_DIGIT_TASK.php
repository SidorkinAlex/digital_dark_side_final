<?php
 // created: 2020-10-17 10:05:45
$layout_defs["DIGIT_TASK"]["subpanel_setup"]['digit_task_hrpac_tags_1'] = array (
  'order' => 100,
  'module' => 'HRPAC_TAGS',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_DIGIT_TASK_HRPAC_TAGS_1_FROM_HRPAC_TAGS_TITLE',
  'get_subpanel_data' => 'digit_task_hrpac_tags_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
