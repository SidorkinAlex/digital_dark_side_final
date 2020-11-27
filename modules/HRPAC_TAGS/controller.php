<?php
class HRPAC_TAGSController extends SugarController
{
    /**
     * Получить все личные теги текущего пользвоателя
     */
    function action_getUsersPrivateTags() {
        global $db, $current_user;

        $sql = "
            SELECT
                `hrpac_tags`.`id`,
                `hrpac_tags`.`name`
            FROM
                `hrpac_tags`
            WHERE
                `hrpac_tags`.`deleted` = 0
                AND `hrpac_tags`.`assigned_user_id` = '{$current_user->id}'
                AND `hrpac_tags`.`my_tag` = 1
        ";

        $res = $db->query($sql, true);
        $result = [];
        while ($row = $db->fetchByAssoc($res)) {
            $result[] = [
                'tag_id' => $row['id'],
                'tag_name' => $row['name'],
            ];
        }

        $result = json_encode($result);

        echo $result;
    }

    /**
     * Созадть тег.
     */
    function action_createTag () {
        global $current_user;

        $tag_name = $_REQUEST['tag_name'];
        $my_tag = $_REQUEST['my_tag'];

        if (!empty($tag_name)) {
            $seed_tag = new HRPAC_TAGS();
            $seed_tag->name = $tag_name;
            $seed_tag->my_tag = (0 == $my_tag || 1 == $my_tag) ? $my_tag : 0;
            $seed_tag->assigned_user_id = $seed_tag->my_tag ? $current_user->id : null;

            $tag_id = $seed_tag->save();

            if ($tag_id) {
                $result[] = [
                    'tag_id' => $tag_id
                ];
            } else {
                $result['error'] = "Couldn't create a tag";
            }
        } else {
            $result['error'] = "Need tag`s name";
        }
        $result = json_encode($result);

        echo $result;

        
    }

    /**
     * Удалить связь тега и кандидата
     */
    function action_removeTagFromCandidate() {
        $tag_id = $_REQUEST['tag_id'];
        $candidate_id = $_REQUEST['candidate_id'];

        $seed_cand = new DIGIT_TASK();
        $seed_cand->retrieve($candidate_id);

        if (!empty($seed_cand->id) && !empty($tag_id)) {
            $seed_cand->load_relationship('digit_task_hrpac_tags_1');
            $seed_cand->digit_task_hrpac_tags_1->delete($seed_cand->id, $tag_id);

            $result[] = 'deleted';
        } else {
            $result['error'] =  "Couldn't delete a tag";
        }

        $result = json_encode($result);

        echo $result;
    }

    /**
     * Получить все теги привзяанные к кандидату.
     */
    function action_getCandidatesTags() {
        global $current_user;

        $candidate_id = $_REQUEST['candidate_id'];

        $seed_cand = new DIGIT_TASK();
        $seed_cand->retrieve($candidate_id);

        $result = [];

        if (!empty($seed_cand->id)) {
            $seed_cand->load_relationship('digit_task_hrpac_tags_1');
            $tags_objs = $seed_cand->digit_task_hrpac_tags_1->getBeans();

            foreach ($tags_objs as $seed_tag) {
                if (
                    $seed_tag->my_tag == 0
                    || ($seed_tag->my_tag == 1 && $current_user->id == $seed_tag->assigned_user_id )
                ){
                    //Забираем тег, если он общий и если личный для текущего пользователя.
                    $result[] = [
                        'tag_id' => $seed_tag->id,
                        'tag_name' => $seed_tag->name,
                        'my_tag' => $seed_tag->my_tag,
                    ];
                }
            }

        } else {
            $result['error'] = 'Need candidate`s ID';
        }

        $result = json_encode($result);

        echo $result;
    }


    function action_addTagToCandidate() {
        $tag_id = $_REQUEST['tag_id'];
        $candidate_id = $_REQUEST['candidate_id'];

        $seed_cand = new DIGIT_TASK();
        $seed_cand->retrieve($candidate_id);

        if (!empty($seed_cand->id) && !empty($tag_id)) {
            $seed_cand->load_relationship('digit_task_hrpac_tags_1');
            $seed_cand->digit_task_hrpac_tags_1->add($tag_id);

            $result[] = 'added';
        } else {
            $result['error'] =  "Couldn't add a tag";
        }

        $result = json_encode($result);

        echo $result;
    }

}