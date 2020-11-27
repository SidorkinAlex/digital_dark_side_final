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

$dictionary['DIGIT_PROJECT'] = array(
    'table' => 'digit_project',
    'audited' => true,
    'inline_edit' => true,
    'duplicate_merge' => true,
    'fields' => array (
        'date_start' => array(
            'name' => 'date_start',
            'vname' => 'LBL_DATE_START',
            'massupdate' => false,
            'type' => 'datetime',
            'comment' => ''
        ),
        'date_stop' => array(
            'name' => 'date_stop',
            'vname' => 'LBL_DATE_STOP',
            'massupdate' => false,
            'type' => 'datetime',
            'comment' => ''
        ),
        'date_attention' => array(
            'name' => 'date_attention',
            'vname' => 'LBL_DATE_ATTENTION',
            'massupdate' => false,
            'type' => 'datetime',
            'comment' => ''
        ),
        'status' => array (
            'required' => false,
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => '',
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
            'options' => 'digit_project_status_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'priority' => array (
            'required' => false,
            'name' => 'priority',
            'vname' => 'LBL_PRIORITY',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => '',
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
            'options' => 'digit_project_priority_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'date_stop_last_task' => array(
            'name' => 'date_stop_last_task',
            'vname' => 'LBL_DATE_STOP_LAST_TASK',
            'massupdate' => false,
            'type' => 'datetime',
            'comment' => '',
            'source' => 'non-db',
            'inline_edit' => false,
        ),
        'project_starter_id' => array (
            'required' => false,
            'name' => 'project_starter_id',
            'vname' => 'LBL_PROJECT_STARTER_ID',
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
        'project_starter_name' => array (
            'required' => false,
            'source' => 'non-db',
            'name' => 'project_starter_name',
            'vname' => 'LBL_PROJECT_STARTER_NAME',
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
            'id_name' => 'project_starter_id',
            'ext2' => 'Users',
            'module' => 'Users',
            'rname' => 'name',
            'quicksearch' => 'enabled',
            'studio' => 'visible',
        ),
    ),
    'relationships' => array (
),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('DIGIT_PROJECT', 'DIGIT_PROJECT', array('basic','assignable','security_groups'));