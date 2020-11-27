<?php
/**
 * Created by PhpStorm.
 * User: SeedTeam
 * Date: 21.01.2020
 * Time: 12:21
 */
require_once 'include/MVC/View/views/view.popup.php';
class CustomViewPopup extends ViewPopup{
    public $json;
    public function display()
    {
        global $popupMeta, $mod_strings;

        if (($this->bean instanceof SugarBean) && !$this->bean->ACLAccess('list')) {
            ACLController::displayNoAccess();
            sugar_cleanup(true);
        }

        if (isset($_REQUEST['metadata']) && strpos($_REQUEST['metadata'], "..") !== false) {
            die("Directory navigation attack denied.");
        }
        if (!empty($_REQUEST['metadata']) && $_REQUEST['metadata'] != 'undefined'
            && file_exists('custom/modules/' . $this->module . '/metadata/' . $_REQUEST['metadata'] . '.php')) {
            require 'custom/modules/' . $this->module . '/metadata/' . $_REQUEST['metadata'] . '.php';
        } elseif (!empty($_REQUEST['metadata']) && $_REQUEST['metadata'] != 'undefined'
            && file_exists('modules/' . $this->module . '/metadata/' . $_REQUEST['metadata'] . '.php')) {
            require 'modules/' . $this->module . '/metadata/' . $_REQUEST['metadata'] . '.php';
        } elseif (file_exists('custom/modules/' . $this->module . '/metadata/popupdefs.php')) {
            require 'custom/modules/' . $this->module . '/metadata/popupdefs.php';
        } elseif (file_exists('modules/' . $this->module . '/metadata/popupdefs.php')) {
            require 'modules/' . $this->module . '/metadata/popupdefs.php';
        }

        if (!empty($popupMeta) && !empty($popupMeta['listviewdefs'])) {
            if (is_array($popupMeta['listviewdefs'])) {
                //if we have an array, then we are not going to include a file, but rather the
                //listviewdefs will be defined directly in the popupdefs file
                $listViewDefs[$this->module] = $popupMeta['listviewdefs'];
            } else {
                //otherwise include the file
                require_once($popupMeta['listviewdefs']);
            }
        } elseif (file_exists('custom/modules/' . $this->module . '/metadata/listviewdefs.php')) {
            require_once('custom/modules/' . $this->module . '/metadata/listviewdefs.php');
        } elseif (file_exists('modules/' . $this->module . '/metadata/listviewdefs.php')) {
            require_once('modules/' . $this->module . '/metadata/listviewdefs.php');
        }

        //check for searchdefs as well
        if (!empty($popupMeta) && !empty($popupMeta['searchdefs'])) {
            if (is_array($popupMeta['searchdefs'])) {
                //if we have an array, then we are not going to include a file, but rather the
                //searchdefs will be defined directly in the popupdefs file
                $searchdefs[$this->module]['layout']['advanced_search'] = $popupMeta['searchdefs'];
            } else {
                //otherwise include the file
                require_once($popupMeta['searchdefs']);
            }
        } else {
            if (empty($searchdefs) && file_exists('custom/modules/'.$this->module.'/metadata/searchdefs.php')) {
                require_once('custom/modules/'.$this->module.'/metadata/searchdefs.php');
            } else {
                if (empty($searchdefs) && file_exists('modules/'.$this->module.'/metadata/searchdefs.php')) {
                    require_once('modules/'.$this->module.'/metadata/searchdefs.php');
                }
            }
        }

        //if you click the pagination button, it will populate the search criteria here
        if (!empty($this->bean) && isset($_REQUEST[$this->module.'2_'.strtoupper($this->bean->object_name).'_offset'])) {
            if (!empty($_REQUEST['current_query_by_page'])) {
                $blockVariables = array('mass', 'uid', 'massupdate', 'delete', 'merge', 'selectCount',
                    'sortOrder', 'orderBy', 'request_data', 'current_query_by_page');
                $current_query_by_page = json_decode(html_entity_decode($_REQUEST['current_query_by_page']), true);
                foreach ($current_query_by_page as $search_key=>$search_value) {
                    if ($search_key != $this->module.'2_'.strtoupper($this->bean->object_name).'_offset'
                        && !in_array($search_key, $blockVariables)) {
                        if (!is_array($search_value)) {
                            $_REQUEST[$search_key] = securexss($search_value);
                        } else {
                            foreach ($search_value as $key=>&$val) {
                                $val = securexss($val);
                            }
                            $_REQUEST[$search_key] = $search_value;
                        }
                    }
                }
            }
        }

        if (!empty($listViewDefs) && !empty($searchdefs)) {
            require_once('custom/include/Popups/CustomPopupSmarty.php');
            $displayColumns = array();
            $filter_fields = array();
            $popup = new CustomPopupSmarty($this->bean, $this->module);
            foreach ($listViewDefs[$this->module] as $col => $params) {
                $filter_fields[strtolower($col)] = true;
                if (!empty($params['related_fields'])) {
                    foreach ($params['related_fields'] as $field) {
                        //id column is added by query construction function. This addition creates duplicates
                        //and causes issues in oracle. #10165
                        if ($field != 'id') {
                            $filter_fields[$field] = true;
                        }
                    }
                }
                if (!empty($params['default']) && $params['default']) {
                    $displayColumns[$col] = $params;
                }
            }
            $popup->displayColumns = $displayColumns;
            $popup->filter_fields = $filter_fields;
            $popup->mergeDisplayColumns = true;
            //check to see if popupdefs contains searchdefs
            $popup->_popupMeta = $popupMeta;
            $popup->listviewdefs = $listViewDefs;
            $popup->searchdefs = $searchdefs;

            if (isset($_REQUEST['query'])) {
                $popup->searchForm->populateFromRequest();
            }

            $massUpdateData = '';
            if (isset($_REQUEST['mass'])) {
                foreach (array_unique($_REQUEST['mass']) as $record) {
                    $massUpdateData .= "<input style='display: none' checked type='checkbox' name='mass[]' value='$record'>\n";
                }
            }
            $popup->massUpdateData = $massUpdateData;

            $tpl = 'include/Popups/tpls/PopupGeneric.tpl';
            if (file_exists($this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupGeneric.tpl"))) {
                $tpl = $this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupGeneric.tpl");
            }

            if (file_exists($this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupHeader.tpl"))) {
                $popup->headerTpl = $this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupHeader.tpl");
            }

            if (file_exists($this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupFooter.tpl"))) {
                $popup->footerTpl = $this->getCustomFilePathIfExists("modules/{$this->module}/tpls/popupFooter.tpl");
            }

            $popup->setup($tpl);
            // исключаем связанные записи
            if(!empty($_REQUEST['relate_mdule']) && !empty($_REQUEST['relate_id']) && !empty($_REQUEST['relate_mdule_link'])) {
                $seedModule=BeanFactory::getBean($_REQUEST['relate_mdule'],$_REQUEST['relate_id'] );
                $seedModule->load_relationship($_REQUEST['relate_mdule_link']);
                $exclude=$seedModule->{$_REQUEST['relate_mdule_link']}->get();
                foreach( $popup->data['data'] as $k => $v ) {
                    if(in_array($v["ID"],$exclude)){
                        unset( $popup->data['data'][$k]);
                    }
                }
            }
            if (empty($_REQUEST['jsqon_return'])) {
                //We should at this point show the header and javascript even if to_pdf is true.
                //The insert_popup_header javascript is incomplete and shouldn't be relied on.
                if (isset($this->options['show_all']) && $this->options['show_all'] == false) {
                    unset($this->options['show_all']);
                    $this->options['show_javascript'] = true;
                    $this->options['show_header'] = true;
                    $this->_displayJavascript();
                }
                insert_popup_header(null, false);
                if (isset($this->override_popup['template_data']) && is_array($this->override_popup['template_data'])) {
                    $popup->th->ss->assign($this->override_popup['template_data']);
                }
                echo $popup->display();
            } else {
                /*
                 * добавлено для вывода данных в json формате
                 *
                 */
                global $mod_strings,$app_list_strings;
                $return=[];
                $this->json=[
                    'data'=> $popup->data,
                    'mod_strings'=> $mod_strings,
                    'app_list_strings'=> $app_list_strings,
                    'sugarconfig'=> $popup->displayColumns,
                    ];
                $this->beforPVdisplay($popup);
                header('Content-Type: application/json');
                echo json_encode($this->json);
            }
        } else {
            if (file_exists('modules/' . $this->module . '/Popup_picker.php')) {
                require_once('modules/' . $this->module . '/Popup_picker.php');
            } else {
                require_once('include/Popups/Popup_picker.php');
            }

            $popup = new Popup_Picker();
            $popup->_hide_clear_button = true;
            echo $popup->process_page();
        }
    }

    public function beforPVdisplay($popup){
        $filtrs = array();
        //собираем в массив данные для фильтров
        foreach ($popup->searchForm->th->ss->_tpl_vars['formData'] as $i => $arr_val) {
            $fild_name = str_replace('_advanced','',$arr_val['field']['name']);
            // если не указан лейбл для фильтра берем его из vardef
            $default_show=!empty($popup->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['default_show']) ? $popup->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['default_show'] : false;
            $label=!empty($popup->searchForm->searchdefs['layout']['advanced_search'][$fild_name]['label']) ? $popup->searchForm->searchdefs['layout']['advanced_search'][$fild_name]['label'] : $popup->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['vname'];
            $filtrs['filters'][$arr_val['field']['name']] = [
                'name' => $popup->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['name'],
                'label' => $popup->searchForm->th->ss->_tpl_vars['MOD'][$label],
                'value' => $popup->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['value'],
                'type' => $popup->searchForm->searchdefs['layout']['advanced_search'][$fild_name]['type'],
                'default_show' => $default_show,
            ];
            // сравнение типов
            switch ($popup->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['type']) {
                case 'enum':
                case 'multienum':
                    $filtrs['filters'][$arr_val['field']['name']]['options'] = $popup->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['options'];
                    break;
                case 'decimal':
                    $filtrs['filters'][$arr_val['field']['name']]['precision'] = $popup->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['precision'];
                    break;
                case 'int':
                    $filtrs['filters'][$arr_val['field']['name']]['validation'] = $popup->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['validation'];
                    break;
                case  'datetime':

                    // если включен расширенный фильтр для даты
                    if($popup->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['enable_range_search'] == 1)
                    {
                        $filtrs['filters'][$arr_val['field']['name']]['enable_range_search']= 1;
                        // формируем опции для фильтра
                        $filtrs['filters'][$arr_val['field']['name']]['options'] = $popup->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['options'];
                        $filtrs['filters'][$arr_val['field']['name']]['datef'] = $popup->searchForm->th->ss->_tpl_vars['APP_CONFIG']['datef'];
                        //воссоздаем структуру полей
                        $filtrs['filters'][$arr_val['field']['name']]['structure'] = [
                            '=' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'date'],
                            'not_equal' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'date'],
                            'greater_than' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'date'],
                            'less_than' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'date'],
                            'last_7_days' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'string'],
                            'next_7_days' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'string'],
                            'last_30_days' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'string'],
                            'next_30_days' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'string'],
                            'last_month' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'string'],
                            'this_month' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'string'],
                            'next_month' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'string'],
                            'last_year' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'string'],
                            'this_year' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'string'],
                            'next_year' => ['name' => 'range_'.$fild_name.'_advanced', 'type' => 'string'],
                            'between' => [
                                ['name' => 'start_range_'.$fild_name.'_advanced', 'type' => 'date',],
                                ['name' => 'end_range_'.$fild_name.'_advanced', 'type' => 'date',],
                            ],
                        ];
                        $popup->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice'] = !empty($popup->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice']) ? $popup->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice'] : '=';
                        //если применен фильтр и присутствует оператор, то мы добавляем опцию оператор, и поле
                        if(!empty($popup->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice'])) {
                            //добавляем к фильтру оператор
                            $filtrs['filters'][$arr_val['field']['name']]['operator'] = $popup->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice'];
                            //добавляем параметр fields к массиву с фильтрами
                            $filtrs['filters'][$arr_val['field']['name']]['fields'][$fild_name.'_advanced_range_choice'] = $fild_name.'_advanced_range_choice';
                            //добавляем отдельный фильтр
                            $filtrs['filters'][$fild_name.'_advanced_range_choice']= [
                                'name' => $fild_name.'_advanced_range_choice',
                                'label' => '',
                                'value' => $popup->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice'],
                                'type' => 'enum',
                                'subtype' => 'children',
                                'parent' => $fild_name.'_advanced',
                                'options' => $filtrs['filters'][$arr_val['field']['name']]['options'],
                            ];
                            //  зависимости от выбранного оператора добавляем нужные фильтры
                            switch ($filtrs['filters'][$arr_val['field']['name']]['operator']) {
                                case 'between':

                                    // получаем из структуры название поля
                                    $addition_filter=$filtrs['filters'][$arr_val['field']['name']]['structure'][$filtrs['filters'][$fild_name.'_advanced_range_choice']['value']];
                                    foreach ($addition_filter as $v){
                                        if(isset($popup->lv->data['pageData']['queries']['baseURL'][$v['name']])) {
                                            $filtrs['filters'][$v['name']] = [
                                                'name' => $v['name'],
                                                'label' => '',
                                                'value' => $popup->lv->data['pageData']['queries']['baseURL'][$v['name']],
                                                'type' => 'datetime',
                                                'subtype' => 'children',
                                                'parent' => $fild_name . '_advanced_range_choice',
                                            ];
                                            $filtrs['filters'][$arr_val['field']['name']]['fields'][$v['name']] = $v['name'];
                                        }
                                    }
                                    break;

                                default:
                                    // получаем из структуры название поля
                                    $addition_filter = $filtrs['filters'][$arr_val['field']['name']]['structure'][$filtrs['filters'][$fild_name . '_advanced_range_choice']['value']];
                                    if(isset($popup->lv->data['pageData']['queries']['baseURL'][$addition_filter['name']])) {
                                        //формируем дополнительное поле
                                        $filtrs['filters'][$addition_filter['name']] = [
                                            'name' => $addition_filter['name'],
                                            'label' => '',
                                            'value' => $popup->searchForm->th->ss->_tpl_vars['fields'][$addition_filter['name']]['value'],
                                            'type' => 'datetime',
                                            'subtype' => 'children',
                                            'parent' => $fild_name . '_advanced_range_choice',
                                        ];
                                        $filtrs['filters'][$arr_val['field']['name']]['fields'][$addition_filter['name']] = $addition_filter['name'];
                                    }
                                    break;
                            }
                            //date_entered_advanced_range_choice
                        }

                    }
                    break;
            }
        }
        $this->json['custom_search'] = $filtrs;
        // print_array($filtrs);
        //print_array($popup->searchForm->th->ss->_tpl_vars['formData']);
    }

}

##                                                      ##
