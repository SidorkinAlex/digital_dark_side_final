<?php

$dictionary['HRPAC_COMMENTS'] = array(
    'table' => 'hrpac_comments',
    'audited' => false,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'text' => array(
            'required' => true,
            'name' => 'text',
            'vname' => 'LBL_TEXT',
            'type' => 'text',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'studio' => 'visible',
            'rows' => '5',
            'cols' => '50',
        ),
        'to_recruits' => array(
            'required' => false,
            'name' => 'to_recruits',
            'vname' => 'LBL_TO_RECRUITS',
            'type' => 'bool',
            'massupdate' => 0,
            'default' => '0',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'inline_edit' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => '255',
            'size' => '20',
        ),

        'parent_type' => [
            'name' => 'parent_type',
            'vname' => 'LBL_PARENT_TYPE',
            'type' => 'parent_type',
            'dbType' => 'varchar',
            'required' => false,
            'group' => 'parent_name',
            'options' => 'hr_comments_rel_list',
            'len' => 255,
            'comment' => 'The Sugar object to which the call is related',
        ],

        'parent_name' => [
            'name' => 'parent_name',
            'parent_type' => 'record_type_display',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'vname' => 'LBL_LIST_RELATED_TO',
            'type' => 'parent',
            'group' => 'parent_name',
            'source' => 'non-db',
            'options' => 'hr_comments_rel_list',
        ],

        'parent_id' => [
            'name' => 'parent_id',
            'vname' => 'LBL_LIST_RELATED_TO_ID',
            'type' => 'id',
            'group' => 'parent_name',
            'reportable' => false,
            'comment' => 'The ID of the parent Sugar object identified by parent_type',
        ],

        'talantix_id' => [
            'name' => 'talantix_id',
            'vname' => 'LBL_TALANTIX_ID',
            'type' => 'varchar',
//          'massupdate' => 0,
//          'no_default' => false,
//          'comments' => '',
//          'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
//          'audited' => false,
//          'inline_edit' => false,
//          'reportable' => false,
//          'unified_search' => false,
//          'merge_filter' => 'disabled',
            'len' => 36,
            'size' => '20',
            'required' => false,
        ],


        'button_edit' => [
            'name' => 'button_edit',
            'vname' => 'LBL_BUTTON_EDIT',
            'source' => 'non-db',
            'type' => 'text',
            'audited' => false,
            'inline_edit' => false,
            'reportable' => false,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'studio' => 'visible',
        ],

        'button_drop' => [
            'name' => 'button_drop',
            'vname' => 'LBL_BUTTON_DROP',
            'source' => 'non-db',
            'type' => 'text',
            'audited' => false,
            'inline_edit' => false,
            'reportable' => false,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'studio' => 'visible',
        ],

    ),
    'indices' => array(
        array(
            'name' => 'parent_id',
            'type' => 'index',
            'fields' => array('parent_id'),
        ),
        array(
            'name' => 'parent_type',
            'type' => 'index',
            'fields' => array('parent_type'),
        ),
    ),
    'relationships' => [],
    'optimistic_locking' => true,
    'unified_search' => true,
);

if (!class_exists('VardefManager')){
    require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef('HRPAC_COMMENTS', 'HRPAC_COMMENTS', ['basic', 'assignable', 'security_groups', ]);
$dictionary['HRPAC_COMMENTS']['fields']['name']['required'                  ] =  false;
$dictionary['HRPAC_COMMENTS']['fields']['name']['inline_edit'               ] =  true;
$dictionary['HRPAC_COMMENTS']['fields']['name']['duplicate_merge'           ] = 'disabled';
$dictionary['HRPAC_COMMENTS']['fields']['name']['duplicate_merge_dom_value' ] = '0';
$dictionary['HRPAC_COMMENTS']['fields']['name']['merge_filter'              ] = 'disabled';
$dictionary['HRPAC_COMMENTS']['fields']['name']['unified_search'            ] =  false;
$dictionary['HRPAC_COMMENTS']['fields']['name']['importable'                ] = 'false';