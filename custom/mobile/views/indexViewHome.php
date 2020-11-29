<?php
/**
 * Created by PhpStorm.
 * User: seedteam
 * Date: 01.11.20
 * Time: 14:18
 */
include_once COREDIR . 'custom/mobile/views/indexView.php';
include_once COREDIR . 'custom/include/CustomClass/MobileDashbord.php';
class indexViewHome extends indexView{
    public function process()
    {
        $data_info=MobileDashbord::digit_assigned_user_info();
        $data_task=MobileDashbord::digit_task_show();
        $this->contentBox->assign('data_info_end',$data_info);
        $this->contentBox->assign('data_task',$data_task);

    }
}