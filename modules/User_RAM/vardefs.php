<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

$dictionary['User_RAM'] = array(
    'table' => 'user_ram',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'user_id_c' =>
            array (
                'required' => false,
                'name' => 'user_id_c',
                'vname' => 'LBL_USER_RELATE_USER_ID',
                'type' => 'id',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'inline_edit' => true,
                'reportable' => false,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'len' => 36,
                'size' => '20',
            ),
        'rel_name_hrpac_vacancy' =>
            array (
                'required' => false,
                'name' => 'rel_name_hrpac_vacancy',
                'vname' => 'LBL_REL_NAME_HRPAC_VACANCY',
                'source' => 'non-db',
                'type' => 'varchar',
                'massupdate' => 0,
                'default' => 'HRPAC_VACANCY',
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => 0,
                'audited' => false,
                'inline_edit' => true,
                'reportable' => false,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                //'len' => 36,
                'size' => '20',
                'value' => 'HRPAC_VACANCY'
            ),
        'user_relate' =>
            array (
                'required' => false,
                'source' => 'non-db',
                'name' => 'user_relate',
                'vname' => 'LBL_USER_RELATE',
                'type' => 'relate',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => 'Этот пользователь связывается  с выбранным модулем и выбранным полем',
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
                'id_name' => 'user_id_c',
                'ext2' => 'Users',
                'module' => 'Users',
                'rname' => 'name',
                'quicksearch' => 'enabled',
                'studio' => 'visible',
            ),
        'field_name' =>
            array (
                'required' => false,
                'name' => 'field_name',
                'vname' => 'LBL_FIELD_NAME',
                'type' => 'varchar',
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
                'len' => '255',
                'size' => '20',
            ),
        'parent_type' =>
            array(
                'name' => 'parent_type',
                'vname' => 'LBL_PARENT_TYPE',
                'type' => 'parent_type',
                'dbType' => 'varchar',
                'required' => false,
                'group' => 'parent_name',
                'options' => 'parent_type_ram',
                'len' => 255,
                'comment' => 'The Sugar object to which the call is related'
            ),

        'parent_name' =>
            array(
                'name' => 'parent_name',
                'parent_type' => 'record_type_display',
                'type_name' => 'rel_name_hrpac_vacancy',
                'id_name' => 'parent_id',
                'vname' => 'LBL_LIST_RELATED_TO',
                'type' => 'parent',
                'group' => 'parent_name',
                'source' => 'non-db',
                'options' => 'parent_type_display',
            ),
        'parent_id' =>
            array(
                'name' => 'parent_id',
                'vname' => 'LBL_LIST_RELATED_TO_ID',
                'type' => 'id',
                'group' => 'parent_name',
                'reportable' => false,
                'comment' => 'The ID of the parent Sugar object identified by parent_type'
            ),
        'additional_managers' =>
            array(
                'name' => 'vacancy',
                'type' => 'link',
                'relationship' => 'additional_managers',
                'source' => 'non-db',
                'link_type' => 'one',
                'vname' => 'LBL_HRPAC_VACANCY',
            ),
        'spectators' =>
            array(
                'name' => 'vacancy',
                'type' => 'link',
                'relationship' => 'spectator',
                'source' => 'non-db',
                'link_type' => 'one',
                'vname' => 'LBL_HRPAC_VACANCY',
            ),
        'additional_assigned' =>
            array(
                'name' => 'vacancy',
                'type' => 'link',
                'relationship' => 'additional_assigned',
                'source' => 'non-db',
                'link_type' => 'one',
                'vname' => 'LBL_HRPAC_VACANCY',
            ),
    ),
    'relationships' => array(
        'additional_managers' => array(
            'lhs_module' => 'HRPAC_VACANCY',
            'lhs_table' => 'hrpac_vacancy',
            'lhs_key' => 'id',
            'rhs_module' => 'User_RAM',
            'rhs_table' => 'user_ram',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'additional_managers',
        ),
        'spectators' => array(
            'lhs_module' => 'HRPAC_VACANCY',
            'lhs_table' => 'hrpac_vacancy',
            'lhs_key' => 'id',
            'rhs_module' => 'User_RAM',
            'rhs_table' => 'user_ram',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'spectators',
        ),
        'additional_assigned' => array(
            'lhs_module' => 'HRPAC_VACANCY',
            'lhs_table' => 'hrpac_vacancy',
            'lhs_key' => 'id',
            'rhs_module' => 'User_RAM',
            'rhs_table' => 'user_ram',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column' => 'parent_type',
            'relationship_role_column_value' => 'additional_assigned',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('User_RAM', 'User_RAM', array('basic', 'assignable', 'security_groups'));