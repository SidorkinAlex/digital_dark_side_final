<template>
  <div class="document-tab">
    <Dialog
      v-if="is_visible.document_dialog"
      :is_visible="is_visible.document_dialog"
      title="Вы действительно хотите удалить этот документ?"
      @ok-callback="removeDocument(deletedDoc)"
      @cancel-callback="closeDialog('document_dialog', null, 'deletedDoc')"
    >
    </Dialog>
    <form v-if="id" id="upload-doc" enctype="multipart/form-data">
      <Upload
        name="filename_file"
        uploadText="<i class='el-icon-upload'></i>Перетащите файлы, чтобы прикрепить или <em>обзор</em>"
        @upload-file="uploadDocument"
        :file="filename_file"
        :multiple="true"
        :show-file-list="false"
        v-loading="addFile_loading"
        class="document-tab__upload"
      ></Upload>
    </form>
    <div v-loading="removeFile_loading">
      <div
        v-for="doc in all_documents_list"
        :key="doc.id.value"
        class="document-tab__document"
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
        <div class="document-tab__btns" v-if="doc.id.value">
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
      <div class="document-tab__no-docs" v-if="!all_documents_list.length">
        Ничего не обнаружено
      </div>
    </div>
  </div>
</template>

<script>
import Upload from 'Elements/Upload/Upload';
import Dialog from 'Elements/Dialog/Dialog';
import { mixin } from '@/utils/mixins';
import { MODULE, ACTION, SUBPANEL } from '@/utils/constants';
import './document.scss';
const FormData = require('form-data');

export default {
  mixins: [mixin],
  props: {
    user_id: {
      type: String
    },
    module: {
      type: String
    },
    id: {
      type: String
    }
  },
  data() {
    return {
      all_documents_list: [],
      deletedDoc: null,
      filename_file: [],
      removeFile_loading: false,
      addFile_loading: false,
      is_visible: {
        document_dialog: false
      }
    };
  },
  created() {
    this.updateDocumentsList();
  },
  methods: {
    updateDocumentsList() {
      this.$axios
        .get('index.php', {
          params: {
            module: this.module,
            action: ACTION.GET_SUBPANEL_JSON_DATA,
            record: this.id,
            subpanel: SUBPANEL.DOCUMENTS,
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
          this.$emit('set-loading', false);
        });
    },
    showDocumentDialog(doc) {
      this.deletedDoc = doc;
      this.$set(this.is_visible, 'document_dialog', true);
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
              action: ACTION.DELETE_RELATIONSHIP,
              record: this.id,
              return_action: ACTION.DELETE_RELATIONSHIP,
              return_module: this.module,
              return_id: this.id,
              linked_field: SUBPANEL.DOCUMENTS,
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
    updateFormData(newId, formData) {
      formData.set('module', MODULE.DOCUMENTS);
      formData.set('record', newId);
      formData.set('action', ACTION.SAVE);
      formData.set('relate_to', SUBPANEL.DOCUMENTS);
      formData.set('relate_id', newId);
      formData.set('parent_type', this.module);
      formData.set('parent_id', newId);
      formData.set('revision', 1);
      formData.set('jsqon_return', 1);
      return formData;
    },
    uploadDocument(file) {
      const form = document.getElementById('upload-doc');
      let formData = new FormData(form);
      formData = this.updateFormData(this.id, formData);
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
    }
  },
  components: { Upload, Dialog }
};
</script>
