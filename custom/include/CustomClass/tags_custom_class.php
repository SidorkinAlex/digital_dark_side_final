<?php
class tags_custom_class {
    public $tagsList = [];


    public function getTagsList($full_list = false) {
        global $current_user, $db;

        $filter = '';
        if ($full_list) {
            $filter = "
                OR (
                    `hrpac_tags`.`my_tag` = 1
                    AND `hrpac_tags`.`assigned_user_id` = '{$current_user->id}'
                )
            ";
        }

        $q = "
            SELECT
                `hrpac_tags`.`id`,
                `hrpac_tags`.`name`,
                `hrpac_tags`.`my_tag`
            FROM
                `hrpac_tags`
            WHERE
                `hrpac_tags`.`deleted` = 0
                AND `hrpac_tags`.`my_tag` = 0
                {$filter}
        ";

        $result = $db->query($q, true);
        $ret = [];
        while ($row = $db->fetchByAssoc($result)) {
            $ret[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'my_tag' => $row['my_tag'],
            ];
        }

        $this->tagsList = $ret;
    }
}