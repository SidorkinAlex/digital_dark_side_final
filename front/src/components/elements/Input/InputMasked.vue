<template>
  <the-mask
    v-model="localModel"
    type="text"
    :name="field.name"
    :required="field.required"
    class="el-input__inner"
    :placeholder="field.placeholder"
    :masked="false"
    :mask="['### ', '# ###', '## ###', '### ### ', '# ### ###', '## ### ###']"
    :value="(localModel = Number(localModel))"
  ></the-mask>
</template>
<script>
import { mixin, editView } from '@/utils/mixins';
import { mask, TheMask } from 'vue-the-mask';
export default {
  mixins: [mixin, editView],
  directives: { mask },
  props: {
    field: Object,
    model: String,
    callback: Object
  },
  data() {
    return {
      localModel: this.model
    };
  },
  components: { TheMask }
};
</script>
