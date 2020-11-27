<template>
  <div
    class="s-comment"
    @mouseenter="showIcons = true"
    @mouseleave="showIcons = false"
  >
    <div class="s-comment__avatar">
      <el-avatar :src="avatar" :fit="'scale-down'"></el-avatar>
    </div>
    <div class="s-comment__main">
      <div
        class="s-comment__icons"
        v-if="showIcons && user_id === data.user_id"
      >
        <a
          class="el-icon el-icon-delete"
          @click="$emit('delete-comment', data)"
        ></a>
        <a
          class="el-icon el-icon-edit"
          @click="$emit('set-form-visible', { flag: true, comment: data })"
        ></a>
      </div>
      <div class="s-comment__name">
        <el-link
          :type="'primary'"
          :href="'#'"
          target="_blank"
          class="s-comment__name-link"
          >{{ data.name }}</el-link
        >
      </div>
      <div
        ref="comment"
        :class="[
          's-comment__value',
          isFullText ? 's-comment__value_height_full' : ''
        ]"
      >
        <div v-html="data.text"></div>
        <span v-if="visibleTextHelp" class="s-comment__link" @click="showText">
          Показать весь текст
        </span>
      </div>
      <div class="s-comment__bottom">
        <span class="s-comment__date">{{ data.date_entered }}</span>
        <font-awesome-icon
          v-if="onlyRecruiter"
          :icon="['fas', 'eye-slash']"
          size="x"
          class="s-comment__view"
        ></font-awesome-icon>
      </div>
    </div>
  </div>
</template>

<script>
import { detailView } from '@/utils/helpers';
export default {
  props: {
    user_id: {
      type: String
    },
    data: {
      type: Object
    }
  },
  data() {
    return {
      no_profile_photo: '/custom/include/img/avatar.jpg',
      showIcons: false,
      isFullText: false,
      visibleTextHelp: false
    };
  },
  mounted() {
    this.visibleTextHelp = this.visibleTextElem;
  },
  computed: {
    avatar() {
      return this.data.avatar ? this.data.avatar : this.no_profile_photo;
    },
    userLink() {
      return this.data.user_id ? detailView('Users', this.data.user_id) : '#';
    },
    visibleTextElem() {
      return (
        (this.$refs.comment.offsetHeight == 81 ||
          this.data.text.length > 120) &&
        !this.isFullText
      );
    },
    onlyRecruiter() {
      return Boolean(+this.data.to_recruits);
    }
  },
  methods: {
    showText() {
      this.isFullText = true;
      this.visibleTextHelp = false;
    }
  }
};
</script>
