<?php
/**
 * Created by PhpStorm.
 * User: SeedTeam
 * Date: 19.03.2020
 * Time: 11:16
 * Этот файл кастомной локики параметров полей подключается в include/EditView/EditView2.php
 */
require_once 'custom/modules/Currencies/CustomCurrency.php';

    foreach ($this->fieldDefs as $k=>$v) {
        if(isset($v['type'])){
            switch ($v['type']) {
                case 'currency_id':
                    $this->fieldDefs[$k]['symbol']=CustomCurrency::get_currensy_symbol($this->focus->{$v['name']});
                    break;
                case 1:
                    break;
                case 2:
                    break;
                default:
                    break;
            }
        }
    }