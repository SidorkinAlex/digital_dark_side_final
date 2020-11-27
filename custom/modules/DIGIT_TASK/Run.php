<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.11.20
 * Time: 14:21
 */
$seed = BeanFactory::newBean('DIGIT_TASK');
$sql=$seed ->create_new_list_query('','',['id','name']);
global $db;
$result=$db->query($sql);
$arr=[];
while ($row = $db->fetchByAssoc($result)) {
$arr[]=$row;
}
print_array($arr);