<template>
  <div class="stages-panel">
    <stage-panel-item
      v-for="(stage, key) in stages"
      :key="key"
      :stage="stage"
    ></stage-panel-item>
  </div>
</template>

<script>
import './stages-panel.scss';
import StagePanelItem from 'Elements/StagePanel/StagePanelItem';
export default {
  props: {
    module: {
      type: String
    },
    stages: {
      type: [Object, Array]
    }
  },
  components: { StagePanelItem }
};
</script>
