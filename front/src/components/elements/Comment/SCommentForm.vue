<template>
  <el-form
    v-if="formVisible"
    ref="form"
    :model="form"
    :rules="rules"
    label-position="top"
    class="s-comment-form"
  >
    <text-editor
      name="text"
      label="Комментарий:"
      v-model="form.text"
      :max-length="1000"
      width="99%"
      @set-text-value="setTextValue"
    ></text-editor>
    <el-form-item class="control-panel" prop="to_recruits">
      <el-checkbox
        label="Виден только рекрутерам"
        name="to_recruits"
        :checked="Number(form.to_recruits)"
        v-model="form.to_recruits"
      ></el-checkbox>
      <div class="inline-buttons" v-loading="loader">
        <el-button type="primary" v-if="edited" @click="saveComment">
          Сохранить
        </el-button>
        <el-button type="primary" v-else @click="saveComment">
          Добавить
        </el-button>
        <el-button @click="cancelComment">
          Отмена
        </el-button>
      </div>
    </el-form-item>
  </el-form>
</template>

<script>
import TextEditor from 'Elements/TextEditor/TextEditor';

export default {
  props: {
    formVisible: {
      type: Boolean,
      default: false
    },
    editedData: {
      type: Object
    },
    loader: {
      type: Boolean
    }
  },
  data() {
    return {
      form: {
        text: '',
        to_recruits: false
      },
      rules: {
        text: [
          {
            required: true,
            message: 'Необходимо указать сообщение',
            trigger: 'change'
          }
        ]
      },
      edited: false
    };
  },
  methods: {
    cancelComment() {
      this.$emit('set-form-visible', { flag: false });
      this.resetForm();
    },
    saveComment() {
      this.$refs.form.validate(valid => {
        if (valid) {
          this.$emit('save-comment-form', this.form, this.edited);
        } else {
          console.log('Поля невалидные!');
        }
      });
    },
    resetForm() {
      this.edited = false;
      this.$refs.form.resetFields();
      this.form = {
        comment: '',
        onlyRecruter: false
      };
    },
    setTextValue(text, name) {
      this.$set(this.form, name, text);
      this.$refs.form.validateField(name);
    }
  },
  watch: {
    editedData: function() {
      if (this.editedData && Object.keys(this.editedData).length) {
        this.edited = true;
        this.form = Object.assign({}, this.editedData);
      }
    }
  },
  components: { TextEditor }
};
</script>
