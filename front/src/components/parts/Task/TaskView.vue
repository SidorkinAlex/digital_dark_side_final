<template>
  <div class="task-view">
    <div class="control-panel">
      <div class="inline-buttons">
        <el-link :href="editRoute">
          <el-button type="primary">
            <span>Править</span>
          </el-button>
        </el-link>
      </div>
      <!-- <stage-panel :module="module" :stages="tasks"></stage-panel> -->
      <form id="task-view" ref="form" enctype="multipart/form-data">
        <task-item
          type="card"
          :mod="mod"
          :task="data"
          :wide="true"
          :id="data.id.value"
          :color="customColors"
          :tasks-data="tasksData"
          :vacancy_statuses="vacancy_statuses"
        ></task-item>
      </form>
      <tag-form :task-id="data.id.value" :options="tags_list"></tag-form>
      <div class="task-view__main">
        <div class="task-view__info">
          <el-tabs
            v-model="activeSubpanel.left"
            @tab-click="tabClick"
            v-loading="tabLoading"
          >
            <el-tab-pane
              label="Описание"
              name="description"
              class="task-fields"
            >
              <description-tab :data="data"></description-tab>
            </el-tab-pane>
            <el-tab-pane label="Документы" name="documents">
              <document-tab
                v-if="tabDataLoaded.documents"
                :user_id="user_id"
                :id="data.id.value"
                :module="module"
                @set-loading="setTabLoading"
              ></document-tab>
            </el-tab-pane>
            <el-tab-pane label="Ответственные в задаче" name="assigned">
              <assigned-users
                v-if="tabDataLoaded.assigned"
                :data="data"
                :task-id="data.id.value"
                :module="module"
                @set-loading="setTabLoading"
              ></assigned-users>
            </el-tab-pane>
            <el-tab-pane label="Подзадачи" name="subtasks">
              <subtask-tab
                v-if="tabDataLoaded.subtasks"
                :id="data.id.value"
                :module="module"
                @set-loading="setTabLoading"
              ></subtask-tab>
            </el-tab-pane>
          </el-tabs>
        </div>
        <div class="task-view__comments">
          <el-tabs v-model="activeSubpanel.right">
            <el-tab-pane label="Комментарии" name="comments">
              <comment-tab
                :user_id="user_id"
                :id="data.id.value"
                :module="module"
                @set-loading="setTabLoading"
              ></comment-tab>
            </el-tab-pane>
          </el-tabs>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mixin } from '@/utils/mixins';
import { MODULE, FIELD } from '@/utils/constants';
import TagForm from 'Elements/Tag/TagForm';
import TaskItem from 'Parts/Task/TaskItem';
import DocumentTab from 'Elements/Tab/DocumentTab';
import SubtaskTab from 'Elements/Tab/SubTaskTab';
import CommentTab from 'Elements/Tab/CommentTab';
import DescriptionTab from 'Elements/Tab/DescriptionTab';
import AssignedUsers from 'Elements/Tab/AssignedUsers';
export default {
  mixins: [mixin],
  props: {
    data: Object,
    mod: Object,
    dateFormat: Object,
    user_id: String,
    tags_list: Array
  },
  data() {
    return {
      module: MODULE.DIGIT_TASK,
      tasks: [], //tasksData,
      FIELD,
      loading: false,
      activeSubpanel: {
        left: 'description',
        right: 'comments'
      },
      tabLoading: false,
      tabDataLoaded: {},
      customColors: [
        { color: '#F56C6C', percentage: 30 },
        { color: '#e6a23c', percentage: 40 },
        { color: '#e6a23c', percentage: 60 },
        { color: '#409EFF', percentage: 80 },
        { color: '#67C23A', percentage: 100 }
      ]
    };
  },
  computed: {
    editRoute() {
      return this.editViewLink(this.module, this.data.id.value);
    },
  },
  methods: {
    setTabLoading(bool) {
      this.tabLoading = bool;
    },
    tabClick(tab) {
      const mustLazyLoaded = ['documents', 'assigned', 'subtasks'];
      if (mustLazyLoaded.includes(tab.name) && !this.tabDataLoaded[tab.name]) {
        this.tabLoading = true;
        this.$set(this.tabDataLoaded, tab.name, true);
      }
    }
  },
  components: {
    TagForm,
    TaskItem,
    DescriptionTab,
    DocumentTab,
    SubtaskTab,
    CommentTab,
    AssignedUsers
  }
};
</script>
