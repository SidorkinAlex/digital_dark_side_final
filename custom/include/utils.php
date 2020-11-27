<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.10.20
 * Time: 14:20
 */
//кастомные сss
function add_custom_css() {
    $version=getVersionedPath('');
    $var='';
    if(is_dir("custom/include/style/")){
        foreach(scandir("custom/include/style/") as $scriptname){
            switch($scriptname){
                case ".":
                case "..":
                    break;
                default:
                    $var .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"custom/include/style/{$scriptname}?v={$version}\"/>";
                    break;
            }
        }
    }
    return $var;
}
//кастомные js
function add_custom_js() {
    $version=getVersionedPath('');
    $var='';
    if(is_dir("custom/include/js/")){
        foreach(scandir("custom/include/js/") as $scriptname){
            if ('js' != end(explode('.', $scriptname))) continue;
            switch($scriptname){
                case ".":
                case "..":
                    break;
                default:
                    $var .= "<script type=\"text/javascript\" src=\"custom/include/js/{$scriptname}?v={$version}\"></script>";
                    break;
            }
        }
    }
    return $var;
}

// отладка
if (!function_exists('print_array')) {
    function print_array($var, $exit = false, $in_file = false)
    {
        if($in_file) ob_start();

        if (!$in_file) echo '<pre>';
        print_r($var);
        if (!$in_file) echo '</pre>' . "\n";
        if ($in_file) $content = ob_get_contents();

        if(isset($GLOBALS['print_array_filename']) AND $GLOBALS['print_array_filename'] != '') {
            $filename = $GLOBALS['print_array_filename'];
        } else {
            $filename = 'print_array.log';
        }
        if ($in_file) {
            $file = fopen("cache/" . $filename, "a+");
            fwrite($file, "\n\n******************************\n");
            fwrite($file, date("Y-m-d H:i:s") . "\n");
            fwrite($file, $content);
            fclose($file);
            empty($file);

            ob_end_clean();
        }

        if ($exit) exit;
    }
}
//получение списка этапов подбора
if (!function_exists('selection_stage_list')) {
    function selection_stage_list($view,$id='')
    {
        switch ($view) {
            case "edit":
                global $db;

                $sql = "SELECT `id`, `name`, `activity`, `color`, `required_stage`, `required_position`, `position_sort` FROM `hrpac_selection_stage` WHERE `deleted`=0";

                $result = $db->query($sql, 1);
                $return = array();
                while ($row = $db->fetchByAssoc($result)) {
                    $return[] = $row;
                }
                break;
            case "detail":
                global $db;

                $sql = "SELECT h.`id`, h.`name`, h.`activity`, h.`color` , s.`sort`, h.`required_stage`, h.`required_position`, h.`position_sort`
                FROM `stage_templates_hrpac_selection_stage_1` s
                INNER JOIN  `hrpac_selection_stage` h ON s.hrpac_selection_stage_id=h.id AND h.deleted='0'
                WHERE 
                s.`stage_templates_id`='{$id}'
                AND 
                s.deleted='0'
                ";

                $result = $db->query($sql, 1);
                $return = array();
                while ($row = $db->fetchByAssoc($result)) {
                    $return[] = $row;
                }
                break;
            default;
                $return='error';
                break;
        }
        return $return;
    }
}
//получение списка
if (!function_exists('table_sql_list')) {
    function table_sql_list($tablename,$fields,$filter='')
    {
        if(is_array($fields)) {
            $select = '`'.implode("`,`", $fields).'`';
        } else {
            $select = $fields;
        }
        if(empty($filter)){
            $where='1';
        } else {
            $where=$filter;
        }
        global $db;
        $sql = "
         SELECT {$select}
         FROM `{$tablename}`
         WHERE
         `deleted`='0'
         AND 
         {$where}
         ";
        $result = $db->query($sql,1);
        $return = array();
        while ($row = $db->fetchByAssoc($result)) {
            $return[] = $row;
        }

        return $return;
    }
}

if (!function_exists('get_currency_list')) {
    function get_currency_list()
    {
        global $db, $sugar_config ;
        $sql = "
         SELECT *
         FROM `currencies`
         WHERE
         `deleted`='0'
         ";
        $result=$db->query($sql,1);
        while ($row = $db->fetchByAssoc($result)) {
            $return[$row['id']] = $row;
        }
        $return['-99']=array(
            'id' => '-99',
            'name' => $sugar_config['default_currency_name'],
            'symbol' => $sugar_config['default_currency_symbol'],
            'iso4217' => $sugar_config['default_currency_iso4217'],
            'conversion_rate' => 1,
            'status' => 'System',
            'deleted' => '0',
            'created_by' => '1',
        );

        return $return;
    }
}
if (!function_exists('print_debug')) {
    function print_debug($exit = false, $in_file = false)
    {
        if($in_file) ob_start();

        $debug = debug_backtrace();

        if (!$in_file) echo '<pre>';

        echo "******************************\n";

        $counter = 0;
        for ($i = 0, $c = count($debug); $i < $c - 1; $i++) {

            echo "***  ".$i."  ***\n";
            echo "- file: " . $debug[$i]['file'] . "\n";
            echo "- line: " . $debug[$i]['line'] . "\n";
            echo "- function: " . $debug[$i]['function'] . "\n";
            echo "\n";

        }
//        foreach ($debug as $line) {
//            if(isset($line['object'])) unset($line['object']);
//            if($counter) var_export($line);
//
//            $counter++;
//            break;
//        }

        if (!$in_file) echo '</pre>' . "\n";
        if ($in_file) $content = ob_get_contents();

        if(isset($GLOBALS['print_debug_filename']) AND $GLOBALS['print_debug_filename'] != '') {
            $filename = $GLOBALS['print_debug_filename'];
        } else {
            $filename = 'print_debug.log';
        }
        if ($in_file) {
            $file = fopen("cache/" . $filename, "a+");
            //fwrite($file, "\n\n******************************\n");
            //fwrite($file, date("Y-m-d H:i:s") . "\n");
            fwrite($file, $content);
            fclose($file);
            empty($file);

            ob_end_clean();
        }

        if ($exit) exit;
    }
}
// функция обновлениия конфига
if(!function_exists('update_js_version')) {
    function update_js_version()
    {
        global $sugar_config;
        $sugar_config['js_custom_version']++;
        write_var_in_file('config-js-version', '$sugar_config["js_custom_version"]', $sugar_config['js_custom_version']);
    }
}
//запись переменной в файл юзрз
if(!function_exists('write_var_in_file')) {
    function write_var_in_file(string $filename, string $var_name,$var_data){
        $write_string="<?php {$var_name}=".var_export($var_data,1) .";";
        file_put_contents($filename.".php",$write_string);
    }
}
//рекурсивный поимск по массиву
function findArray ($ar, $findValue,$format){
    //print_array($findValue);
    //print_array($ar);
    $result = array();
    $executeKeys=array_keys($ar);
    foreach ($ar as $k => $v) {
        if (is_array($ar[$k])) {
            $second_result = findArray($ar[$k], $findValue,$format);
            $result = array_merge($result, $second_result);
            continue;
        }
        if(is_array($findValue)){
            foreach ($findValue as $vf) {
                if ((string)$v == (string)$vf) {
                    $row=array();
                    foreach ($executeKeys as $val) {

                        $row[$val]= $ar[$val];
                    }
                    $row2=array();
                    foreach ($format as $val) {
                        $row2[]=$row[$val];
                    }
                    $result[] = implode(' ',$row2);
                }
            }
        } else {
            if ($v == $findValue) {
                foreach ($executeKeys as $val) {
                    $result[] = $ar[$val];
                }
            }
        }
    }
    //$result=implode(',',$result);
    //print_array($result);
    return $result;
}
if (!function_exists('getMetaDataFile')) {
    function getMetaDataFile($type, $module_dir)
    {
        $metadataFile = null;
        $foundViewDefs = false;
        $viewDef = strtolower($type) . 'viewdefs';
        $coreMetaPath = 'modules/' . $module_dir . '/metadata/' . $viewDef . '.php';
        if (file_exists('custom/' . $coreMetaPath)) {
            $metadataFile = 'custom/' . $coreMetaPath;
            $foundViewDefs = true;
        } else {
            if (file_exists('custom/modules/' . $module_dir . '/metadata/metafiles.php')) {
                require_once('custom/modules/' . $module_dir . '/metadata/metafiles.php');
                if (!empty($metafiles[$module_dir][$viewDef])) {
                    $metadataFile = $metafiles[$module_dir][$viewDef];
                    $foundViewDefs = true;
                }
            } elseif (file_exists('modules/' . $module_dir . '/metadata/metafiles.php')) {
                require_once('modules/' . $module_dir . '/metadata/metafiles.php');
                if (!empty($metafiles[$module_dir][$viewDef])) {
                    $metadataFile = $metafiles[$module_dir][$viewDef];
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
}
if (!function_exists('get_url_var')) {
    function get_url_var($url)
    {
        $parts = parse_url($url);
        parse_str($parts['query'], $query);
        return $query;
    }
}
if(!function_exists('search_in_2lavel_arr')){
    function search_in_2lavel_arr($value , string $serchinkey,  array $array) {
        foreach ($array as $key => $val) {
            if ($val[$serchinkey] === $value) {
                return $key;
            }
        }
        return null;
    }
}

if (!function_exists('table_list_arr')) {
    /**
     * table_list_arr.
     *
     * This is a helper function to return an Array of users depending on the parameters passed into the function.
     * This function uses the get_register_value function by default to use a caching layer where supported.
     * This function has been updated return the array sorted by user preference of name display (bug 62712)
     *
     * @param string   $tablename        таблица для формирования массива
     * @param array    $fields           список поллей в виде нумерованного массива, для вывода в значение
     * @param string   $filter           фильтр для дополнительного условия в выборке
     *
     * @return array Array of users matching the filter criteria that may be from cache (if similar search was previously run)
     */
    function table_list_arr($tablename,$fields,$filter='',$order_by='', $sort=1)
    {

        $select = '`'.implode("`,`", $fields).'`';
        if(empty($filter)){
            $where='1';
        } else {
            $where=$filter;
        }
        global $db;
        $sql = "
         SELECT  `id`, {$select}
         FROM `{$tablename}`
         WHERE
         `deleted`='0'
         AND 
         {$where}
         ";
        if(!empty($order_by)){
            if($sort==1){
                $sort_type="ASC";
            } elseif ($sort==0){
                $sort_type="DESC";
            }
            $sql.="ORDER BY {$order_by} {$sort_type}";
        }
        $result = $db->query($sql,1);
        $return = array();
        while ($row = $db->fetchByAssoc($result)) {
            $arr=$row;
            unset($arr['id']);
            $value=implode(" ",$arr);
            $return[$row['id']] = $value;
        }

        return $return;
    }
}

if (!function_exists('isJSON')) {
    function isJSON($string)
    {
        return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
    }
}

/**
 * Функция обработки SQL скрипта в subquery для стандартного поиска
 */
if (!function_exists('custom_string_format')) {
    function custom_string_format($format, $args)
    {
        $result = $format;
        for ($i = 0; $i < count($args); ++$i) {
            if (strpos($args[$i], ',') !== false) {
                $values = explode(',', $args[$i]);
                $args[$i] = implode("','", $values);
            }

            $result = str_replace('{'.$i.'}', $args[$i], $result);
        }

        return $result;
    }
}

/**
 * Проверка может ли текущий пользователь просматривать комментарии с чекнутым чекбоксом "Только для рекрутеров"
 */
if (!function_exists('can_see_comment')) {
    function can_see_comment($to_recruit) {
        global $current_user, $sugar_config;

        //Если чекбокс не установлен или текущий пользователь админ, разрешаем показ коммента
        if ($to_recruit == 0 || $current_user->isAdmin()) {
            return true;
        }

        //Проверяем роли пользователя
        foreach (ACLRole::getUserRoles($current_user->id, false) as $usersRole) {
            if (in_array($usersRole->id, $sugar_config['roles_can_see_comment'])) {
                //Если хотя бы одна роль ползователя совпадает с ролями разрешёнными для показа
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('print_log')) {
    function print_log($var, $filename = "log")
    {
        ob_start();
        print_r($var);
        $content = ob_get_contents();
        if (!file_exists("cache/log")) {
            mkdir("cache/log");
        }
        if (!file_exists("cache/log/{$filename}")) {
            mkdir("cache/log/{$filename}");
        }

        $file = fopen("cache/log/{$filename}/" . date("Y-m-d") . $filename . '.log', "a+");
        fwrite($file, "\n\n******************************\n");
        fwrite($file, date("Y-m-d H:i:s") . "\n");
        fwrite($file, $content);
        fclose($file);
        empty($file);

        ob_end_clean();
    }
}
/**
 * Рассчёт возраста (полное кол-во лет)
 */
if (!function_exists('getFullAge')) {
    function getFullAge($birthday) {
        $datetime = new DateTime($birthday);
        $interval = $datetime->diff(new DateTime(date("Y-m-d")));

        return $interval->format("%Y");
    }
}

/**
 * Получение последнего комментария по id объекта
 * @param $parent_id
 * @return mixed|string
 */
if (!function_exists('getLastComment')) {
    function getLastComment($parent_id)
    {
        global $db;
        $sql = "
            SELECT 
                `text`, 
                `to_recruits`
            FROM 
                `hrpac_comments`
            WHERE
                `deleted` = '0'
            AND
                `parent_id`='{$parent_id}'
            ORDER BY `date_entered` DESC
            LIMIT 1
        ";
        $result = $db->query($sql, 1);
        $ret = '';
        if ($row = $db->fetchByAssoc($result)) {
            if (can_see_comment($row['to_recruits'])) {
                $ret = $row['text'];
            }
        }
        return $ret;
    }
}

/**
 * Проверка может ли текущий пользователь просматривать комментарии с чекнутым чекбоксом "Только для рекрутеров"
 */
if (!function_exists('user_in_role')) {
    function user_in_role($check_role_id) {
        global $current_user, $sugar_config;

        //Проверяем роли пользователя
        foreach (ACLRole::getUserRoles($current_user->id, false) as $usersRole) {
            if ($usersRole->id == $check_role_id) {
                //Если хотя бы одна роль пользователя совпадает с ролями разрешёнными для показа
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('generator_letters')) {
    function generator_letters(int $length) {
        return substr(str_shuffle('AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz'),1,$length);
    }
}