<?php
/**
 * Created by PhpStorm.
 * User: SeedTeam
 * Date: 17.01.2020
 * Time: 10:19
 */
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/MVC/View/SugarView.php');
require_once('include/MVC/Controller/SugarController.php');

class CustomSugarController extends SugarController {

    protected function post_save()
    {
        if(empty($_REQUEST['jsqon_return'])) {
            $module = (!empty($this->return_module) ? $this->return_module : $this->module);
            $action = (!empty($this->return_action) ? $this->return_action : 'DetailView');
            $id = (!empty($this->return_id) ? $this->return_id : $this->bean->id);

            $url = "index.php?module=" . $module . "&action=" . $action . "&record=" . $id;
            $this->set_redirect($url);
        }
        else {
            $id = (!empty($this->return_id) ? $this->return_id : $this->bean->id);
            $return=json_encode(array('id' => $id));
            echo $return;
        }
    }
    public function action_get_subpanel_json_data()
    {
        require_once 'custom/include/CustomClass/Custom_data_modull.php';
        global $beanList, $current_user, $config_override;
        if (empty($_REQUEST['module'])) {
            die(json_encode(['error'=>"'module' was not defined"]));
        }

        if (empty($_REQUEST['record'])) {
            die(json_encode(['error'=>"'record' was not defined"]));
        }

        if (!isset($beanList[$_REQUEST['module']])) {
            die(json_encode(['error'=>"'" . $_REQUEST['module'] . "' is not defined in \$beanList"]));
        }

        if (!isset($_REQUEST['subpanel'])) {
            sugar_die(json_encode(['error'=>'Subpanel was not defined']));
        }

        $subpanel = $_REQUEST['subpanel'];
        $record = $_REQUEST['record'];
        $module = $_REQUEST['module'];

        $collection = array();



        include 'include/SubPanel/SubPanel.php';
        $layout_def_key = '';
        if (!empty($_REQUEST['layout_def_key'])) {
            $layout_def_key = $_REQUEST['layout_def_key'];
        }
        require_once 'include/SubPanel/SubPanelDefinitions.php';
// retrieve the definitions for all the available subpanels for this module from the subpanel
        $bean = BeanFactory::getBean($module);
        $spd = new SubPanelDefinitions($bean);
        $aSubPanelObject = $spd->load_subpanel($subpanel, false, false, '', $collection);

        $seedobj=BeanFactory::getBean($module,$record);
        $seedobj->load_relationship($aSubPanelObject->_instance_properties['get_subpanel_data']);
        $arr=$seedobj->{$aSubPanelObject->_instance_properties['get_subpanel_data']}->get();
        //сортировка
        if(!empty($_REQUEST['sort_by'])){
            $seedrelateobj=BeanFactory::getBean($aSubPanelObject->_instance_properties['module']);
            //проверяем наличие поля по которому пробуем отсортированть
            if(array_key_exists($_REQUEST['sort_by'], $seedrelateobj->field_name_map)) {
                global $db;
                if(!empty($_REQUEST['type_sort']) && $_REQUEST['type_sort'] == 'DESC') {
                    $sort_type="DESC";
                } else {
                    $sort_type="ASC";
                }
                $ID = "'" . implode("','", $arr) . "'";
                $sql = "
                SELECT `id`
                FROM `{$seedrelateobj->table_name}`
                WHERE
                `id` IN ({$ID})
                ORDER BY {$_REQUEST['sort_by']} {$sort_type}
            ";
                $result=$db->query($sql,1);
                $arr=[];
                while ($row = $db->fetchByAssoc($result)) {
                    $arr[]=$row['id'];
                }
            }
        }
        $list=[];
        if (ACLController::checkAccess($aSubPanelObject->_instance_properties['module'], 'list', true)) {

            $mod=Custom_data_modull::getlanguage($aSubPanelObject->_instance_properties['module']);
            foreach ($arr as $v) {
                if ($aSubPanelObject->_instance_properties['module'] == 'HRPAC_COMMENTS') {
                    //Логика для модуля комментарии
                    $temp = Custom_data_modull::set($aSubPanelObject->_instance_properties['module'], $v);
                    //Проверяем наличие чекбокса "Виден только рекрутерам"
                    if (can_see_comment($temp['to_recruits']['value'])) {
                        //Если есть разрешение, то показываем коммент
                        $list[] = $temp;
                    }
                } else {
                    //стандартная логика
                    $list[]=Custom_data_modull::set($aSubPanelObject->_instance_properties['module'], $v);
                }
            }
            $return=[
                "MOD"=>$mod,
                "List"=>$list,
            ];
        } else {
            $return=['error'=>'you do not have rights'];
        }
        $return=json_encode($return);
        echo $return;
    }

}