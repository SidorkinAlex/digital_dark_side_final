<template>
  <el-table
    v-if="assignedList.length"
    ref="assignedTable"
    :data="assignedList"
    size="mini"
    align="left"
  >
    <!-- <el-table-column :prop="assignedUsers" label="Ответственные"></el-table-column> -->
    <el-table-column
      v-for="(col, i) in assignedFields"
      :key="`${col.id}_${i}`"
      :property="col.id"
      :label="col.name"
      header-align="left"
      sortable
      :sort-orders="['ascending', 'descending']"
    >
      <template slot-scope="scope">
        <el-tag
          v-if="col.id === 'typical_responses' && scope.row.typical_responses"
          :type="reactions(scope.row[col.id])"
          disable-transitions
        >
          {{ scope.row[col.id] }}
        </el-tag>
        <span v-else>{{ scope.row[col.id] }}</span>
      </template>
    </el-table-column>
  </el-table>
  <div v-else class="empty">
    <span>Нет данных</span>
  </div>
</template>

<script>
import { mixin } from '@/utils/mixins';
import { ACTION, SUBPANEL } from '@/utils/constants';
export default {
  mixins: [mixin],
  props: {
    data: Object,
    module: Object,
    taskId: String
  },
  data() {
    return {
      assignedList: [],
      assignedFields: []
      // assignedUsers: []
    };
  },
  created() {
    this.$axios
      .get('index.php', {
        params: {
          module: this.module,
          action: ACTION.GET_SUBPANEL_JSON_DATA,
          subpanel: SUBPANEL.ASSIGNED_TASK_INFO,
          record: this.taskId,
          to_pdf: true,
          json_return: 1
        }
      })
      .then(resp => {
        if (resp.data && !resp.data.error) {
          const { List, MOD } = resp.data;
          const tableCols = [
            'executor_name',
            'type',
            'typical_responses',
            'description'
          ];

          if (List.hasOwnProperty('length')) {
            List.forEach((item, i) => {
              const opt = {};
              for (let key in item) {
                const { value, vname, options } = item[key];
                opt[key] = value && options ? options[value] : value;

                if (tableCols.includes(key) && !i) {
                  this.assignedFields.push({
                    id: key,
                    name: MOD[vname]
                  });
                }
              }
              this.assignedList.push(opt);
            });
          }
        }
      })
      .catch(err =>
        this.catchError(
          err,
          'Возникла ошибка загрузки списка ответственных в задаче',
          'assigned by task loading'
        )
      )
      .finally(() => this.$emit('set-loading', false));
  },
  methods: {
    reactions(val) {
      const colors = {
        accepted: 'success',
        done: 'primary',
        taken_to_work: 'warning'
      };
      return colors[val];
    }
  }
};
</script>
