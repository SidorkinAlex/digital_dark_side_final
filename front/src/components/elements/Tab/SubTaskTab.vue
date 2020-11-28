<template>
  <div class="subtask-tab" v-html="formatHtml(subtaskHtml)"></div>
</template>

<script>
import { mixin } from '@/utils/mixins';
import { ACTION } from '@/utils/constants';
export default {
  mixins: [mixin],
  props: {
    module: {
      type: String
    },
    id: {
      type: String
    }
  },
  data() {
    return {
      subtaskHtml: ''
    };
  },
  created() {
    this.$axios
      .get('index.php', {
        params: {
          module: this.module,
          action: ACTION.SUBPANEL_VIEWER,
          subpanel: this.module,
          record: this.id,
          to_pdf: true,
          inline: 1,
          ajaxSubpanel: true
        }
      })
      .then(resp => {
        if (resp.data && !resp.data.error) {
          this.subtaskHtml = resp.data;
        }
      })
      .catch(err =>
        this.catchError(
          err,
          'Возникла ошибка загрузки списка подзадач',
          'subtask list loading'
        )
      )
      .finally(() => this.$emit('set-loading', false));
  }
};
</script>
