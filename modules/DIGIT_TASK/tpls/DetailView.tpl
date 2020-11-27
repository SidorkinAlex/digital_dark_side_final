<task-view
  :data="data"
  :mod="mod"
  :date-format="dateFormat"
  :user_id="user_id"
  :tags_list="tags_list"
></task-view>
{*<script src="custom/include/lib/timeline/jquery.min.js"></script>*}
<script src="custom/include/lib/timeline/timeline.js"></script>
<script>
const data = {$fields|@json_encode};
const mod = {$MOD|@json_encode};
const dateFormat = {$CALENDAR_DATEFORMAT|@json_encode};
const timeFormat = {$TIME_FORMAT|@json_encode};
const user_id = {$current_user->id|@json_encode};
const tags_list = {$tags_list|@json_encode};
  console.log(data, tags_list, mod);

  {literal}
  new Vue({
    el: "#content",
    data() {
      return {
        data,
        mod,
        user_id,
        dateFormat: {
          date: dateFormat,
          time: timeFormat
        },
        tags_list: tags_list || []
      }
    },
    components: {
      'task-view': CRMSuite.default.TaskView,
    },
  });
  {/literal}
</script>