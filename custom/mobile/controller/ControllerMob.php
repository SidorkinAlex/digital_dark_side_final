<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22.10.20
 * Time: 8:56
 */
include_once COREDIR.'custom/mobile/views/indexView.php';
class ControllerMob
{
    public $request = array();
    public $view;
    public $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->initView();

    }

    public function index_action()
    {
       $this->view->show();
    }
    public function index_DetailView()
    {
        //просмотр по умолчанию
    }
    public function index_EditView()
    {
        //редактирование по умолчанию
    }

    public function initView(){
        if(file_exists(COREDIR.'custom/mobile/views/'.$this->router->request['action'].'View'.$this->router->request['module'].'.php')){
            include_once(COREDIR.'custom/mobile/views/'.$this->router->request['action'].'View'.$this->router->request['module'].'.php');
            $className = $this->router->request['action'].'View'.$this->router->request['module'];
            $this->view = new $className($this->router);
        } elseif(file_exists(COREDIR.'custom/mobile/views/'.$this->router->request['action'].'View.php')) {
            include_once(COREDIR.'custom/mobile/views/'.$this->router->request['action'].'View.php');
            $className = $this->router->request['action'].'View';
            $this->view = new $className($this->router);
        } else {
            include_once(COREDIR.'custom/mobile/views/View.php');
            $this->view = new View($this->router);
        }
    }
}