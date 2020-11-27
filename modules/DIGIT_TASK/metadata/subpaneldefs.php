<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$layout_defs["DIGIT_TASK"]["subpanel_setup"]['hrpac_comments'] = array(
    'order' => 100,
    'module' => 'HRPAC_COMMENTS',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'date_entered',
    'title_key' => 'LBL_COMMENTS',
    'get_subpanel_data' => 'comments',
    'top_buttons' => array(
        0 => array('widget_class' => 'SubPanelTopButtonQuickCreate',),
        1 => array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect',),
    ),
);

$layout_defs["DIGIT_TASK"]["subpanel_setup"]['assigned_task_assigned'] = array(
    'order' => 100,
    'module' => 'DIGIT_ASSIGNED_USER',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'date_entered',
    'title_key' => 'LBL_ASSIGNED_TASK_ASSIGNED',
    'get_subpanel_data' => 'assigned_task_assigned',
    'top_buttons' => array(
        0 => array('widget_class' => 'SubPanelTopButtonQuickCreate',),
        1 => array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect',),
    ),
);
$layout_defs["DIGIT_TASK"]["subpanel_setup"]['assigned_task_info'] = array(
    'order' => 100,
    'module' => 'DIGIT_ASSIGNED_USER',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'date_entered',
    'title_key' => 'LBL_ASSIGNED_TASK_INFO',
    'get_subpanel_data' => 'assigned_task_info',
    'top_buttons' => array(
        0 => array('widget_class' => 'SubPanelTopButtonQuickCreate',),
        1 => array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect',),
    ),
);
$layout_defs["DIGIT_TASK"]["subpanel_setup"]['assigned_task_free_form'] = array(
    'order' => 100,
    'module' => 'DIGIT_ASSIGNED_USER',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'date_entered',
    'title_key' => 'LBL_ASSIGNED_TASK_FREE_FORM',
    'get_subpanel_data' => 'assigned_task_free_form',
    'top_buttons' => array(
        0 => array('widget_class' => 'SubPanelTopButtonQuickCreate',),
        1 => array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect',),
    ),
);

$layout_defs["DIGIT_TASK"]["subpanel_setup"]['digit_task'] = array(
    'order' => 90,
    'sort_order' => 'asc',
    'sort_by' => 'name',
    'module' => 'DIGIT_TASK',
    'subpanel_name' => 'default',
    'get_subpanel_data' => 'members',
//    'add_subpanel_data' => 'member_id',
    'title_key' => 'LBL_MEMBER_ORG_SUBPANEL_TITLE',
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopButtonQuickCreate'),
        array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect')
    ),
);