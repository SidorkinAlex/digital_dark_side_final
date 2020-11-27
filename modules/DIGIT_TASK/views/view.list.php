<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17.10.20
 * Time: 23:17
 */
require_once 'custom/include/MVC/View/views/view.list.php';

class DIGIT_TASKViewList extends CustomViewList
{
    public function beforLVdisplay()
    {
        global $app_list_strings;
        parent::beforLVdisplay();
        if ($_REQUEST['action'] == 'timeLine') {
            $this->lv->setup($this->seed, 'modules/DIGIT_TASK/tpls/ListView.tpl', $this->where, $this->params, 0, 1000);
            $this->do_setup = true;

            $timeLine=$this->timeline();
            $this->lv->ss->assign("timeLine", $timeLine);
        }
    }

    public function timeline()
    {
        global $timedate;
        global $sugr_config;
        $date= date($timedate->get_db_date_format());
        $sundayDate=date($timedate->get_db_date_format(), strtotime('sunday this week', strtotime($date)));
        $sundayTimeStamp=strtotime($sundayDate);
        $mondayDate=date($timedate->get_db_date_format(), strtotime('monday this week', strtotime($date)));
        $mondayTimeStamp=strtotime($mondayDate);
        $i=0;
        $ss = new Sugar_Smarty();
        foreach ($this->lv->data['data'] as $k => $v) {
            if (empty($v['DATE_START']) || $mondayTimeStamp > strtotime($v['DATE_START'])) {
                $data[$i]['start'] = date('d', $mondayTimeStamp);
            } else {
                $data[$i]['start'] = (date('d',strtotime( $v['DATE_START'])) . '.' . ((int)((int)((date('H', strtotime( $v['DATE_START'])))) / 24)));
            }
            if (empty($v['DATE_STOP']) || $sundayTimeStamp < strtotime($v['DATE_STOP'])) {
                $data[$i]['end'] = date('d', $sundayTimeStamp);
            } else {
                $data[$i]['end'] = (date('d', strtotime($v['DATE_STOP'])) . '.' . ((int)(date('H', strtotime($v['DATE_STOP'])) / 24 * 10)));
            }
            $data[$i]['title'] = "{$v['NAME']} ответственный {$v['ASSIGNED_USER_NAME']}";
            //$data[$i]['title']="1";
            $rowEndTime = strtotime($v['DATE_STOP']) - time();
            if ($rowEndTime < 86400) {
                $data[$i]['class'] = "red";
            } elseif ($rowEndTime > 86400 && $rowEndTime < (86400 * 3)) {
                $data[$i]['class'] = "yelow";
            } else {
                $data[$i]['class'] = "blue";
            }
            $data[$i]['class'] .= " iframe ";
            $data[$i]['id'] = $v['ID'];
            $data[$i]['url'] = $sugr_config['site_url'] . '/index.php?module=DIGIT_TASK&action=DetailView&record=' . $v['ID'];
            $data[$i]['date_stop'] = $v['date_stop'];

            $i++;
        }

        $statday=$mondayTimeStamp;
        $arrday=array('Понедельник', "Вторник", "Среда", "Четверг","Пятница","Суббота","Воскресенье");
        for ($i=0;$i<7;$i++){
            $headings[] =array(
                'start' =>date('d', $statday),
                'end' => date('d', ($statday + 86400 )),
                'title' => $arrday[$i],
            );
            $statday = $statday + 86400;
        }

        $dateEnd = (int)(date('d', $sundayTimeStamp)) + 1;
        $ss->assign('data', $data);
        $ss->assign('dateStart', date('d', $mondayTimeStamp));
        $ss->assign('dateEnd', $dateEnd);
        $ss->assign('headingsData', $headings);
        //print_array($data);
        return $ss->fetch('modules/DIGIT_TASK/tpls/TimeLine.tpl');
    }

}