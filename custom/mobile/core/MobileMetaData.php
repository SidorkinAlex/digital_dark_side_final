<?php
/**
 * Created by PhpStorm.
 * User: seedteam
 * Date: 31.10.20
 * Time: 23:10
 */
class MobileMetaData{
    public $metaData;
    public function __construct(Router $router)
    {
        $mobileMetaData = [];
        $metaFile= $this->getMetaDataFile($router->module,$router->action);
        include $metaFile;
        $this->metaData = $mobileMetaData[$router->module];
    }

    public function getMetaDataFile($module_name,$action)
    {
        $metadataFile = null;
        $foundViewDefs = false;
        $viewDef = $action.'mobileviewdefs.php';
        $coreMetaPath = COREDIR.'modules/' . $module_name . '/metadata/' . $viewDef;
        if (file_exists(COREDIR.'custom/' . $coreMetaPath)) {
            $metadataFile = COREDIR.'custom/' . $coreMetaPath;
            $foundViewDefs = true;
        } elseif (file_exists($coreMetaPath)){
            $metadataFile = $coreMetaPath;
        }
        return $metadataFile;
    }
    public static function getlanguage($module)
    {
        global $current_language;
        $module_strings = return_module_language($current_language, $module);
        return $module_strings;
    }
}