<template>
  <el-form-item
    v-if="field"
    class="row"
    :prop="field.name"
    :label="label"
    :label-width="label ? width : '0'"
  >
    <Select
      v-if="selectType(field.type)"
      :field="field"
      :model="model"
      :options="options"
      :callback="callback"
      :loading="loading"
      :visible-tag-btn="visibleTagBtn"
      @set-value="setValue"
      @change-option="changeOption"
      @add-new-skill="addNewTag"
    ></Select>
    <InputMasked
      v-else-if="field.type === FIELD.TYPE.DECIMAL"
      :field="field"
      :model="model"
      :callback="callback"
    ></InputMasked>
    <Datepicker
      v-else-if="isDateField(field.type)"
      type="date"
      :field="field"
      :model="model"
      :date-format="dateFormat"
    ></Datepicker>
    <Checkbox
      v-else-if="field.type === FIELD.TYPE.BOOL"
      :field="field"
      :model="model"
      @set-value="setValue"
    ></Checkbox>
    <Input v-else :field="field" :model="model" @set-value="setValue" />
  </el-form-item>
</template>
<script>
import { editView } from '@/utils/mixins';
import { FIELD } from '@/utils/constants';
import Select from 'Elements/Select/Select.vue';
import Input from 'Elements/Input/Input.vue';
import InputMasked from 'Elements/Input/InputMasked.vue';
import Datepicker from 'Elements/Datepicker/Datepicker.vue';
import Checkbox from 'Elements/Checkbox/Checkbox.vue';
export default {
  mixins: [editView],
  props: {
    field: Object,
    label: String,
    model: [String, Array, Object, Boolean],
    options: Array,
    callback: Object,
    visibleTagBtn: {
      type: Boolean,
      default: false
    },
    width: {
      type: String,
      default: 'unset'
    },
    loading: Boolean,
    dateFormat: Object
  },
  data() {
    return {
      FIELD
    };
  },
  methods: {
    changeOption(name, id) {
      this.$emit('change-option', name, id);
    },
    setValue(name, val) {
      this.$emit('set-value', name, val);
    },
    addNewTag(name) {
      this.$emit('add-new-tag', name);
    }
  },
  components: { Select, Input, InputMasked, Datepicker, Checkbox }
};
</script>
