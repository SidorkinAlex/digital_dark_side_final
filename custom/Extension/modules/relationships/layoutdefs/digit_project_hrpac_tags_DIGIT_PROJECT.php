<?php
 // created: 2020-10-17 09:03:29
$layout_defs["DIGIT_PROJECT"]["subpanel_setup"]['digit_project_hrpac_tags'] = array (
  'order' => 100,
  'module' => 'HRPAC_TAGS',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_DIGIT_PROJECT_HRPAC_TAGS_FROM_HRPAC_TAGS_TITLE',
  'get_subpanel_data' => 'digit_project_hrpac_tags',
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
