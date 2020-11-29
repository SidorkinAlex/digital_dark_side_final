<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.11.20
 * Time: 4:21
 */
include_once COREDIR . 'custom/mobile/views/View.php';

class editViewDIGIT_TASK extends View
{
    public function process()
    {
        $data_edit['record']= $this->router->request['record'];
        $this->contentBox->assign('data_edit',$data_edit);
    }
}