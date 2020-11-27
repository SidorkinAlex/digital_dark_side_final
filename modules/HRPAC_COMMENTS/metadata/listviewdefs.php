<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

//  ������

$module_name = 'HRPAC_COMMENTS';
$listViewDefs [$module_name] = array(
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '9%',
//      'label' => 'LBL_ASSIGNED_TO_NAME',
        'label' => 'LBL_CREATED',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true,
    ),
    'TEXT' => array(
        'type' => 'text',
        'studio' => 'visible',
        'label' => 'LBL_TEXT',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
    ),
/*
    'NAME' => array(
        'width' => '32',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true
    ),
*/
);

//                                                                              ----
