<?php
/**
 * Created by PhpStorm.
 * User: SeedTeam
 * Date: 17.03.2020
 * Time: 10:37
 */
require_once('modules/Configurator/Configurator.php');

class ViewJson_list extends SugarView
{
    public function __construct()
    {
        parent::__construct();
        $_REQUEST['to_pdf']='true';
        $_POST['to_pdf']='true';
        $_GET['to_pdf']='true';
    }

    public function display()
    {
        $url_from_var = get_url_var($_SERVER['HTTP_REFERER']);
        if (!empty($url_from_var['action']) && $url_from_var['action'] = !$this->type) {
            $seed_obj = BeanFactory::newBean($_REQUEST['module']);
            if (!empty($_REQUEST['fields']) && is_array($_REQUEST['fields'])) {
                $fields = $_REQUEST['fields'];
            } else {
                $fields = '*';
            }
            $users = table_sql_list($seed_obj->table_name, $fields);
            echo json_encode($users);
        }
    }
}