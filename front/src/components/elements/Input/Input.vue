<template>
  <el-input
    v-model="localModel"
    :type="inputType"
    :required="field.required"
    :min="min"
    :max="max"
    :maxlength="Number(field.maxlength || field.len)"
    :name="field.name"
    :placeholder="field.placeholder"
    @change.native="$emit('set-value', field.name, localModel)"
    @input.native="$emit('set-value', field.name, localModel)"
  ></el-input>
</template>

<script>
import { editView } from '@/utils/mixins';
import { FIELD } from '@/utils/constants';
export default {
  mixins: [editView],
  props: {
    field: Object,
    model: String
  },
  data() {
    return {
      localModel: this.model
    };
  },
  computed: {
    inputType() {
      console.log(this.field);
      return this.field.type === FIELD.TYPE.INT
        ? 'number'
        : this.field.type === FIELD.TYPE.TEXT
        ? 'textarea'
        : 'text';
    },
    min() {
      const { validation } = this.field;
      return validation ? validation.min : '';
    },
    max() {
      const { validation } = this.field;
      return validation ? validation.max : '';
    }
  }
};
</script>
