<template>
  <el-dialog
    width="40%"
    :title="title"
    :visible.sync="is_visible"
    @close="$emit('cancel-callback')"
  >
    <div slot="footer" class="dialog-footer">
      <el-button v-on:click="$emit('cancel-callback')">
        Отмена
      </el-button>
      <el-button type="primary" v-on:click="$emit('ok-callback')">
        Ок
      </el-button>
    </div>
  </el-dialog>
</template>

<script>
export default {
  props: {
    is_visible: {
      type: Boolean
    },
    title: {
      type: String
    }
  }
};
</script>
