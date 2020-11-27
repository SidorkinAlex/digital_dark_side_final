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

$dictionary['DIGIT_ASSIGNED_USER'] = array(
    'table' => 'digit_assigned_user',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'user_id_c' =>
            array(
                'required' => false,
                'name' => 'user_id_c',
                'vname' => 'LBL_EXECUTOR_NAME_USER_ID',
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
        'executor_name' =>
            array(
                'required' => false,
                'source' => 'non-db',
                'name' => 'executor_name',
                'vname' => 'LBL_EXECUTOR_NAME',
                'type' => 'relate',
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
                'id_name' => 'user_id_c',
                'ext2' => 'Users',
                'module' => 'Users',
                'rname' => 'name',
                'quicksearch' => 'enabled',
                'studio' => 'visible',
            ),
        'digit_task_id' =>
            array(
                'required' => false,
                'name' => 'digit_task_id',
                'vname' => 'LBL_DIGIT_TASK_ID',
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
        'digit_task_name' =>
            array(
                'required' => false,
                'source' => 'non-db',
                'name' => 'digit_task_name',
                'vname' => 'LBL_DIGIT_TASK_NAME',
                'type' => 'relate',
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

                'id_name' => 'digit_task_id',
                'ext2' => 'DIGIT_TASK',
                'module' => 'DIGIT_TASK',
                'rname' => 'name',
                'quicksearch' => 'enabled',
                'studio' => 'visible',
            ),
        'type' =>
            array(
                'required' => false,
                'name' => 'type',
                'vname' => 'LBL_TYPE',
                'type' => 'enum',
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
                'len' => 100,
                'size' => '20',
                'options' => 'digit_assigned_user_type_list',
                'studio' => 'visible',
                'dependency' => false,
            ),
        'typical_responses' =>
            array(
                'required' => false,
                'name' => 'typical_responses',
                'vname' => 'LBL_TYPICAL_RESPONSES',
                'type' => 'enum',
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
                'len' => 100,
                'size' => '20',
                'options' => 'typical_responses_list',
                'studio' => 'visible',
                'dependency' => false,
            ),
        'description' =>
            array(
                'name' => 'description',
                'vname' => 'LBL_DESCRIPTION',
                'type' => 'text',
                'comment' => 'Full text of the note',
                'rows' => '6',
                'cols' => '80',
                'required' => false,
                'massupdate' => 0,
                'no_default' => false,
                'comments' => 'Full text of the note',
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
            ),
    ),
    'relationships' => array(
        'assigned_task_assigned' =>
            array(
                'lhs_module' => 'DIGIT_TASK',
                'lhs_table' => 'digit_task',
                'lhs_key' => 'id',
                'rhs_module' => 'DIGIT_ASSIGNED_USER',
                'rhs_table' => 'digit_assigned_user',
                'rhs_key' => 'digit_task_id',
                'relationship_type' => 'one-to-many',
                'relationship_role_column' => 'type',
                'relationship_role_column_value' => 'assigned',
            ),
        'assigned_task_info' =>
            array(
                'lhs_module' => 'DIGIT_TASK',
                'lhs_table' => 'digit_task',
                'lhs_key' => 'id',
                'rhs_module' => 'DIGIT_ASSIGNED_USER',
                'rhs_table' => 'digit_assigned_user',
                'rhs_key' => 'digit_task_id',
                'relationship_type' => 'one-to-many',
                'relationship_role_column' => 'type',
                'relationship_role_column_value' => 'info',
            ),
        'assigned_task_free_form' =>
            array(
                'lhs_module' => 'DIGIT_TASK',
                'lhs_table' => 'digit_task',
                'lhs_key' => 'id',
                'rhs_module' => 'DIGIT_ASSIGNED_USER',
                'rhs_table' => 'digit_assigned_user',
                'rhs_key' => 'digit_task_id',
                'relationship_type' => 'one-to-many',
                'relationship_role_column' => 'type',
                'relationship_role_column_value' => 'free_form',
            ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('DIGIT_ASSIGNED_USER', 'DIGIT_ASSIGNED_USER', array('basic', 'assignable', 'security_groups'));