<template>
  <div class="task-edit">
    <div class="control-panel" v-scroll="handleScroll">
      <div v-loading="loading">
        <el-button type="primary" @click="saveForm">
          <span>Сохранить</span>
        </el-button>
        <el-button type="button" @click="resetForm">
          <span>Закрыть</span>
        </el-button>
      </div>
      <el-form
        v-if="fields"
        id="task-edit"
        ref="form"
        :model="form"
        :rules="rules"
        status-icon
        class="task-form demo-ruleForm"
        label-width="auto"
        size="small"
      >
        <div class="task-form__wrapper">
          <div class="task-form__section">
            <h3>Основная информация</h3>
            <el-card class="el-form-item sub-field">
              <el-form-item
                class="row"
                prop="name"
                :label="mod[fields.name.vname]"
              >
                <InputEl
                  :field="fields.name"
                  :model="form.name"
                  @set-value="setValue"
                ></InputEl>
              </el-form-item>
              <el-form-item
                class="row"
                prop="source"
                :label="mod[fields.source.vname]"
              >
                <SelectEl
                  :model="form.source"
                  :field="fields.source"
                  :options="options.source"
                  @change-option="handleChange"
                  @set-value="setValue"
                >
                  <!-- <el-option
                    v-for="opt in options.source"
                    :key="opt.id"
                    :label="opt.name"
                    :value="opt.name"
                    :disabled="opt.disabled"
                  >
                    {{ opt.name }}
                  </el-option> -->
                </SelectEl>
              </el-form-item>
              <el-form-item
                class="row"
                prop="digit_project_name"
                :label="mod[fields.digit_project_name.vname]"
              >
                <SelectEl
                  :model="form.digit_project_name"
                  :field="fields.digit_project_name"
                  :options="options.digit_project_name"
                  @change-option="handleChange"
                  @set-value="setValue"
                >
                  <!-- <el-option
                    v-for="opt in options.digit_project_name"
                    :key="opt.id"
                    :label="opt.name"
                    :value="specialField ? opt.id : opt.name"
                    :disabled="opt.disabled"
                  >
                    {{ opt.name }}
                  </el-option> -->
                </SelectEl>
              </el-form-item>
            </el-card>
          </div>
          <div class="task-form__section">
            <h3>Участники</h3>
            <div class="el-form-item sub-field">
              <el-form-item
                class="row"
                prop="assigned_user_name"
                :label="mod[fields.assigned_user_name.vname]"
              >
                <SelectEl
                  :model="form.assigned_user_name"
                  :field="fields.assigned_user_name"
                  :options="options.assigned_user_name"
                  @change-option="handleChange"
                  @set-value="setValue"
                >
                  <!-- <el-option
                    v-for="opt in options.assigned_user_name"
                    :key="opt.id"
                    :label="opt.name"
                    :value="specialField ? opt.id : opt.name"
                    :disabled="opt.disabled"
                  >
                    {{ opt.name }}
                  </el-option> -->
                </SelectEl>
              </el-form-item>
              <!-- <el-form-item
                class="row"
                prop="spectators_ids"
                :label="mod[fields.spectators_ids.vname]"
              >
                <SelectEl
                  :model="form.spectators_ids"
                  :field="fields.spectators_ids"
                  :placeholder="fields.spectators_ids.placeholder || ''"
                  :filterable="fields.spectators_ids.type === FIELD.TYPE.RELATE"
                  :required="fields.spectators_ids.required"
                  no-match-text="Нет результатов поиска"
                  @change-option="handleChange"
                >
                  <el-option
                    v-for="opt in options.spectators_ids"
                    :key="opt.id"
                    :label="opt.name"
                    :value="specialField ? opt.id : opt.name"
                    :disabled="opt.disabled"
                  >
                    {{ opt.name }}
                  </el-option>
                </SelectEl>
              </el-form-item> -->
              <!-- <el-form-item
                class="row"
                prop="digit_task_users_spectator"
                :label="mod[fields.digit_task_users_spectator.vname]"
              >
                <SelectEl
                  :model="form.digit_task_users_spectator"
                  :field="fields.digit_task_users_spectator"
                  :placeholder="
                    fields.digit_task_users_spectator.placeholder || ''
                  "
                  :filterable="
                    fields.digit_task_users_spectator.type === FIELD.TYPE.RELATE
                  "
                  :required="fields.digit_task_users_spectator.required"
                  no-match-text="Нет результатов поиска"
                  @change-option="handleChange"
                >
                  <el-option
                    v-for="opt in options.digit_task_users_spectator ||
                      []"
                    :key="opt.id"
                    :label="opt.name"
                    :value="specialField ? opt.id : opt.name"
                    :disabled="opt.disabled"
                  >
                    {{ opt.name }}
                  </el-option>
                </SelectEl>
              </el-form-item> -->
              <el-form-item
                class="row"
                prop="task_manager_name"
                :label="mod[fields.task_manager_name.vname]"
              >
                <SelectEl
                  :model="form.task_manager_name"
                  :field="fields.task_manager_name"
                  :options="options.task_manager_name"
                  @change-option="handleChange"
                  @set-value="setValue"
                >
                  <!-- <el-option
                    v-for="opt in options.task_manager_name"
                    :key="opt.id"
                    :label="opt.name"
                    :value="specialField ? opt.id : opt.name"
                    :disabled="opt.disabled"
                  >
                    {{ opt.name }}
                  </el-option> -->
                </SelectEl>
              </el-form-item>
            </div>
          </div>
          <div class="task-form__section">
            <h3>Детали задачи</h3>
            <el-card class="el-form-item sub-field">
              <el-form-item
                class="row"
                prop="digit_block_name"
                :label="mod[fields.digit_block_name.vname]"
              >
                <SelectEl
                  :model="form.digit_block_name"
                  :field="fields.digit_block_name"
                  :options="options.digit_block_name"
                  @change-option="handleChange"
                  @set-value="setValue"
                >
                  <!-- <el-option
                    v-for="opt in options.digit_block_name"
                    :key="opt.id"
                    :label="opt.name"
                    :value="specialField ? opt.id : opt.name"
                    :disabled="opt.disabled"
                  >
                    {{ opt.name }}
                  </el-option> -->
                </SelectEl>
              </el-form-item>
              <el-form-item
                class="row"
                prop="digit_section_name"
                :label="mod[fields.digit_section_name.vname]"
              >
                <SelectEl
                  :model="form.digit_section_name"
                  :field="fields.digit_section_name"
                  :options="options.digit_section_name"
                  @change-option="handleChange"
                  @set-value="setValue"
                >
                  <!-- <el-option
                    v-for="opt in options.digit_section_name"
                    :key="opt.id"
                    :label="opt.name"
                    :value="specialField ? opt.id : opt.name"
                    :disabled="opt.disabled"
                  >
                    {{ opt.name }}
                  </el-option> -->
                </SelectEl>
              </el-form-item>
              <el-form-item
                class="row"
                prop="digit_workshop_name"
                :label="mod[fields.digit_workshop_name.vname]"
              >
                <SelectEl
                  :model="form.digit_workshop_name"
                  :field="fields.digit_workshop_name"
                  :options="options.digit_workshop_name"
                  @change-option="handleChange"
                  @set-value="setValue"
                >
                  <!-- <el-option
                    v-for="opt in options.digit_workshop_name"
                    :key="opt.id"
                    :label="opt.name"
                    :value="specialField ? opt.id : opt.name"
                    :disabled="opt.disabled"
                  >
                    {{ opt.name }}
                  </el-option> -->
                </SelectEl>
              </el-form-item>
              <div class="el-form-item sub-field">
                <el-form-item
                  class="row"
                  prop="priority"
                  :label="mod[fields.priority.vname]"
                >
                  <SelectEl
                    :model="form.priority"
                    :field="fields.priority"
                    :options="options.priority"
                    @change-option="handleChange"
                    @set-value="setValue"
                  >
                    <!-- <el-option
                      v-for="opt in options.priority"
                      :key="opt.id"
                      :label="opt.name"
                      :value="specialField ? opt.id : opt.name"
                      :disabled="opt.disabled"
                    >
                      {{ opt.name }}
                    </el-option> -->
                  </SelectEl>
                </el-form-item>
                <el-form-item
                  class="row"
                  prop="complexity"
                  :label="mod[fields.complexity.vname]"
                >
                  <SelectEl
                    :model="form.complexity"
                    :field="fields.complexity"
                    :options="options.complexity"
                    @change-option="handleChange"
                    @set-value="setValue"
                  >
                    <!-- <el-option
                      v-for="opt in options.complexity"
                      :key="opt.id"
                      :label="opt.name"
                      :value="specialField ? opt.id : opt.name"
                      :disabled="opt.disabled"
                    >
                      {{ opt.name }}
                    </el-option> -->
                  </SelectEl>
                </el-form-item>
                <el-form-item
                  class="row"
                  prop="status"
                  :label="mod[fields.status.vname]"
                >
                  <SelectEl
                    :model="form.status"
                    :field="fields.status"
                    :options="options.status"
                    @change-option="handleChange"
                    @set-value="setValue"
                  >
                    <!-- <el-option
                      v-for="opt in options.status"
                      :key="opt.id"
                      :label="opt.name"
                      :value="specialField ? opt.id : opt.name"
                      :disabled="opt.disabled"
                    >
                      {{ opt.name }}
                    </el-option> -->
                  </SelectEl>
                </el-form-item>
              </div>
              <div class="el-form-item sub-field">
                <el-form-item
                  class="row"
                  prop="date_start"
                  :label="mod[fields.date_start.vname]"
                  ><DatepickerEl
                    :field="fields.date_start"
                    :model="form.date_start"
                    :datepicker="datepicker"
                    @set-value="setValue"
                  ></DatepickerEl
                ></el-form-item>
                <el-form-item
                  class="row"
                  prop="date_stop"
                  :label="mod[fields.date_stop.vname]"
                  ><DatepickerEl
                    :field="fields.date_stop"
                    :model="form.date_stop"
                    :datepicker="datepicker"
                    @set-value="setValue"
                  ></DatepickerEl
                ></el-form-item>
              </div>
              <el-form-item
                prop="description"
                :label="mod[fields.description.vname]"
              >
                <InputEl
                  :field="fields.description"
                  :model="form.description"
                  @set-value="setValue"
                ></InputEl>
              </el-form-item>
            </el-card>
          </div>
        </div>
      </el-form>
    </div>
  </div>
</template>
<script>
import { mixin, editView } from '@/utils/mixins';
import { MODULE, FIELD /*, BUTTON,*/ } from '@/utils/constants';
import SelectEl from 'Elements/Select/SelectEl.vue';
import InputEl from 'Elements/Input/Input.vue';
import DatepickerEl from 'Elements/Datepicker/Datepicker.vue';
export default {
  mixins: [mixin, editView],
  props: {
    fields: Object,
    mod: Object,
    dateFormat: Object
  },
  data() {
    return {
      module: MODULE.DIGIT_TASK,
      FIELD,
      form: {},
      rules: {},
      options: {},
      loading: false,
      callback: {
        field: {}
      },
      datepicker: {
        date: '',
        time: 'HH:mm'
      },
      fieldsConfig: [
        'name',
        'source',
        'digit_project_name',
        'assigned_user_name',
        'task_manager_name',
        'digit_block_name',
        'digit_section_name',
        'digit_workshop_name',
        'priority',
        'complexity',
        'status',
        'date_start',
        'date_stop',
        'description'
      ]
    };
  },
  created() {
    const format = this.setDateFormat(this.dateFormat);
    this.$set(this.datepicker, 'date', format);

    for (let key of this.fieldsConfig) {
      if (this.fields.hasOwnProperty(key)) {
        const {
          name,
          id_name,
          type,
          value,
          option,
          options,
          isMultiSelect,
          multiple,
          required,
          default: defaultVal
        } = this.fields[key];
        const isMultiple = multiple || isMultiSelect;
        const conditionVal = isMultiple ? [] : '';
        const defaultValue = defaultVal || conditionVal;

        if (id_name) {
          const { name, value, default: defaultVal } = id_name;
          this.$set(this.form, name, value || defaultVal || value);
        }

        this.$set(this.form, name, value || defaultValue);
        this.setRequiredFields(required, name);

        if (type !== FIELD.TYPE.DATETIME) {
          const fieldOptions = option || options || [];
          this.setOptions(fieldOptions, name);
        }
      }
    }
  },
  components: {
    InputEl,
    SelectEl,
    DatepickerEl
  }
};
</script>
