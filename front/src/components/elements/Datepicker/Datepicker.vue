<template>
  <el-date-picker
    v-model="localModel"
    :type="dateType"
    :name="field.name"
    :required="field.required"
    :placeholder="field.placeholder || ''"
    :format="dateFormat"
    :value-format="dateFormat"
    :picker-options="pickerOptions"
    @change="$emit('set-value', field.name, localModel)"
  ></el-date-picker>
</template>
<script>
import { mixin, editView } from '@/utils/mixins';
import { FIELD } from '@/utils/constants';
export default {
  mixins: [mixin, editView],
  props: {
    field: Object,
    model: String,
    datepicker: Object
  },
  data() {
    return {
      localModel: this.model || null,
      pickerOptions: {
        // disabledDate(time) {
        //   return time.getTime() > Date.now();
        // },
        firstDayOfWeek: 1
      }
    };
  },
  computed: {
    dateType() {
      return this.field.type === FIELD.TYPE.DATE ? 'date' : 'datetime';
    },
    dateFormat() {
      return this.dateType === 'date'
        ? this.datepicker.date
        : `${this.datepicker.date} ${this.datepicker.time}`;
    }
  }
};
</script>
