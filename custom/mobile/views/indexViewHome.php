<?php
/**
 * Created by PhpStorm.
 * User: seedteam
 * Date: 01.11.20
 * Time: 14:18
 */
include_once COREDIR . 'custom/mobile/views/indexView.php';
class indexViewHome extends indexView{
    public function process()
    {
global $current_user;
        global $db;
        global $timedate;
        $fields=array('id','digit_task_info_name','user_id_c');
        $sql="
        SELECT `dt`.name ,`dt`.`date_plan`
        FROM `digit_assigned_user` `dau`
        Inner JOIN `digit_task` `dt` on `dau`.`digit_task_id`=`dt`.`id` AND `dt`.`deleted`='0' AND `dt`.`digit_tasks_class`='info'
        WHERE 
        `dau`.`deleted`='0'
        AND `dau`.`user_id_c` = '{$current_user->id}'
        AND ( `dau`.typical_responses ='' OR `dau`.typical_responses IS NULL )
        ORDER BY `dt`.date_plan DESC
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

        $this->contentBox->assign('data_info',$data_info);

    }
}