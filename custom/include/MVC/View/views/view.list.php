<?php
/**
 * Created by PhpStorm.
 * User: seedteam
 * Date: 18.03.20
 * Time: 20:23
 */
require_once 'include/MVC/View/views/view.list.php';

class CustomViewList extends ViewList
{
    public $do_setup = false;

    public function listViewProcess()
    {
        $this->processSearchForm();
        $this->lv->searchColumns = $this->searchForm->searchColumns;

        if (!$this->headers) {
            return;
        }
        if (empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false) {
            $this->lv->ss->assign("SEARCH", true);
            $this->lv->ss->assign('savedSearchData', $this->searchForm->getSavedSearchData());
//            $this->lv->setup($this->seed, 'include/ListView/ListViewGeneric.tpl', $this->where, $this->params);
            $savedSearchName = empty($_REQUEST['saved_search_select_name']) ? '' : (' - ' . $_REQUEST['saved_search_select_name']);
            $this->beforLVdisplay();
            if (!$this->do_setup) {
                $this->lv->setup($this->seed, 'include/ListView/ListViewGeneric.tpl', $this->where, $this->params);
            }
            echo $this->lv->display();
        }
    }

    public function beforLVdisplay()
    {
        //Добавляем переменные для фильтров в кастомных модулях
        //$this->advansed_custom_search();


    }

    public function advansed_custom_search(){
        //        print_array($this->searchForm->th->ss->_tpl_vars['fields']);
//        print_array($this->searchForm->searchFields['date_entered']);
//        print_array($this->lv->data['pageData']['queries']['baseURL']);
//        print_array($this->lv->data['pageData']['urls']['baseURL']);
        $filtrs = array();
        //собираем в массив данные для фильтров
        foreach ($this->searchForm->th->ss->_tpl_vars['formData'] as $i => $arr_val) {
            $fild_name = str_replace('_advanced','',$arr_val['field']['name']);
            // если не указан лейбл для фильтра берем его из vardef
            $label=!empty($this->searchForm->searchdefs['layout']['advanced_search'][$fild_name]['label']) ? $this->searchForm->searchdefs['layout']['advanced_search'][$fild_name]['label'] : $this->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['vname'];
            $filtrs['filters'][$arr_val['field']['name']] = [
                'name' => $this->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['name'],
                'label' => $this->searchForm->th->ss->_tpl_vars['MOD'][$label],
                'value' => $this->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['value'],
                'type' => $this->searchForm->searchdefs['layout']['advanced_search'][$fild_name]['type'],
            ];
            // сравнение типов
            switch ($this->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['type']) {
                case 'enum':
                case 'multienum':
                    $filtrs['filters'][$arr_val['field']['name']]['options'] = $this->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['options'];
                    break;
                case 'decimal':
                    $filtrs['filters'][$arr_val['field']['name']]['precision'] = $this->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['precision'];
                    break;
                case 'int':
                    $filtrs['filters'][$arr_val['field']['name']]['validation'] = $this->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['validation'];
                    break;
                case  'datetime':

                    // если включен расширенный фильтр для даты
                    if($this->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['enable_range_search'] == 1)
                    {
                        $filtrs['filters'][$arr_val['field']['name']]['enable_range_search']= 1;
                        // формируем опции для фильтра
                        $filtrs['filters'][$arr_val['field']['name']]['options'] = $this->searchForm->th->ss->_tpl_vars['fields'][$arr_val['field']['name']]['options'];
                        $filtrs['filters'][$arr_val['field']['name']]['datef'] = $this->searchForm->th->ss->_tpl_vars['APP_CONFIG']['datef'];
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
                        $this->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice'] = !empty($this->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice']) ? $this->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice'] : '=';
                        //если применен фильтр и присутствует оператор, то мы добавляем опцию оператор, и поле
                        if(!empty($this->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice'])) {
                            //добавляем к фильтру оператор
                            $filtrs['filters'][$arr_val['field']['name']]['operator'] = $this->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice'];
                            //добавляем параметр fields к массиву с фильтрами
                            $filtrs['filters'][$arr_val['field']['name']]['fields'][$fild_name.'_advanced_range_choice'] = $fild_name.'_advanced_range_choice';
                            //добавляем отдельный фильтр
                            $filtrs['filters'][$fild_name.'_advanced_range_choice']= [
                                'name' => $fild_name.'_advanced_range_choice',
                                'label' => '',
                                'value' => $this->lv->data['pageData']['queries']['baseURL'][$fild_name.'_advanced_range_choice'],
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
                                        if(isset($this->lv->data['pageData']['queries']['baseURL'][$v['name']])) {
                                            $filtrs['filters'][$v['name']] = [
                                                'name' => $v['name'],
                                                'label' => '',
                                                'value' => $this->lv->data['pageData']['queries']['baseURL'][$v['name']],
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
                                    if(isset($this->lv->data['pageData']['queries']['baseURL'][$addition_filter['name']])) {
                                        //формируем дополнительное поле
                                        $filtrs['filters'][$addition_filter['name']] = [
                                            'name' => $addition_filter['name'],
                                            'label' => '',
                                            'value' => $this->searchForm->th->ss->_tpl_vars['fields'][$addition_filter['name']]['value'],
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
        // записываем сохранненые шаблоны
        $this->searchForm->th->ss->_tpl_vars['savedSearchData']['selected']=array_key_exists($this->searchForm->th->ss->_tpl_vars['savedSearchData']['selected'],$this->searchForm->th->ss->_tpl_vars['savedSearchData']['options']) ? $this->searchForm->th->ss->_tpl_vars['savedSearchData']['selected'] : '' ;
        $filtrs['savedSearchData'] = $this->searchForm->th->ss->_tpl_vars['savedSearchData'];
        // добавляем все поля в сортировку
        $filtrs['sortable']['fields'] = array_keys($this->listViewDefs[$this->bean->module_dir]);
        $filtrs['sortable']['fields']=array_flip($filtrs['sortable']['fields']);
        //удаляем из сортировки поля у которых она запрещена
        foreach ($filtrs['sortable']['fields'] as $key => $value) {
            $filtrs['sortable']['fields'][$key]=$this->searchForm->th->ss->_tpl_vars['MOD'][$this->listViewDefs[$this->bean->module_dir][$key]['label']];
            if (isset($this->listViewDefs[$this->bean->module_dir][$key]['sortable'])) {
                unset($filtrs['sortable']['fields'][$key]);
            }
        }
        // добавляем данные текущей сортировки
        $filtrs['sortable']['orderBy'] = $this->params['orderBy'];
        $filtrs['sortable']['sortOrder'] = $this->params['sortOrder'];
        $filtrs['sortable']['sorttype'] = ['ASC', 'DESC'];
        $this->lv->ss->assign("CUSTOM_SEARCH", $filtrs);
        //print_array($filtrs);
    }

    public function prepareSearchForm()
    {
        //модули для которых используется только расширенный поиск;
        $only_advanced_search = ['HRPAC_CANDIDATES', 'HRPAC_VACANCY'];
        //Проверяем текущий модуль входит в эти модули, если да то выводим расширенный поиск
        if (in_array($this->module, $only_advanced_search)) {
            $_REQUEST['searchFormTab'] = 'advanced_search';
        }
        parent::prepareSearchForm();
    }


}