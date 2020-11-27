<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'HRPAC_COMMENTS';
$subpanel_layout['list_fields'] = [
    'date_entered' => [
        'type' => 'datetime',
        'vname' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'sortable' => false,
    ],
    'assigned_user_name' => [
        'link' => true,
        'type' => 'relate',
        'vname' => 'LBL_CREATED',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module' => 'Users',
        'target_record_key' => 'assigned_user_id',
        'sortable' => false,
    ],
    'text' => [
        'type' => 'text',
        'studio' => 'visible',
        'vname' => 'LBL_TEXT',
        'width' => '10%',
        'default' => true,
        'sortable' => false,
    ],

    'button_edit' => ['default' => true, 'sortable' => false, 'studio' => 'visible', 'type' => 'text', 'vname' => 'LBL_BUTTON_EDIT', 'width' => '10%', ],
    'button_drop' => ['default' => true, 'sortable' => false, 'studio' => 'visible', 'type' => 'text', 'vname' => 'LBL_BUTTON_DROP', 'width' => '10%', ],

];