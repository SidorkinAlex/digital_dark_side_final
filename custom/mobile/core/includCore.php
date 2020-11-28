<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.10.20
 * Time: 9:57
 */
$includeArr = include'custom/mobile/core/fileArr.php';
foreach ($includeArr as $file){
    include_once $file;
}