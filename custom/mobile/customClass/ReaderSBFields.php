<?php

class ReaderDBFields
{
    public $seed;

    public function __consrtact(SugarBean $objName)
    {
        $this->seed = $objName;
    }

    static function getObjdData(SugarBean $objName):array
    {
        $retutn = [];
        foreach ($objName->field_name_map as $fieldName => $fieldArr) {
            if($fieldArr['type'] != 'link')
            $retutn[$fieldName] = $objName->$fieldName;
        }
        return $retutn;
    }

}