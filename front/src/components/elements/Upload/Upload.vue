<template>
  <el-upload
    :name="name"
    :class="[
      'candidate-form__upload',
      name == 'photo_file' && uploaded ? 'uploaded' : ''
    ]"
    ref="upload"
    :action="''"
    :auto-upload="false"
    :list-type="listType"
    :disabled="disabled"
    drag
    :accept="accept"
    :limit="limit"
    :on-change="changeFile"
    :on-remove="removeFile"
    :multiple="multiple"
    :show-file-list="showFileList"
  >
    <div class="el-upload__text" v-html="uploadText"></div>
    <span v-if="isOversize" class="file-error">
      Размер загружаемого файла не должен превышать 28.61 Мб
    </span>
  </el-upload>
</template>

<script>
import './upload.scss';
export default {
  props: {
    file: {
      type: Object
    },
    accept: {
      type: String,
      default: ''
    },
    listType: {
      type: String,
      default: 'text'
    },
    uploadText: {
      type: String,
      default: ''
    },
    name: {
      type: String
    },
    limit: {
      type: [Number, String],
      default: ''
    },
    size: {
      type: Number,
      default: 3e7
    },
    multiple: {
      type: Boolean,
      default: false
    },
    showFileList: {
      type: Boolean,
      default: true
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      uploaded: false,
      isOversize: false
    };
  },
  methods: {
    changeFile() {
      const uploadFiles = this.$refs.upload.uploadFiles;
      const newFile = uploadFiles[uploadFiles.length - 1];
      const validSize = newFile.size < this.size;

      if (validSize) {
        this.isOversize = false;
        this.file.push(newFile);
        this.uploaded = true;

        if (this.name === 'filename_file') {
          this.$emit('upload-file', newFile);
        }
      } else {
        this.isOversize = true;
        this.$refs.upload.uploadFiles.pop();
      }
    },
    removeFile() {
      this.uploaded = false;
      this.isOversize = false;
      this.file.pop();
    }
  }
};
</script>
