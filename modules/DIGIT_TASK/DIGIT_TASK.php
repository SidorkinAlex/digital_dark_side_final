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


class DIGIT_TASK extends Basic
{
    public $new_schema = true;
    public $module_dir = 'DIGIT_TASK';
    public $object_name = 'DIGIT_TASK';
    public $table_name = 'digit_task';
    public $importable = false;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;
	
    public function bean_implements($interface)
    {
        switch($interface)
        {
            case 'ACL':
                return true;
        }

        return false;
    }

    public function list_view_custom_sow(){
        global $timedate;
        global $db;
        global $sugr_config;
        require_once 'modules/DIGIT_TASK/DIGIT_TASK.php';
        $ss = new Sugar_Smarty();
        $seedDTask =new DIGIT_TASK();
        $date= gmdate($timedate->get_db_date_format());
        $sundayDate=date($timedate->get_db_date_format(), strtotime('sunday this week', strtotime($date)));
        $sundayTimeStamp=strtotime($sundayDate);
        $mondayDate=date($timedate->get_db_date_format(), strtotime('monday this week', strtotime($date)));
        $mondayTimeStamp=strtotime($mondayDate);
        $task = gmdate($timedate->get_db_date_format());
        $where = "(digit_task.`date_start`<'{$sundayDate}' AND digit_task.`status` NOT IN ('canceled', 'completed'))";
        $sql=$seedDTask->create_new_list_query('date_stop' , $where);
        $reult=$db->query($sql,1);
        $data=[];
        $i=0;
        while ($row = $db->fetchByAssoc($reult)) {
            if ($mondayTimeStamp < strtotime($row['date_start'])) {
                $data[$i]['start'] = date('d',$mondayTimeStamp);
            } else {
                $data[$i]['start'] =( date('d',$row['date_start']).'.' .( (int)((int)((date('H',$row['date_start'])))/24)));
            }
            if($sundayTimeStamp < strtotime($row['date_stop'])){
                $data[$i]['end'] = date('d',$sundayTimeStamp);
            } else {
                $data[$i]['end'] =( date('d',strtotime($row['date_stop'])).'.' .( (int)(date('H',strtotime($row['date_stop']))/24*10)));
            }
            $data[$i]['title']="{$row['name']} ответственный {$row['assigned_user_name']}";
            //$data[$i]['title']="1";
            $rowEndTime=strtotime($row['date_stop']) - time();
            if($rowEndTime < 86400) {
                $data[$i]['class'] = "red";
            } elseif ($rowEndTime > 86400 && $rowEndTime < (86400 * 3)){
                $data[$i]['class'] = "yelow";
            } else {
                $data[$i]['class'] = "blue";
            }
            $data[$i]['class'].=" iframe ";
            $data[$i]['id']=$row['id'];
            $data[$i]['url']=$sugr_config['site_url'].'/index.php?module=DIGIT_TASK&action=DetailView&record='.$row['id'];
            $data[$i]['date_stop']=$row['date_stop'];

            $i++;
        }

        $dateEnd=(int)(date('d',$sundayTimeStamp)) +1;
        $ss->assign('data',$data );
        $ss->assign('dateStart',date('d',$mondayTimeStamp) );
        $ss->assign('dateEnd',$dateEnd );
        print_array($data);
        return $ss->fetch('modules/DIGIT_TASK/tpls/TimeLine.tpl');
    }

}