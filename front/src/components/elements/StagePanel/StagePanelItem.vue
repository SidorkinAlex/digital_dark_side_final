<template>
  <div class="stages-panel__dropdown">
    <el-tooltip content="Срок задачи заканчивается" placement="top-start">
      <label v-if="stage" class="stages-panel__item" :style="stage.styles">
        <i class="el-icon-warning"></i>
      </label>
    </el-tooltip>
  </div>
</template>

<script>
export default {
  props: {
    stage: {
      type: Object
    }
  }
};
</script>
