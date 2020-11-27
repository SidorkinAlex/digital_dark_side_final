<?php
ini_set("xdebug.var_display_max_children", -1);
ini_set("xdebug.var_display_max_data", -1);
ini_set("xdebug.var_display_max_depth", -1);


if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once "custom/include/utils.php";

class SimpleCandidate {
//    private $fullName;
//    private $phone;
//    private $email;
//    private $city;
//    
//    private $simpleFieldsSQL;
//    private $relateFieldsSQL;

    public $simpleFields = array();
    public $relateFields = array();
    public $customFields = array();

    public $metadataFile;
    public $metadata;
    public $moduleName;
    public $tableName;
    public $thisStage;


    public function __construct() {
        $this->metadataFile = 'modules/panel_Selection/metadata/cstm_viewdefs.php';
        $this->moduleName = 'HRPAC_CANDIDATES';
        $this->tableName = 'hrpac_candidates';

        $this->setMetadata();
        $this->splitFields();
    }

    /**
     * Забираем метаданные пополям из файла
     * @return mixed
     */
    public function setMetadata()
    {
        if (file_exists($this->metadataFile)) {
            require ($this->metadataFile);
            $this->metadata = $cstm_viewdefs[$this->moduleName];
            return true;
        } else {
            return false;
        }
    }

    /**
     * Заполенение объекта данными из БД
     * @param int $id
     * @return bool
     */
    public function retrieve($id = -1) {
        if ($id == -1) {
            return false;
        }

        $this->fillSimpleFields($id);
        $this->fillRelateFields($id);
        $this->fillCustomFields($id);
    }

    /**
     * Обработка простых полей (тех которые находятся непосредственно в таблице модуля)
     * @param $ids
     */
    private function fillSimpleFields($ids){
        global $db;

        $simpleQuery = $this->createSimpleFieldsQuery($ids);

        $res = $db->query($simpleQuery, true);
        while ($row = $db->fetchByAssoc($res)) {
            foreach ($row as $k => $v) {
                $this->metadata[$k]['value'] = $v;
            }
        }
    }

    /**
     * Сборка запроса для простых полей
     * @param $ids
     * @return string
     */
    private function createSimpleFieldsQuery($ids){

        $select = !empty($this->simpleFields) ? '`' . implode('`,`', $this->simpleFields). '`' : '*';
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $ids = implode("','", $ids);

        $query = "
            SELECT
                {$select}
            FROM
                `{$this->tableName}`
            WHERE
                `deleted` = 0
                AND `id` IN ('{$ids}')
        ";

        return $query;
    }

    /**
     * Обработка relate полей, которые связь 1:М
     * @param $ids
     */
    private function fillRelateFields($ids) {
        global $db;
        
        $relateQuery = $this->createRelateFieldsQuery($ids);

        $res = $db->query($relateQuery, true);
        while ($row = $db->fetchByAssoc($res)) {
            foreach ($row as $k => $v) {
                $relFieldsData[$k] = $v;
            }
            $relFieldsDataFull[] = $relFieldsData;//собираем все строки, если связанно несколько записей
        }

        foreach ($relFieldsDataFull as $relFieldsData) {//проходим по каждой строке
            foreach ($this->relateFields as $relateFieldName) {//смотрим релейт поля
                $tarr = [];
                foreach ($this->metadata[$relateFieldName]['select_fields'] as $selectFieldName) {//смотрим поля в связанной таблице
                    $key = $relateFieldName . '_' . $selectFieldName;//Собираем название поля
                    $tarr[$selectFieldName] = $relFieldsData[$key];
                }
                $this->metadata[$relateFieldName]['value'][] = $tarr;
            }
        }
    }

    /**
     * Сборка запроса для relate полей
     * @param $ids
     * @return string
     */
    private function createRelateFieldsQuery($ids) {
        foreach ($this->relateFields as $relFiled) {
            $join_table = $this->metadata[$relFiled]['join_table'];
            $select_fields = '';
            foreach ($this->metadata[$relFiled]['select_fields'] as $selectField) {
                $asFiledName = $relFiled . '_' . $selectField;
                $select_fields .= "`{$join_table}`.`{$selectField}` AS `{$asFiledName}`, ";
            }
            $select_fields = substr($select_fields, 0, -2);
            $field_in_table = $this->metadata[$relFiled]['field_in_table'];
            $join_field = $this->metadata[$relFiled]['join_field'];

            $left_join = "
                LEFT JOIN
                    `{$join_table}` ON `{$this->tableName}`.`{$field_in_table}` = `{$join_table}`.`{$join_field}` AND `{$join_table}`.`deleted` = 0
            ";

            $select[] = $select_fields;
            $join[] = $left_join;
        }

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $ids = implode("','", $ids);

        $where = "
            WHERE
                `{$this->tableName}`.`deleted` = 0
                AND `{$this->tableName}`.`id` IN ('{$ids}')
        ";


        $ret_query = " SELECT ". implode(",\n", $select);
        $ret_query .= "\n FROM `{$this->tableName}` ";
        $ret_query .= implode("\n", $join);
        $ret_query .= $where;

        return $ret_query;
    }

    /**
     * Обработка кастомных полей, для которых в метаданных указывается название метода, который будет обрабатывать данное поле
     * @param $ids
     */
    private function fillCustomFields($ids) {
        foreach ($this->customFields as $fieldName) {
            $method_name = $this->metadata[$fieldName]['function'];
            $this->metadata[$fieldName]['value'] = $this->$method_name($ids);
        }

    }


    /**
     * Предварительное разделение полей по типам
     */
    private function splitFields() {
        foreach ($this->metadata as $field_name => $field_def) {
            switch ($field_def['type']) {
                case 'simple' :
                    $this->simpleFields[] = $field_name;
                    break;
                case 'relate' :
                    $this->relateFields[] = $field_name;
                    break;
                case 'custom' :
                    $this->customFields[] = $field_name;
                    break;
            }

        }
    }

    /**
     * Обрабтчик средств связи
     * @param $ids
     * @return array
     */
    private function getCommunications($ids) {
        return Means_of_Communication::get_value($this->moduleName, $ids);
    }

    /**
     * Рассчёт полного возраста кандидата
     * @param $ids
     * @return string
     */
    private function getFullCandidatesAge($ids) {
        return getFullAge($this->metadata['birth_date']['value']);
    }

    /**
     * Получение символа валюты
     * @param $ids
     * @return mixed
     */
    private function getCurrencySymbol($ids) {
        return CustomCurrency::get_currensy_symbol($this->metadata['currency']['value']);
    }

    /**
     * Получение последнего комментария
     * @param $ids
     * @return mixed|string
     */
    private function getLastComment($ids) {
        return getLastComment($ids);
    }

    /**
     * @return mixed
     */
    public function getThisStage()
    {
        return $this->thisStage;
    }

    /**
     * @param mixed $thisStage
     */
    public function setThisStage($thisStage)
    {
        $this->thisStage = $thisStage;
    }

}
