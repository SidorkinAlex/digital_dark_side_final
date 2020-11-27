<template>
  <el-checkbox
    v-model="localModel"
    :name="field.name"
    true-label="1"
    false-label="0"
    :required="field.required"
    @change.native="$emit('set-value', field.name, localModel)"
  ></el-checkbox>
</template>
<script>
import { editView } from '@/utils/mixins';
export default {
  mixins: [editView],
  props: {
    field: Object,
    model: Boolean
  },
  data() {
    return {
      localModel: this.model
    };
  }
};
</script>
