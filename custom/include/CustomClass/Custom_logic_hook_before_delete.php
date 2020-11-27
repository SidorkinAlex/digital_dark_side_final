<?php

/**
 * Class before_delete_logic_hook
 * Кастомная проверка на разрешение удаления
 */

require_once 'custom/include/CustomClass/Custom_Bean_Access.php';

class before_delete_logic_hook {

    public function beforeDeleteCustomLogic($bean, $event, $arguments){
        global $app_list_strings;
        //Кастомнаня  логика доступов к записи
        $cba = new Custom_Bean_Access();

        if (!empty($bean->id) && !$cba->checkCBAccess('delete', $bean)) {
            SugarApplication::appendErrorMessage($app_list_strings['deletion_ban']);
            if ($bean->module_name == 'Currencies') {
                SugarApplication::redirect("index.php?module=" . $bean->module_name . "&action=index");
            } else {
                SugarApplication::redirect("index.php?module=" . $bean->module_name . "&action=DetailView&record=" . $bean->id);
            }
            sugar_die('');
        }
    }

}