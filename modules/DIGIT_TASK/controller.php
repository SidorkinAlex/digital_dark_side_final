<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17.10.20
 * Time: 23:29
 */
require_once 'custom/include/MVC/Controller/SugarController.php';
class DIGIT_TASKController extends CustomSugarController
{
    public function action_timeLine()
    {
        $this->view = 'list';
    }
}