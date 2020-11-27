<?php

/**
 * Class Custom_Bean_Access
 *
 * Класс для кастомной логики доступов к записям модулей.
 *
 */
class Custom_Bean_Access {

    /**
     * @param $action  - действие которое проверям
     * @param $bean - объект для которого производим проверку
     * @return bool
     *
     * Общий метод для проверки доступов
     */
    public function checkCBAccess ($action, $bean) {
        switch ($action) {
            case 'delete' :
                return $this->checkDelete($bean);

            case 'edit' :
                return $this->checkEdit($bean);

            default:
                return true;
        }
    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка доступности удаления объекта
     *
     */
    private function checkDelete ($bean) {
        switch ($bean->module_name) {
            case 'HRPAC_BUSINESS_UNITS' :
                $result = $this->check_del_HRPAC_BUSINESS_UNITS($bean);
                break;

            case 'HRPAC_DEPARTMENTS' :
                $result = $this->check_del_HRPAC_DEPARTMENTS($bean);
                break;

            case 'Project' :
                $result = $this->check_del_Project($bean);
                break;

            case 'HRPAC_VACANCY_NAMES' :
                $result = $this->check_del_HRPAC_VACANCY_NAMES($bean);
                break;

            case 'Users' :
                $result = $this->check_del_Users($bean);
                break;

            case 'HRPAC_VACANCY_STATUSES' :
                $result = $this->check_del_HRPAC_VACANCY_STATUSES($bean);
                break;

            case 'Currencies' :
                $result = $this->check_del_Currencies($bean);
                break;

            case 'HRPAC_CITIES' :
                $result = $this->check_del_HRPAC_CITIES($bean);
                break;

            case 'HRPAC_SOURCES_GROUPS' :
                $result = $this->check_del_HRPAC_SOURCES_GROUPS($bean);
                break;

            case 'HRPAC_SOURCES' :
                $result = $this->check_del_HRPAC_SOURCES($bean);
                break;

            case 'HRPAC_REJECTION_CATEGORIES' :
                $result = $this->check_del_HRPAC_REJECTION_CATEGORIES($bean);
                break;

            case 'HRPAC_REJECTION_REASONS' :
                $result = $this->check_del_HRPAC_REJECTION_REASONS($bean);
                break;

            case 'HRPAC_SELECTION_STAGE' :
                $result = $this->check_del_HRPAC_SELECTION_STAGE($bean);
                break;

            case 'STAGE_Templates' :
                $result = $this->check_del_STAGE_Templates($bean);
                break;

            case 'HRPAC_VACANCY' :
                $result = $this->check_del_HRPAC_VACANCY($bean);
                break;

            case 'HRPAC_CANDIDATES' :
                $result = $this->check_del_HRPAC_CANDIDATES($bean);
                break;

        }
        return $result;
    }



    private function checkEdit ($bean)
    {
        switch ($bean->module_name) {
            case 'HRPAC_VACANCY' :
                $result = $this->check_edit_HRPAC_VACANCY($bean);
                break;
        }

        return $result;
    }
    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Бизнес Юниты
     *
     */
    private function check_del_HRPAC_BUSINESS_UNITS ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_vacancy`
            WHERE
                `hrpac_vacancy`.`hrpac_business_units_id_c` = '{$bean->id}'
                AND `hrpac_vacancy`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Отделы
     *
     */
    private function check_del_HRPAC_DEPARTMENTS ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_vacancy`
            WHERE
                `hrpac_vacancy`.`hrpac_departments_id_c` = '{$bean->id}'
                AND `hrpac_vacancy`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Проекты
     *
     */
    private function check_del_Project ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_vacancy`
            WHERE
                `hrpac_vacancy`.`project_id_c` = '{$bean->id}'
                AND `hrpac_vacancy`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Наименования вакансий
     *
     */
    private function check_del_HRPAC_VACANCY_NAMES ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_vacancy`
            WHERE
                `hrpac_vacancy`.`hrpac_vacancy_names_id_c` = '{$bean->id}'
                AND `hrpac_vacancy`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Пользователи
     *
     */
    private function check_del_Users ($bean) {
        global $db;

        //Линейный руководитель : user_id1_c
        //Нанимающий менеджер : user_id_c
        //Рекрутер : assigned_user_id
        //проверяем есть ли привязка пользователя к вакансии
        $query = "
            SELECT 
                1
            FROM
                `hrpac_vacancy`
            WHERE
                (
                    `hrpac_vacancy`.`user_id1_c` = '{$bean->id}'
                    OR `hrpac_vacancy`.`user_id_c` = '{$bean->id}'
                    OR `hrpac_vacancy`.`assigned_user_id` = '{$bean->id}'
                )
                AND `hrpac_vacancy`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        //если привязка есть, то запрещаем удалять
        if ($result != false) {
            return false;
        }

        //Дополнительные рекрутеры : table user_ram (additional_assigned)
        //Дополнительные менеджеры : table user_ram (additional_managers)
        //Наблюдатели : table user_ram (spectators)
        //Проверяем есть ли привязка пользователей к вакансии в полях-справочниках
        $query = "
            SELECT 
                1
            FROM
                `user_ram`
            WHERE
                `user_ram`.`user_id_c` = '{$bean->id}'
                AND `user_ram`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);


        if ($result == false) {
            //Разрешаем удаление.
            //На предыдущем шаге не была найдена привязка к вакансии
            //и на текущем шаге привязка к вакансии тоже не найдена.
            return true;
        } else {
            //Нашли привязку к вакансии, запрещаем удалять.
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Вакансии: статус
     *
     */
    private function check_del_HRPAC_VACANCY_STATUSES ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_vacancy`
            WHERE
                `hrpac_vacancy`.`hrpac_vacancy_statuses_id_c` = '{$bean->id}'
                AND `hrpac_vacancy`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Валюта
     *
     */
    private function check_del_Currencies ($bean) {
        global $db;

        //проверка что валюта привязана к вакансии
        $query = "
            SELECT 
                1
            FROM
                `hrpac_vacancy`
            WHERE
                `hrpac_vacancy`.`currency_id` = '{$bean->id}'
                AND `hrpac_vacancy`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);
        //Если нашли привязку к вакансии, то запрещаем удалять.
        if ($result != false) {
            return false;
        }

        //проверка что валюта привязана к кандидату
        $query = "
            SELECT 
                1
            FROM
                `hrpac_candidates`
            WHERE
                `hrpac_candidates`.`currency_id` = '{$bean->id}'
                AND `hrpac_candidates`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);


        if ($result == false) {
            //Разрешаем удаление.
            //На предыдущем шаге не была найдена привязка к вакансии
            //и на текущем шаге привязка к кандидату тоже не найдена.
            return true;
        } else {
            //Нашли привязку к кандидату, запрещаем удалять.
            return false;
        }

    }


    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля HR Города (Локация)
     *
     */
    private function check_del_HRPAC_CITIES ($bean) {
        global $db;

        //Проверка что город привязан к вакансии
        $query = "
            SELECT 
                1
            FROM
                `hrpac_vacancy`
            WHERE
                `hrpac_vacancy`.`hrpac_cities_id_c` = '{$bean->id}'
                AND `hrpac_vacancy`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);
        //Если нашли привязку к вакансии, то запрещаем удалять.
        if ($result != false) {
            return false;
        }

        //Если НЕ нашли привязку к вакансии то ищем привязку к кандидату
        $query = "
            SELECT 
                1
            FROM
                `hrpac_candidates`
            WHERE
                `hrpac_candidates`.`hrpac_cities_id_c` = '{$bean->id}'
                AND `hrpac_candidates`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);


        if ($result == false) {
            //Разрешаем удаление.
            //На предыдущем шаге не была найдена привязка к вакансии
            //и на текущем шаге привязка к кандидату тоже не найдена.
            return true;
        } else {
            //Нашли привязку к кандидату, запрещаем удалять.
            return false;
        }

    }


    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Группы источников
     *
     */
    private function check_del_HRPAC_SOURCES_GROUPS ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_sources`
            WHERE
                `hrpac_sources`.`hrpac_sources_groups_id_c` = '{$bean->id}'
                AND `hrpac_sources`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Источники
     *
     */
    private function check_del_HRPAC_SOURCES ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_candidates`
            WHERE
                `hrpac_candidates`.`hrpac_sources_id_c` = '{$bean->id}'
                AND `hrpac_candidates`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Категории отказа
     *
     */
    private function check_del_HRPAC_REJECTION_CATEGORIES ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_rejection_reasons`
            WHERE
                `hrpac_rejection_reasons`.`hrpac_rejection_categories_id_c` = '{$bean->id}'
                AND `hrpac_rejection_reasons`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Причина отказа
     *
     */
    private function check_del_HRPAC_REJECTION_REASONS ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_selection_stage_candidates_1`
            WHERE
                `hrpac_selection_stage_candidates_1`.`rejection_reasons` = '{$bean->id}'
                AND `hrpac_selection_stage_candidates_1`.`hrpac_candidates_id` IS NOT NULL
                AND `hrpac_selection_stage_candidates_1`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Этапы подбора
     *
     */
    private function check_del_HRPAC_SELECTION_STAGE ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `stage_templates_hrpac_selection_stage_1`
            WHERE
                `stage_templates_hrpac_selection_stage_1`.`hrpac_selection_stage_id` = '{$bean->id}'
                AND `stage_templates_hrpac_selection_stage_1`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля ШАБЛОНЫ ЭТАПОВ ПОДБОРА
     *
     */
    private function check_del_STAGE_Templates ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `stage_templates_hrpac_vacancy`
            WHERE
                `stage_templates_hrpac_vacancy`.`stage_templates_id` = '{$bean->id}'
                AND `stage_templates_hrpac_vacancy`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Вакансии
     *
     */
    private function check_del_HRPAC_VACANCY ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_vacancy_statuses`
            WHERE
                `hrpac_vacancy_statuses`.`id` = '{$bean->hrpac_vacancy_statuses_id_c}'
                AND `hrpac_vacancy_statuses`.`deleted` = 0
                AND `hrpac_vacancy_statuses`.`name` LIKE 'Новая'
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            //если вакансия имеет статус отличный от "Новая", то запрещаем удаление
            return false;
        } else {
            //если вакансия имеет статус "новая", то разрешаем удаление
            return true;
        }

    }

    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности удаления записи для модуля Кандидаты
     *
     */
    private function check_del_HRPAC_CANDIDATES ($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_vacancy_hrpac_candidates_1`
            WHERE
                `hrpac_vacancy_hrpac_candidates_1`.`hrpac_candidates_id` = '{$bean->id}'
                AND `hrpac_vacancy_hrpac_candidates_1`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            //если если кандидат НЕ имеет связи с вакансией то РАЗРЕШАЕМ удаление
            return true;
        } else {
            //если если кандидат имеет связь с вакансией то ЗАПРЕЩАЕМ удаление
            return false;
        }

    }


    /**
     * @param $bean - объект доступ к которому проверям
     * @return bool
     *
     * Проверка возможности редактирования записи для модуля Кандидаты
     *
     */
    private function check_edit_HRPAC_VACANCY($bean) {
        global $db;

        $query = "
            SELECT 
                1
            FROM
                `hrpac_vacancy_hrpac_candidates_1`
            WHERE
                `hrpac_vacancy_hrpac_candidates_1`.`hrpac_vacancy_id` = '{$bean->id}'
                AND `hrpac_vacancy_hrpac_candidates_1`.`deleted` = 0
            LIMIT 1
        ";

        $result = $db->getOne($query, true);

        if ($result == false) {
            //если вакансия НЕ имеет связи с кандидатом то РАЗРЕШАЕМ изменение
            return true;
        } else {
            //если если кандидат имеет связь с вакансией то ЗАПРЕЩАЕМ изменение
            return false;
        }
    }

}