<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22.10.20
 * Time: 18:06
 */
include_once COREDIR . 'custom/mobile/customClass/ReaderSBFields.php';
class View {
    public $ss;
    public $sitebar;
    public $menubar;
    public $smartyVar = ['ss','sitebar','menubar','contentBox'];
    public $router;
    public $config;
    public $mobileMetaData;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->sitebar = new Sugar_Smarty();
        $this->menubar = new Sugar_Smarty();
        $this->contentBox = new Sugar_Smarty();
        $this->mobileMetaData = new MobileMetaData($this->router);
    }

    public function show()
    {
        $this->initSS();
        $this->defoultVar();
        $this->autoAssignSS();
        $this->process();
        $this->unionTemplate();
        $this->display();
    }

    public function initSS()
    {
        $this->ss = new Sugar_Smarty();
    }

    public function process(){


    }

    public function unionTemplate(){

        $this->sitebar=$this->sitebar->fetch(COREDIR.'custom/mobile/tpl/sitebar.tpl');
        $this->menubar=$this->menubar->fetch(COREDIR.'custom/mobile/tpl/menubar.tpl');

        $this->ss->assign('SITEBAR',$this->sitebar);
        $this->ss->assign('MENUBAR',$this->menubar);

    }

    public function display()
    {
        if(file_exists(COREDIR.'custom/mobile/tpl/'.$this->router->action.$this->router->module.'.tpl')){
            $contentBoxTpl = COREDIR.'custom/mobile/tpl/'.$this->router->action.$this->router->module.'.tpl';
        } elseif(file_exists(COREDIR.'custom/mobile/tpl/'.$this->router->action.'.tpl')) {
            $contentBoxTpl = COREDIR.'custom/mobile/tpl/'.$this->router->action.'.tpl';
        } else {
            $contentBoxTpl = COREDIR.'custom/mobile/tpl/error.tpl';
        }
        $this->contentBox=$this->contentBox->fetch($contentBoxTpl);

        $this->ss->assign('contentBox',$this->contentBox);

        echo $this->ss->fetch(COREDIR . 'custom/mobile/tpl/basetemplate.tpl');
    }
    public function autoAssignSS(){
        global $sugar_config;
        foreach ( $this->smartyVar as $var){
            $this->$var->assign('baseUrl',$sugar_config['site_url']);
            $this->$var->assign('config',$this->config);
        }

    }
    public function defoultVar(){
        global $current_user;
        $config['current_user'] = ReaderDBFields::getObjdData($current_user);
        $config['metaData'] = $this->mobileMetaData->metaData;
        $this->config=$config;
    }
}