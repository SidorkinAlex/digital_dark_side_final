<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23.09.20
 * Time: 16:22
 */
require_once 'include/Popups/PopupSmarty.php';
require_once 'custom/include/ListView/CustomListViewData.php';
class CustomPopupSmarty extends PopupSmarty{

    public function __construct($seed, $module)
    {
        parent::__construct($seed, $module);
        $this->lvd = new CustomListViewData();

    }

    public function _setup($file)
    {
        if (isset($this->_popupMeta)) {
            if (isset($this->_popupMeta['create']['formBase'])) {
                require_once('modules/' . $this->seed->module_dir . '/' . $this->_popupMeta['create']['formBase']);
                $this->_create = true;
            }
        }
        if (!empty($this->_popupMeta['create'])) {
            $formBase = new $this->_popupMeta['create']['formBaseClass']();
            if (isset($_REQUEST['doAction']) && $_REQUEST['doAction'] == 'save') {
                //If it's a new record, set useRequired to false
                $useRequired = empty($_REQUEST['id']) ? false : true;
                $formBase->handleSave('', false, $useRequired);
            }
        }

        $params = array();
        if (!empty($this->_popupMeta['orderBy'])) {
            $params['orderBy'] = $this->_popupMeta['orderBy'];
        }

        if (file_exists('custom/modules/'.$this->module.'/metadata/metafiles.php')) {
            require('custom/modules/'.$this->module.'/metadata/metafiles.php');
        } elseif (file_exists('modules/'.$this->module.'/metadata/metafiles.php')) {
            require('modules/'.$this->module.'/metadata/metafiles.php');
        }

        if (!empty($metafiles[$this->module]['searchfields'])) {
            require($metafiles[$this->module]['searchfields']);
        } elseif (file_exists('modules/'.$this->module.'/metadata/SearchFields.php')) {
            require('modules/'.$this->module.'/metadata/SearchFields.php');
        }
        $this->searchdefs[$this->module]['templateMeta']['maxColumns'] = 2;
        $this->searchdefs[$this->module]['templateMeta']['widths']['label'] = 10;
        $this->searchdefs[$this->module]['templateMeta']['widths']['field'] = 30;

        $this->searchForm->view = 'PopupSearchForm';
        $this->searchForm->setup($this->searchdefs, $searchFields, 'SearchFormGenericAdvanced.tpl', 'advanced_search', $this->listviewdefs);

        $lv = new ListViewSmarty();
        $displayColumns = array();
        if (!empty($_REQUEST['displayColumns'])) {
            foreach (explode('|', $_REQUEST['displayColumns']) as $num => $col) {
                if (!empty($listViewDefs[$this->module][$col])) {
                    $displayColumns[$col] = $this->listviewdefs[$this->module][$col];
                }
            }
        } else {
            foreach ($this->listviewdefs[$this->module] as $col => $para) {
                if (!empty($para['default']) && $para['default']) {
                    $displayColumns[$col] = $para;
                }
            }
        }
        $params['massupdate'] = true;
        if (!empty($_REQUEST['orderBy'])) {
            $params['orderBy'] = $_REQUEST['orderBy'];
            $params['overrideOrder'] = true;
            if (!empty($_REQUEST['sortOrder'])) {
                $params['sortOrder'] = $_REQUEST['sortOrder'];
            }
        }

        $lv->displayColumns = $displayColumns;
        $this->searchForm->lv = $lv;
        $this->searchForm->displaySavedSearch = false;


        $this->searchForm->populateFromRequest('advanced_search');
        $searchWhere = $this->_get_where_clause();
        $this->searchColumns = $this->searchForm->searchColumns;
        //parent::setup($this->seed, $file, $searchWhere, $params, 0, -1, $this->filter_fields);

        $this->should_process = true;

        if (isset($params['export'])) {
            $this->export = $params['export'];
        }
        if (!empty($params['multiSelectPopup'])) {
            $this->multi_select_popup = $params['multiSelectPopup'];
        }
        if (!empty($params['massupdate']) && $params['massupdate'] != false) {
            $this->show_mass_update_form = true;
            $this->mass = new MassUpdate();
            $this->mass->setSugarBean($this->seed);
            if (!empty($params['handleMassupdate']) || !isset($params['handleMassupdate'])) {
                $this->mass->handleMassUpdate();
            }
        }

        // create filter fields based off of display columns
        if (empty($this->filter_fields) || $this->mergeDisplayColumns) {
            foreach ($this->displayColumns as $columnName => $def) {
                $this->filter_fields[strtolower($columnName)] = true;
                if (!empty($def['related_fields'])) {
                    foreach ($def['related_fields'] as $field) {
                        //id column is added by query construction function. This addition creates duplicates
                        //and causes issues in oracle. #10165
                        if ($field != 'id') {
                            $this->filter_fields[$field] = true;
                        }
                    }
                }
                if (!empty($this->seed->field_defs[strtolower($columnName)]['db_concat_fields'])) {
                    foreach ($this->seed->field_defs[strtolower($columnName)]['db_concat_fields'] as $index=>$field) {
                        if (!isset($this->filter_fields[strtolower($field)]) || !$this->filter_fields[strtolower($field)]) {
                            $this->filter_fields[strtolower($field)] = true;
                        }
                    }
                }
            }
            foreach ($this->searchColumns as $columnName => $def) {
                $this->filter_fields[strtolower($columnName)] = true;
            }
        }

        if (isset($_REQUEST['request_data'])) {
            $request_data = json_decode(html_entity_decode($_REQUEST['request_data']), true);
            $_POST['field_to_name'] = $_REQUEST['field_to_name'] = array_keys($request_data['field_to_name_array']);
        }

        /**
         * Bug #46842 : The relate field field_to_name_array fails to copy over custom fields
         * By default bean's create_new_list_query function loads fields displayed on the page or used in the search
         * add fields used to populate forms from _viewdefs :: field_to_name_array to retrive from db
         */
        if (isset($_REQUEST['field_to_name']) && $_REQUEST['field_to_name']) {
            $_REQUEST['field_to_name'] = is_array($_REQUEST['field_to_name']) ? $_REQUEST['field_to_name'] : array($_REQUEST['field_to_name']);
            foreach ($_REQUEST['field_to_name'] as $add_field) {
                $add_field = strtolower($add_field);
                if ($add_field != 'id' && !isset($this->filter_fields[$add_field]) && isset($this->seed->field_defs[$add_field])) {
                    $this->filter_fields[$add_field] = true;
                }
            }
        }


        if (!empty($_REQUEST['query']) || (!empty($GLOBALS['sugar_config']['save_query']) && $GLOBALS['sugar_config']['save_query'] != 'populate_only')) {
            $data = $this->lvd->getListViewData($this->seed, $searchWhere, 0, -1, $this->filter_fields, $params, 'id');
        } else {
            $this->should_process = false;
            $data = array(
                'data'=>array(),
                'pageData'=>array(
                    'bean'=>array('moduleDir'=>$this->seed->module_dir),
                    'ordering'=>'',
                    'offsets'=>array('total'=>0,'next'=>0,'current'=>0),
                ),
            );
        }

        $this->fillDisplayColumnsWithVardefs();

        $this->process($file, $data, $this->seed->object_name);
    }
}