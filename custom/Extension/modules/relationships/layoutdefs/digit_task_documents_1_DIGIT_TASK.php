<?php
 // created: 2020-10-17 09:03:49
$layout_defs["DIGIT_TASK"]["subpanel_setup"]['digit_task_documents_1'] = array (
  'order' => 100,
  'module' => 'Documents',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_DIGIT_TASK_DOCUMENTS_1_FROM_DOCUMENTS_TITLE',
  'get_subpanel_data' => 'digit_task_documents_1',
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
