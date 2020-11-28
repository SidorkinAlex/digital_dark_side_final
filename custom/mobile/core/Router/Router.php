<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.10.20
 * Time: 9:38
 */

 Class Router {
     public $module;
     public $action;
     public $request;
        public function __construct()
        {
            $this->module =$_REQUEST['module'];
            $this->action =$_REQUEST['action'];
            $this->request =$_REQUEST;
        }

        public function run(){
            if (!empty($this->module) && !empty($this->action)){
                if(file_exists('custom/mobile/controller/'.$this->module.'ControllerMob.php')){
                    include_once 'custom/mobile/controller/'.$this->module.'ControllerMob.php';
                    $className=$this->module.'Controller';
                    $controller= new $className();
//                    if(!method_exists($controller,$this->action.'_action')){
                        $action =$this->action.'_action';
                        $controller->{$action}();
//                    } else {
//                        echo "ошибка роутинга";
//                    }

                } else {
                    include_once COREDIR.'custom/mobile/controller/ControllerMob.php';
                    $controller= new ControllerMob($this);
                    $action =$this->action.'_action';
                    $controller->{$action}();
                }
            }
        }
 }