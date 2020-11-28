<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.10.20
 * Time: 9:32
 */

include 'custom/mobile/core/includCore.php';
define("COREDIR",     $_SERVER['DOCUMENT_ROOT'].'/');
$router=new Router();
$router->run();