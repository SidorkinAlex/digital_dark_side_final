<template>
  <div class="comment-tab">
    <Dialog
      v-if="is_visible.comment_dialog"
      title="Вы действительно хотите удалить этот комментарий?"
      :is_visible="is_visible.comment_dialog"
      @ok-callback="confirmCommentDeletion"
      @cancel-callback="closeDialog('comment_dialog', null, 'deletedComment')"
    >
    </Dialog>
    <div class="inline-buttons">
      <el-button
        class="inline-buttons__btn"
        type="primary"
        :disabled="hasNoRights"
        @click="is_visible.comment_form = true"
        >Комментарий
      </el-button>
    </div>
    <s-comment-form
      v-if="!hasNoRights"
      :form-visible="is_visible.comment_form"
      @set-form-visible="showCommentForm"
      :edited-data="editedComment"
      @save-comment-form="saveCommentForm"
      :loader="saveLoading"
    ></s-comment-form>
    <s-comment
      v-for="(comment, i) in comments"
      :data="comment"
      :key="`${i}_${comment.date}`"
      :user_id="user_id"
      @delete-comment="deleteComment"
      @set-form-visible="showCommentForm"
    ></s-comment>
    <div v-if="!comments.length && hasNoRights" class="empty">
      <span>Нет прав на просмотр комментариев</span>
    </div>
  </div>
</template>
<script>
import { mixin } from '@/utils/mixins';
import { MODULE, FIELD, ACTION, SUBPANEL } from '@/utils/constants';
import Dialog from 'Elements/Dialog/Dialog';
import SComment from 'Elements/Comment/SComment';
import SCommentForm from 'Elements/Comment/SCommentForm';
export default {
  mixins: [mixin],
  props: {
    module: String,
    id: String,
    user_id: String
  },
  data() {
    return {
      editedComment: null,
      deletedComment: null,
      comments: [],
      is_visible: {
        comment_form: false,
        comment_dialog: false
      },
      saveLoading: false,
      hasNoRights: false
    };
  },
  created() {
    this.$axios
      .get('index.php', {
        params: {
          module: this.module,
          action: ACTION.GET_SUBPANEL_JSON_DATA,
          subpanel: SUBPANEL.COMMENTS,
          record: this.id,
          to_pdf: true,
          sort_by: FIELD.ID.DATE_ENTERED,
          type_sort: 'DESC'
        }
      })
      .then(resp => {
        if (resp.data && !resp.data.error) {
          const comments = resp.data.List;

          if (comments.length) {
            comments.forEach(comment => {
              this.comments.push({
                id: comment.id.value,
                user_id: comment.created_by.value,
                name: comment.created_by_name.value,
                date_entered: comment.date_entered.value,
                text: this.formatHtml(comment.text.value),
                to_recruits: comment.to_recruits.value,
                avatar: ''
              });
            });
          }
        }
        if (resp.data.error) {
          this.hasNoRights = true;
        }
      })
      .catch(err =>
        this.catchError(
          err,
          'Возникла ошибка загрузки списка комментариев',
          'comments loading'
        )
      )
      .finally(() => this.$emit('set-loading', false));
  },
  methods: {
    showCommentForm({ flag, comment = null }) {
      const isAuthor = comment && this.user_id === comment.user_id;
      if (isAuthor || !comment) {
        this.editedComment = null;
        this.is_visible.comment_form = false;
        this.$nextTick(function() {
          this.editedComment = comment;
          this.is_visible.comment_form = flag;
        });
      }
    },
    saveCommentForm(comment, edited) {
      if (comment) {
        if (!this.requestSent) {
          this.requestSent = true;
          this.saveLoading = true;
          const formData = new FormData();
          formData.set('module', MODULE.HRPAC_COMMENTS);
          formData.set('record', edited ? comment.id : '');
          formData.set('action', ACTION.SAVE);
          formData.set('relate_to', MODULE.HRPAC_COMMENTS);
          formData.set('relate_id', this.id);
          formData.set('parent_type', this.module);
          formData.set('parent_id', this.id);
          formData.set('assigned_user_id', this.user_id);
          formData.set('to_recruits', Number(comment.to_recruits));
          formData.set('text', comment.text);

          this.$axios
            .post('index.php', formData)
            .then(() => {
              this.is_visible.comment_form = false;
              location.reload();
            })
            .catch(err =>
              this.catchError(
                err,
                'Возникла ошибка при сохранении комментария',
                'save comment'
              )
            )
            .finally(() => {
              this.requestSent = false;
              this.saveLoading = false;
            });
        }
      }
    },
    deleteComment(comment) {
      const isAuthor = comment && this.user_id === comment.user_id;
      if (isAuthor || !comment) {
        this.$set(this.is_visible, 'comment_dialog', true);
        this.deletedComment = comment;
      }
    },
    confirmCommentDeletion() {
      if (this.deletedComment) {
        if (!this.requestSent) {
          this.requestSent = true;
          const formData = new FormData();
          this.$set(this.is_visible, 'comment_dialog', false);
          formData.set('module', MODULE.HRPAC_COMMENTS);
          formData.set('record', this.deletedComment.id);
          formData.set('action', ACTION.DELETE);

          this.$axios
            .post('index.php', formData)
            .then(() => {
              this.deletedComment = null;
              location.reload();
            })
            .catch(err =>
              this.catchError(
                err,
                'Возникла ошибка при удалении комментария',
                'comment remove'
              )
            )
            .finally(() => (this.requestSent = false));
        }
      }
    }
  },
  components: { Dialog, SComment, SCommentForm }
};
</script>
