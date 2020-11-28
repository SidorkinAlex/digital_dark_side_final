<template>
  <div>
    test data 1
  </div>
</template>

<script>
import { mixin } from '@/utils/mixins';
import { MODULE, FIELD, ACTION, SUBPANEL } from '@/utils/constants';
export default {
  mixins: [mixin],
  props: {
    data: Object,
    module: Object,
    taskId: String
  },
  data() {
    return {
      assigned_list: []
    }
  },
  created() {
    this.$axios
      .get('index.php', {
        params: {
          module: this.module,
          action: ACTION.GET_SUBPANEL_JSON_DATA,
          subpanel: SUBPANEL.ASSIGNED_TASK_INFO,
          record: this.taskId,
          to_pdf: true,
          json_return: 1
        }
      })
      .then(resp => {
        if (resp.data && !resp.data.error) {
          const list = resp.data.List;
          console.log(list)

          // if (comments.length) {
          //   comments.forEach(comment => {
          //     this.comments.push({
          //       id: comment.id.value,
          //       user_id: comment.created_by.value,
          //       name: comment.created_by_name.value,
          //       date_entered: comment.date_entered.value,
          //       text: this.formatHtml(comment.text.value),
          //       to_recruits: comment.to_recruits.value,
          //       avatar: ''
          //     });
          //   });
          // }
        }
      })
      .catch(err =>
        this.catchError(
          err,
          'Возникла ошибка загрузки списка ответственных в задаче',
          'assigned by task loading'
        )
      )
      .finally(() => this.$emit('set-loading', false));
  },
}
</script>