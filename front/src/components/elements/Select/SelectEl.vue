<template>
  <el-select
    v-model="localModel"
    :name="field.name"
    :multiple="multiple"
    :multiple-limit="field[FIELD.PARAM.MULTIPLE_LIMIT] || 0"
    :placeholder="field.placeholder || ''"
    :filterable="field.type === FIELD.TYPE.RELATE"
    :required="field.required"
    no-match-text="Нет результатов поиска"
    @change="handleChange"
  >
    <el-option
      v-for="opt in options"
      :key="opt.id"
      :label="opt.name"
      :value="specialField ? opt.id : opt.name"
      :disabled="opt.disabled"
    >
      {{ opt.name }}
    </el-option>
  </el-select>
</template>
<script>
import { FIELD } from '@/utils/constants';
import { editView } from '@/utils/mixins';
export default {
  mixins: [editView],
  props: {
    field: Object,
    model: [String, Array],
    options: Array,
    // callback: Object,
  },
  data() {
    return {
      FIELD,
      localModel: this.model
    };
  },
  computed: {
    specialField() {
      return (
        this.selectType(this.field.type) &&
        (this.multiple ||
          this.field.type === FIELD.TYPE.CURRENCY_ID ||
          this.field.type === FIELD.TYPE.ENUM)
      );
    },
    multiple() {
      return this.field.isMultiSelect || this.field.multiple;
    },
    noMatches() {
      return this.options && this.options.length
        ? 'Не найдено...'
        : 'Справочник пуст';
    }
  },
  methods: {
    handleChange() {
      const { id_name, name, type } = this.field;
      this.$emit('set-value', name, this.localModel);

      if (
        !this.multiple &&
        type !== FIELD.TYPE.CURRENCY_ID &&
        type !== FIELD.TYPE.ENUM
      ) {
        this.$emit('change-option', name, id_name);
      } else {
        // this.callback.field.change(this.field, this.localModel);
      }
    }
  }
};
</script>
