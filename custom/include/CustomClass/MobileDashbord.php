<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.11.20
 * Time: 22:15
 */
class MobileDashbord{
    static public function digit_assigned_user_info() {
        $ss=new Sugar_Smarty();
        global $current_user;
        global $db;
        global $timedate;
        $sql="
        SELECT `dt`.name ,`dt`.`date_plan`, `dau`.id as `digit_assigned_user_id`, `dt`.id as `digit_task_id`
        FROM `digit_assigned_user` `dau`
        Inner JOIN `digit_task` `dt` on `dau`.`digit_task_id`=`dt`.`id` AND `dt`.`deleted`='0' AND `dt`.`digit_tasks_class`='info'
        WHERE 
        `dau`.`deleted`='0'
        AND `dau`.`user_id_c` = '{$current_user->id}'
        AND ( `dau`.typical_responses ='' OR `dau`.typical_responses IS NULL )
        ORDER BY `dt`.date_plan ASC
        ";
        //print_array($sql);
        $data_info=[];
        $result=$db->query($sql,1);
        while ($row = $db->fetchByAssoc($result)) {
            if(!empty($row['date_plan'])){

                $timestamp=strtotime($row['date_plan']);
                $time_now=strtotime(gmdate($timedate->get_db_date_time_format()));
                $interval=$timestamp - $time_now;
                if ($interval<0){
                    $row['date_lost']['value']=0;
                    $row['date_lost']['class']='badge-danger';
                } elseif ($interval<3600){
                    $row['date_lost']['value']=((int)($interval/60)) . ' минут';
                    $row['date_lost']['class']='badge-danger';
                }
                elseif ($interval<86400) {
                    $row['date_lost']['value']=(int)($interval/(60*60)) . ' час';
                    $row['date_lost']['class']='badge-warning';
                } elseif ($interval<604800) {
                    $row['date_lost']['value']=(int)($interval/(60*60*24)) . ' дней';
                    $row['date_lost']['class']='badge-success';
                } else {
                    $row['date_lost']['value']=(int)($interval/(60*60*24*7)) . ' недель';
                    $row['date_lost']['class']='badge-info';
                }
            }
            $data_info[]=$row;
        }
        $ss->assign('data_info',$data_info);
        return $ss->fetch(COREDIR.'custom/include/custom_tpl/digit_assigned_user_info.tpl');
    }

    static public function digit_task_show() {
        $ss=new Sugar_Smarty();
        global $current_user;
        global $db;
        global $timedate;
        $sql="
        SELECT `dt`.name ,`dt`.`date_plan`, `dt`.id as `digit_assigned_user_id`, `dt`.id as `digit_task_id`, `dt`.`priority`
        FROM `digit_task` `dt`
        Inner JOIN `digit_assigned_user` `dau` on `dau`.`digit_task_id`=`dt`.`id` AND `dt`.`deleted`='0'
        WHERE 
        `dau`.`deleted`='0'
        AND `dau`.`user_id_c` = '{$current_user->id}'
        AND ( `dau`.typical_responses ='' OR `dau`.typical_responses IS NULL )
        ORDER BY `dt`.priority ASC ,`dt`.`date_plan` ASC
        ";
        //print_array($sql);
        $data_info=[];
        $result=$db->query($sql,1);
        while ($row = $db->fetchByAssoc($result)) {
            if(!empty($row['date_plan'])){

                $timestamp=strtotime($row['date_plan']);
                $time_now=strtotime(gmdate($timedate->get_db_date_time_format()));
                $interval=$timestamp - $time_now;
                if ($interval<0){
                    $row['date_lost']['value']=0;
                    $row['date_lost']['class']='badge-danger';
                } elseif ($interval<3600){
                    $row['date_lost']['value']=((int)($interval/60)) . ' минут';
                    $row['date_lost']['class']='badge-danger';
                }
                elseif ($interval<86400) {
                    $row['date_lost']['value']=(int)($interval/(60*60)) . ' час';
                    $row['date_lost']['class']='badge-warning';
                } elseif ($interval<604800) {
                    $row['date_lost']['value']=(int)($interval/(60*60*24)) . ' дней';
                    $row['date_lost']['class']='badge-success';
                } else {
                    $row['date_lost']['value']=(int)($interval/(60*60*24*7)) . ' недель';
                    $row['date_lost']['class']='badge-info';
                }
            }
            $data_info[]=$row;
        }
        $ss->assign('digit_task_list',$data_info);
        return $ss->fetch(COREDIR.'custom/include/custom_tpl/digit_task.tpl');
    }
}