<?php
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
echo $ss->fetch('modules/DIGIT_TASK/tpls/TimeLine.tpl');