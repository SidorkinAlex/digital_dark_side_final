<template>
  <div class="description-tab">
    <div class="label-value">
      <span class="label-value__label">Цех</span>
      <span class="label-value__value">
        {{ data.digit_workshop_name.value }}
      </span>
    </div>
    <div class="label-value">
      <span class="label-value__label">Участок</span>
      <span class="label-value__value">
        {{ data.digit_section_name.value }}
      </span>
    </div>
    <div class="label-value">
      <span class="label-value__label">Корпус</span>
      <span class="label-value__value">
        {{ data.digit_block_name.value }}
      </span>
    </div>
    <div class="label-value">
      <span class="label-value__label">Описание</span>
      <span
        class="label-value__value"
        v-html="formatHtml(data.description.value)"
      ></span>
    </div>
  </div>
</template>

<script>
import { mixin } from '@/utils/mixins';
import './description.scss';
export default {
  mixins: [mixin],
  props: {
    data: {
      type: Object
    }
  }
};
</script>
