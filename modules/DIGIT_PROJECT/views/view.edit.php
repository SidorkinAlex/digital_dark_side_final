<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.10.20
 * Time: 22:05
 */
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


class DIGIT_PROJECTViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        //указываем ссылку на tpl
        // if (file_exists('modules/DIGIT_PROJECT/tpls/EditView.tpl')) {
        //     $tpl = 'modules/DIGIT_PROJECT/tpls/EditView.tpl';
        // }

        $metadataFile = $this->getMetaDataFile();
        $this->ev = $this->getEditView();
        $this->ev->ss =& $this->ss;
        //Подключаем кастомный шаблон если он существует
        if (!empty($tpl)) {
            $this->ev->setup($this->module, $this->bean, $metadataFile, $tpl);
        } else {
            //если шаблона нет, то загружаем страндартный
            $this->ev->setup($this->module, $this->bean, $metadataFile);
        }
    }

    public function display()
    {
        global $sugar_config, $db;

        parent::display();
    }

    public function afterEVprocess()
    {


    }
}


