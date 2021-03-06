<template>
  <section class="task-item">
    <div class="task-item__top" @click.alt="wideInfo = !wideInfo">
      <div class="task-item__left">
        <div class="task-item__left-top">
          <div class="task-item__title">
            <el-tooltip
              :content="`Сложность: ${getValue('complexity', '')}`"
              placement="top-start"
            >
              <level :level="levels[task.complexity.value]"></level>
            </el-tooltip>
            <el-tooltip :content="task.name.value" placement="top-start">
              <component
                :is="task.name.link ? 'el-link' : 's-text'"
                :type="'primary'"
                :href="detailRoute"
                :underline="false"
                class="task-item__title-link"
              >
                {{ task.name.value }}
              </component>
            </el-tooltip>
          </div>
        </div>
        <div class="task-item__left-bottom">
          <div class="task-item__bottom-item">
            <el-tooltip :content="`Приоритет: ${getValue('priority', '')}`" placement="top-start">
              <span :class="priorities[task.priority.value]"></span>
            </el-tooltip>
            <el-tooltip content="Статус задачи" placement="top-start">
              <el-tag :type="colorStatus()" :effect="effect()" size="small"
                >{{ getValue('status', '') }}
              </el-tag>
            </el-tooltip>
            <el-tooltip content="Тип задачи" placement="top-start">
              <el-tag
                v-if="task.type.value"
                :type="colorType"
                :effect="effect()"
                size="small"
              >
                {{ getValue('type', '') }}
              </el-tag>
            </el-tooltip>
          </div>
        </div>
        <div class="task-item__bottom-item task-item__dates">
          <span>По плану до: {{ formatDate('date_plan') }}</span>
          <span>По факту до: {{ formatDate('date_fact') }}</span>
        </div>
      </div>
      <div class="task-item__medium">
        <div
          class="task-item__medium-value"
          v-for="item in mainInfo"
          :key="`${item.value}`"
        >
          <div class="task-item__label">
            <b>{{ item.label }}</b>
          </div>
          <div class="task-item__value" v-html="item.value"></div>
        </div>
      </div>
      <!-- <div class="task-item__right"></div> -->
      <div class="task-item__button-down" @click="wideInfo = !wideInfo">
        <el-button circle :icon="wideIcon" :size="'mini'"></el-button>
      </div>
    </div>
    <div class="task-item__bottom" v-if="wideInfo">
      <section class="cd-horizontal-timeline">
        <timeline></timeline>
      </section>
    </div>
  </section>
</template>

<script>
import SText from 'Elements/Text/Text';
import Level from 'Elements/Level/Level';
import Timeline from 'Elements/Timeline/Timeline';
import { mixin } from '@/utils/mixins';
const statuses = {
  appointed: 'success',
  canceled: 'info',
  completed: 'warning',
  in_work: 'danger',
  new: 'primary',
  on_pause: ''
};
const types_colors = {
  event: 'primary',
  process: 'warning'
};
export default {
  mixins: [mixin],
  name: 'TaskItem',
  props: {
    task: {
      type: Object
    },
    mod: Object,
    data: {
      type: Object,
      default: null
    },
    wide: {
      type: Boolean,
      default: false
    },
    type: {
      type: String,
      default: 'table'
    }
  },
  components: {
    SText,
    Level,
    Timeline
  },
  data() {
    return {
      wideInfo: this.wide,
      customColors: [
        { color: '#F56C6C', percentage: 30 },
        { color: '#e6a23c', percentage: 40 },
        { color: '#e6a23c', percentage: 60 },
        { color: '#409EFF', percentage: 80 },
        { color: '#67C23A', percentage: 100 }
      ],
      levels: {
        low: '1',
        middle: '2',
        high: '3'
      },
      priorities: {
        'low' : 'el-icon-bottom',
        'middle': 'el-icon-d-arrow-left top',
        'high': 'el-icon-warning'
      },
      bodyKeys: [
        'parent_name',
        'digit_tasks_class',
        'task_manager_name',
        'assigned_user_name'
      ],
      arrowDown: '',
      status: this.task.status.value
    };
  },
  computed: {
    colorType() {
      const type = this.getValue('type', '');
      return types_colors[type] || '';
    },
    detailRoute() {
      return location.href;
    },
    mainInfo() {
      let body = [];
      let bodyKeys = this.bodyKeys;

      bodyKeys.forEach(key => {
        const { value, options, vname } = this.task[key];
        const label = this.mod[vname] || '';
        const val = options ? options[value] : value;
        body.push({ value: val, label });
      });
      return body;
    },
    wideIcon() {
      return this.wideInfo ? 'el-icon-arrow-up' : 'el-icon-arrow-down';
    }
  },
  methods: {
    formatDate(name) {
      return this.task[name].value.split(' ')[0];
    },
    colorStatus(name) {
      let status = name || this.task.status.value;
      status = Object.keys(statuses).find(i => i === status);
      return statuses[status];
    },
    effect(name) {
      const status = name || this.task.status.value;
      if (status === 'new') {
        return 'plain';
      } else {
        return 'dark';
      }
    },
    getValue(name, defaultVal) {
      const { value, options } = this.task[name];
      return options ? options[value] : value || defaultVal;
    },
    checkboxClasses(name) {
      return [
        'el-checkbox__input',
        Number(this.task[name].value) ? 'is-checked' : ''
      ];
    }
  }
};
</script>
