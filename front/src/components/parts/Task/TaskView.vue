<template>
  <div class="task-view">
    <Dialog
      v-if="is_visible.document_dialog"
      :is_visible="is_visible.document_dialog"
      title="Вы действительно хотите удалить этот документ?"
      @ok-callback="removeDocument(deletedDoc)"
      @cancel-callback="closeDialog('document_dialog', null, 'deletedDoc')"
    >
    </Dialog>
    <Dialog
      v-if="is_visible.comment_dialog"
      title="Вы действительно хотите удалить этот комментарий?"
      :is_visible="is_visible.comment_dialog"
      @ok-callback="confirmCommentDeletion"
      @cancel-callback="closeDialog('comment_dialog', null, 'deletedComment')"
    >
    </Dialog>
    <div class="control-panel">
      <div class="inline-buttons">
        <el-button type="primary" @click="editRoute">
          <span>Править</span>
        </el-button>
      </div>
      <stage-panel :module="module" :stages="tasks"></stage-panel>
      <form id="task-view" ref="form" enctype="multipart/form-data">
        <task-item
          type="card"
          :mod="mod"
          :task="data"
          :wide="true"
          :id="data.id.value"
          :color="customColors"
          :tasks-data="tasksData"
          :vacancy_statuses="vacancy_statuses"
        ></task-item>
      </form>
      <div class="tags">
        <el-button
          plain
          class="button-new-tag"
          size="mini"
          :disabled="Boolean(loading)"
          @click="visibleTagForm = !visibleTagForm"
        >
          {{ visibleTagForm ? '– Скрыть' : '+ Новый тег' }}
        </el-button>
        <div class="saved-tags" :v-if="addedTags.length">
          <el-tag
            v-for="{ tag_id, tag_name } in addedTags"
            :key="tag_id"
            closable
            :effect="hasMyTag(tag_id) ? 'dark' : 'light'"
            type="primary"
            size="mini"
            @close="removeTag($event, tag_id)"
          >
            {{ tag_name }}
          </el-tag>
        </div>

        <el-collapse v-model="activeCollapse" accordion>
          <el-collapse-item name="1">
            <el-form
              ref="tagForm"
              :model="tagForm"
              :rules="rules"
              size="mini"
              class="tag-form"
            >
              <el-form-item prop="tag_id">
                <el-select
                  v-model="tagForm.tag_id"
                  @input.native="handleChangeTag($event)"
                  @change="tagAction = 'addTagToCandidate'"
                  @visible-change="handleBlur"
                  placeholder="Наименование тега"
                  filterable
                  multiple
                >
                  <el-option
                    v-for="{ id, name } in tagsList"
                    :key="id"
                    :label="name"
                    :value="id"
                  ></el-option>
                  <p slot="empty" class="el-select-dropdown__empty">
                    {{ noTagMatches }}
                  </p>
                  <div class="add-tag-btn">
                    <el-form-item prop="my_tag">
                      <el-radio-group
                        v-model="tagForm.my_tag"
                        @change="createTag"
                      >
                        <el-radio label="0">Создать общий тег</el-radio>
                        <el-radio label="1">Создать личный тег</el-radio>
                      </el-radio-group>
                    </el-form-item>
                  </div>
                </el-select>
              </el-form-item>
              <el-button
                type="primary"
                @click="addTag"
                size="mini"
                v-loading="tagLoading"
                :disabled="!tagForm.tag_id || !tagForm.tag_id.length"
                >Добавить</el-button
              >
            </el-form>
          </el-collapse-item>
        </el-collapse>
        <form v-show="false" id="tagForm">
          <input
            v-for="(val, key) in tagParams"
            :key="key"
            type="hidden"
            :name="key"
            :value="val"
          />
          <input
            v-if="tagAction === 'addTagToCandidate'"
            type="hidden"
            name="candidate_id"
            :value="data.id.value"
          />
          <div v-if="tagAction === 'addTagToCandidate'">
            <input
              type="hidden"
              v-for="tag in tagForm.tag_id"
              :key="tag"
              name="tag_id[]"
              :value="tag"
            />
          </div>
          <input
            v-if="tagAction === 'createTag'"
            type="hidden"
            name="tag_name"
            :value="tagForm.tag_name"
          />
          <input
            v-if="tagAction === 'createTag'"
            type="hidden"
            name="my_tag"
            :value="tagForm.my_tag"
          />
        </form>
      </div>
      <div class="task-view__main candidate-form">
        <div class="task-view__info">
          <el-tabs v-model="activeInfo">
            <el-tab-pane label="Описание" name="description">
              <div class="label-value">
                <span class="label-value__label">Цех</span>
                <span class="label-value__value">
                  {{ data.digit_workshop_name.value }}
                </span>
              </div>
              <div class="label-value">
                <span class="label-value__label">Участок</span>
                <span class="label-value__value">
                  {{ data.digit_section_name.value }}
                </span>
              </div>
              <div class="label-value">
                <span class="label-value__label">Корпус</span>
                <span class="label-value__value">
                  {{ data.digit_block_name.value }}
                </span>
              </div>
              <div class="label-value">
                <span class="label-value__label">Описание</span>
                <span
                  class="label-value__value"
                  v-html="formatHtml(data.description.value)"
                ></span>
              </div>
            </el-tab-pane>
            <el-tab-pane label="Документы" name="documents">
              <form
                v-if="data.id.value"
                id="upload-doc"
                enctype="multipart/form-data"
              >
                <Upload
                  name="filename_file"
                  uploadText="
                  <i class='el-icon-upload'></i>
                  Перетащите файлы, чтобы прикрепить или <em>обзор</em>
                "
                  @upload-file="uploadDocument"
                  :file="filename_file"
                  :multiple="true"
                  :show-fiччle-list="false"
                  v-loading="addFile_loading"
                  class="upload-doc"
                ></Upload>
              </form>
              <div
                class="candidate-view__documents"
                v-loading="removeFile_loading"
              >
                <div
                  v-for="doc in all_documents_list"
                  :key="doc.id.value"
                  class="candidate-view__document"
                >
                  <span>
                    <a
                      :href="
                        `/index.php?preview=yes&entryPoint=download&id=${doc.id.value}&type=Documents`
                      "
                    >
                      <i class="el-icon-paperclip"></i>
                      {{ doc.filename.value }}
                    </a>
                  </span>
                  <span>{{ doc.active_date.value }}</span>
                  <span :data-id="doc.created_by.value">
                    {{ doc.created_by_name.value }}
                  </span>
                  <div class="candidate-view__doc-btns" v-if="doc.id.value">
                    <a
                      :href="
                        `/index.php?entryPoint=download&id=${doc.id.value}&type=Documents`
                      "
                      class="el-icon el-icon-download"
                      title="Скачать"
                    >
                    </a>
                    <a
                      href="javascript:void(0)"
                      v-if="user_id === doc.created_by.value"
                      class="el-icon el-icon-delete"
                      title="Удалить"
                      @click="showDocumentDialog(doc)"
                    >
                    </a>
                  </div>
                </div>
                <div
                  class="candidate-view__no-docs"
                  v-if="!all_documents_list.length"
                >
                  Ничего не обнаружено
                </div>
              </div>
            </el-tab-pane>
            <el-tab-pane label="Ответственные" name="subtasks">
              test data
            </el-tab-pane>
          </el-tabs>
        </div>
        <div class="task-view__comments">
          <el-tabs v-model="activeComments">
            <div class="inline-buttons stage-form__inline-buttons">
              <el-button
                class="inline-buttons__btn"
                type="primary"
                @click="is_visible.comment_form = true"
                >Комментарий</el-button
              >
            </div>
            <el-tab-pane label="Комментарии" name="comments">
              <s-comment-form
                :form-visible="is_visible.comment_form"
                @set-form-visible="showCommentForm"
                :edited-data="editedComment"
                @save-comment-form="saveCommentForm"
                :loader="saveComment_loading"
              ></s-comment-form>
              <s-comment
                class="candidate-view__comments__s-comment"
                v-for="(comment, i) in comments"
                :data="comment"
                :key="`${i}_${comment.date}`"
                :user_id="user_id"
                @delete-comment="deleteComment"
                @set-form-visible="showCommentForm"
              ></s-comment>
            </el-tab-pane>
          </el-tabs>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mixin } from '@/utils/mixins';
import { MODULE, FIELD /*, BUTTON,*/ } from '@/utils/constants';
import TaskItem from 'Parts/Task/TaskItem';
import StagePanel from 'Elements/StagePanel/StagePanel';
import SComment from 'Elements/Comment/SComment';
import SCommentForm from 'Elements/Comment/SCommentForm';
// import tasksData from './tasksMockData';
import Dialog from 'Elements/Dialog/Dialog';
import Upload from 'Elements/Upload/Upload';
// import CommentsData from './CommentDataMock.json';
// import DocumentsData from './DocumentDataMock.json';
export default {
  mixins: [mixin],
  props: {
    data: Object,
    mod: Object,
    dateFormat: Object,
    user_id: String,
    tags_list: Array
  },
  data() {
    return {
      module: MODULE.DIGIT_TASK,
      tasks: [], //tasksData,
      FIELD,
      loading: false,
      activeInfo: 'description',
      activeComments: 'comments',
      editedComment: null,
      deletedComment: null,
      comments: [],
      activeCollapse: '',
      deletedDoc: null,
      filename_file: [],
      all_documents_list: [],
      removeFile_loading: false,
      addFile_loading: false,
      saveComment_loading: false,
      visibleTagForm: false,
      visibleTagBtn: false,
      tagParams: {
        module: 'HRPAC_TAGS',
        jsqon_return: 1,
        to_pdf: true
      },
      tags: [],
      addedTags: [],
      tagForm: {},
      customColors: [
        { color: '#F56C6C', percentage: 30 },
        { color: '#e6a23c', percentage: 40 },
        { color: '#e6a23c', percentage: 60 },
        { color: '#409EFF', percentage: 80 },
        { color: '#67C23A', percentage: 100 }
      ],
      is_visible: {
        comment_form: false,
        document_dialog: false
      }
    };
  },
  created() {
    this.tags = [...this.tags_list];

    this.$axios
      .get('index.php', {
        params: {
          module: this.module,
          action: 'get_subpanel_json_data',
          subpanel: 'hrpac_comments',
          record: this.data.id.value,
          to_pdf: true,
          sort_by: 'date_entered',
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
      );
    // получение списка тэгов кандидата
    this.$axios
      .get('index.php', {
        params: {
          module: 'HRPAC_TAGS',
          action: 'getCandidatesTags',
          candidate_id: this.data.id.value,
          jsqon_return: 1,
          to_pdf: true
        }
      })
      .then(resp => (this.addedTags = [...resp.data]))
      .catch(err =>
        this.catchError(
          err,
          'Возникла ошибка загрузки списка тегов кандидата',
          'get tags'
        )
      )
      .finally(() => (this.loading = false));
    this.updateDocumentsList();
  },
  computed: {
    noTagMatches() {
      return this.tag_id && Object.values(this.tag_id).length
        ? 'Не найдено...'
        : 'Справочник пуст';
    },
    tagsList() {
      return [...new Set(this.tags)].filter(
        tag => !this.addedTags.map(({ tag_id }) => tag_id).includes(tag.id)
      );
    }
  },
  methods: {
    editRoute() {
      location.href = this.editViewLink(this.module, this.data.id.value);
    },
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
    updateDocumentsList() {
      this.$axios
        .get('index.php', {
          params: {
            module: this.module,
            action: 'get_subpanel_json_data',
            record: this.data.id.value,
            subpanel: 'digit_task_documents_1',
            to_pdf: true
          }
        })
        .then(resp => (this.all_documents_list = resp.data.List))
        .catch(err =>
          this.catchError(
            err,
            'Возникла ошибка обновления списка загруженных документов',
            'documents loading'
          )
        )
        .finally(() => {
          this.removeFile_loading = false;
          this.addFile_loading = false;
        });
    },
    removeDocument(doc) {
      const isAuthor = doc && this.user_id === doc.created_by.value;
      this.removeFile_loading = true;
      this.$set(this.is_visible, 'document_dialog', false);

      if (isAuthor) {
        this.$axios
          .get('index.php', {
            params: {
              module: this.module,
              action: 'DeleteRelationship',
              record: this.data.id.value,
              return_action: 'DeleteRelationship',
              return_module: this.module,
              return_id: this.data.id.value,
              linked_field: 'digit_task_documents_1',
              linked_id: doc.id.value,
              refresh_page: 1,
              inline: 1,
              ajaxSubpanel: true
            }
          })
          .then(() => {
            this.deletedDoc = null;
            this.updateDocumentsList();
          })
          .catch(err =>
            this.catchError(
              err,
              'Возникла ошибка при удалении документа',
              'document remove'
            )
          );
      }
    },
    showDocumentDialog(doc) {
      this.deletedDoc = doc;
      this.$set(this.is_visible, 'document_dialog', true);
    },
    updateFormData(newId, formData) {
      formData.set('module', 'Documents');
      formData.set('record', newId);
      formData.set('action', 'Save');
      formData.set('relate_to', 'digit_task_documents_1');
      formData.set('relate_id', newId);
      formData.set('parent_type', this.module);
      formData.set('parent_id', newId);
      formData.set('revision', 1);
      formData.set('jsqon_return', 1);
      return formData;
    },
    saveCommentForm(comment, edited) {
      if (comment) {
        if (!this.requestSent) {
          this.requestSent = true;
          this.saveComment_loading = true;
          const formData = new FormData();
          formData.set('module', 'HRPAC_COMMENTS');
          formData.set('record', edited ? comment.id : '');
          formData.set('action', 'Save');
          formData.set('relate_to', 'HRPAC_COMMENTS');
          formData.set('relate_id', this.data.id.value);
          formData.set('parent_type', this.module);
          formData.set('parent_id', this.data.id.value);
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
              this.saveComment_loading = false;
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
          formData.set('module', 'HRPAC_COMMENTS');
          formData.set('record', this.deletedComment.id);
          formData.set('action', 'Delete');

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
    },
    uploadDocument(file) {
      const form = document.getElementById('upload-doc');
      let formData = new FormData(form);
      formData = this.updateFormData(this.data.id.value, formData);
      this.addFile_loading = true;

      if (file) {
        const { raw: blob, name } = file;
        formData.set('filename_file', blob, name);

        this.$axios
          .post('index.php', formData, {
            header: {
              'Content-Type': 'multipart/form-data'
            }
          })
          .then(() => {
            this.filename_file = [];
            this.updateDocumentsList();
          })
          .catch(err =>
            this.catchError(
              err,
              'Возникла ошибка при загрузке документа',
              'upload file'
            )
          );
      }
    },
    addTag() {
      this.tagAction = 'addTagToCandidate';
      if (this.tagForm.tag_id.length && !this.requestSent) {
        this.requestSent = true;
        this.tagLoading = true;
        const form = document.getElementById('tagForm');
        const formData = new FormData(form);
        formData.set('action', this.tagAction);

        this.$axios
          .post('index.php', formData)
          .then(resp => {
            if (resp.data && !resp.data.error) {
              this.tagForm.tag_id.forEach(tagId => {
                if (
                  !this.addedTags.filter(({ tag_id }) => tag_id === tagId)
                    .length
                ) {
                  const tag = this.tags.find(({ tag_id }) => tag_id === tagId);
                  const addedTag = {
                    tag_id: tag.id,
                    tag_name: tag.name,
                    my_tag: tag.my_tag
                  };
                  this.addedTags.push(addedTag);
                  this.$refs.tagForm.resetFields();
                }
              });
            } else throw 'Возникла ошибка при добавлении тегов';
          })
          .catch(err =>
            this.catchError(
              err,
              'Возникла ошибка при добавлении тегов',
              'add tag'
            )
          )
          .finally(() => {
            this.tagLoading = false;
            this.requestSent = false;
          });
      }
    },
    createTag() {
      this.tagAction = 'createTag';
      if (!this.requestSent) {
        this.requestSent = true;
        const form = document.getElementById('tagForm');
        const formData = new FormData(form);
        formData.set('action', this.tagAction);

        this.$axios
          .post('index.php', formData)
          .then(resp => {
            if (resp.data && !resp.data.error) {
              const id = resp.data[0].tag_id;
              const newTag = {
                id,
                my_tag: this.tagForm.my_tag,
                name: this.tagForm.tag_name
              };
              this.tags.push(newTag);
              this.tagForm.tag_id.push(id);
              this.$set(this.tagForm, 'tag_name', '');
              this.$set(this.tagForm, 'my_tag', '');
              this.visibleTagBtn = false;
            } else throw 'Возникла ошибка при создании тега';
          })
          .catch(err =>
            this.catchError(
              err,
              'Возникла ошибка при создании тега',
              'create tag'
            )
          )
          .finally(() => (this.requestSent = false));
      }
    },
    removeTag(e, id) {
      if (!this.requestSent) {
        this.requestSent = true;
        this.tagAction = 'removeTagFromCandidate';

        if (id) {
          const formData = new FormData();
          const el = e.target.closest('.el-tag');
          const loader = `<div class="el-loading-mask"><div class="el-loading-spinner"><svg viewBox="25 25 50 50" class="circular"><circle cx="50" cy="50" r="20" fill="none" class="path"></circle></svg></div></div>`;

          el.innerHTML += loader;
          formData.set('action', this.tagAction);
          formData.set('candidate_id', this.data.id.value);
          formData.set('tag_id', id);

          for (let key in this.tagParams) {
            formData.set(key, this.tagParams[key]);
          }

          this.$axios
            .post('index.php', formData)
            .then(resp => {
              if (resp.data && !resp.data.error) {
                this.tags = this.tags.filter(({ id: tagId }) => tagId !== id);
                this.addedTags = this.addedTags.filter(
                  ({ tag_id }) => tag_id !== id
                );

                if (this.tagForm.tag_id) {
                  this.$set(
                    this.tagForm,
                    'tag_id',
                    this.tagForm.tag_id.filter(tagId => tagId !== id)
                  );
                }
              } else throw 'Возникла ошибка при удалении тега';
            })
            .catch(err =>
              this.catchError(
                err,
                'Возникла ошибка при удалении тега',
                'remove tag'
              )
            )
            .finally(() => {
              this.requestSent = false;
              el.querySelector('.el-loading-mask').remove();
            });
        }
      }
    },
    hasMyTag(tagId) {
      return (
        this.tags.filter(({ id, my_tag }) => id === tagId && Number(my_tag))
          .length > 0
      );
    },
    handleChangeTag(e) {
      this.tagAction = 'createTag';
      this.$set(this.tagForm, 'tag_name', e.target.value);
      const query = !this.tags.find(({ name }) => name === e.target.value);
      this.visibleTagBtn =
        e.target.value && !this.tags_list.length ? query : false;
    },
    handleBlur(visible) {
      if (!visible) {
        this.$set(this.tagForm, 'tag_name', '');
        this.$set(this.tagForm, 'my_tag', '');
        this.visibleTagBtn = false;
        this.tagAction = 'addTagToCandidate';
      }
    }
  },
  watch: {
    visibleTagForm: function() {
      if (this.visibleTagForm) {
        this.activeCollapse = ['1'];
      } else {
        this.activeCollapse = '';
      }
    }
  },
  components: { TaskItem, StagePanel, SComment, SCommentForm, Upload, Dialog }
};
</script>
