<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22.10.20
 * Time: 10:11
 */
include_once COREDIR . 'custom/mobile/views/View.php';

class indexView extends View
{
    public function process()
    {
        parent::process(); // TODO: Change the autogenerated stub
        $seedObj =BeanFactory::newBean($this->router->module);
        $fields=array_keys($this->mobileMetaData->metaData);

        $qury=$seedObj->create_new_list_query('','',$fields);
        $lists= $seedObj->process_list_query($qury,0);
        //print_array($lists,1);
        foreach ($lists['list'] as $row => $row_data){
            foreach ($fields as $fieldsName){
                $data[$row_data->id][$fieldsName]=$row_data->$fieldsName;
            }
        }
        //print_array($data);
        $this->contentBox->assign('data',$data);

    }

}