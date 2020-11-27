<?php
/**
 * Created by PhpStorm.
 * User: seedteam
 * Date: 10.12.19
 * Time: 18:05
 */
require_once 'include/DetailView/DetailView2.php';
class Custom_data_modull{
    public function __construct()
    {

    }
    public static function set($module,$id) {
        $seedObj=BeanFactory::getBean( $module,$id);
        $vacansy= new DetailView2();
        $metadataFile=Custom_data_modull::getMetaDataFile($module);
        $tpl='modules/'.$module.'/tpl/DetailView.tpl';
        $vacansy->ss=new Sugar_Smarty();
        $vacansy->setup($module, $seedObj, $metadataFile, $tpl);
        $vacansy->process();
        //print_array($vacansy->fieldDefs);
        return $vacansy->fieldDefs;
    }

    /**
     * @return mixed
     */
    public static function getlanguage($module)
    {
        global $current_language;
        $module_strings = return_module_language($current_language, $module);
        return $module_strings;
    }

    public static function getMetaDataFile($module_name)
    {
        $metadataFile = null;
        $foundViewDefs = false;
        $viewDef = 'detailviewdefs.php';
        $coreMetaPath = 'modules/' . $module_name . '/metadata/' . $viewDef;
        if (file_exists('custom/' . $coreMetaPath)) {
            $metadataFile = 'custom/' . $coreMetaPath;
            $foundViewDefs = true;
        } else {
            if (file_exists('custom/modules/' . $module_name . '/metadata/metafiles.php')) {
                require_once('custom/modules/' . $module_name . '/metadata/metafiles.php');
                if (!empty($metafiles[$module_name][$viewDef])) {
                    $metadataFile = $metafiles[$module_name][$viewDef];
                    $foundViewDefs = true;
                }
            } elseif (file_exists('modules/' . $module_name . '/metadata/metafiles.php')) {
                require_once('modules/' . $module_name . '/metadata/metafiles.php');
                if (!empty($metafiles[$module_name][$viewDef])) {
                    $metadataFile = $metafiles[$module_name][$viewDef];
                    $foundViewDefs = true;
                }
            }
        }

        if (!$foundViewDefs && file_exists($coreMetaPath)) {
            $metadataFile = $coreMetaPath;
        }
        $GLOBALS['log']->debug("metadatafile=" . $metadataFile);

        return $metadataFile;
    }

    public static function getSugarField_class($field, $returnNullIfBase=false)
    {
        require_once 'include/SugarFields/SugarFieldHandler.php';
        static $sugarFieldObjects = array();

        $field = SugarFieldHandler::fixupFieldType($field);
        $field = ucfirst($field);

        if (!isset($sugarFieldObjects[$field])) {
            //check custom directory
            if (file_exists('custom/include/SugarFields/Fields/' . $field . '/SugarField' . $field. '.php')) {
                $file = 'custom/include/SugarFields/Fields/' . $field . '/SugarField' . $field. '.php';
                $type = $field;
                //else check the fields directory
            } else {
                if (file_exists('include/SugarFields/Fields/' . $field . '/SugarField' . $field. '.php')) {
                    $file = 'include/SugarFields/Fields/' . $field . '/SugarField' . $field. '.php';
                    $type = $field;
                } else {
                    // No direct class, check the directories to see if they are defined
                    if ($returnNullIfBase &&
                        !is_dir('custom/include/SugarFields/Fields/'.$field) &&
                        !is_dir('include/SugarFields/Fields/'.$field)) {
                        return null;
                    }
                    $file = 'include/SugarFields/Fields/Base/SugarFieldBase.php';
                    $type = 'Base';
                }
            }
            require_once($file);

            $class = 'SugarField' . $type;
            //could be a custom class check it
            $customClass = 'Custom' . $class;
            if (class_exists($customClass)) {
                $class_name = $customClass;
            } else {
                $class_name = $class;
            }
        }
        return ['file'=> $file , 'class_name' => $class_name];
    }
}