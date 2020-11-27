<template>
  <el-dialog
    ref="dialog"
    title="Заявка на выход сотрудника"
    :visible.sync="is_visible"
    :show-close="false"
    v-loading="formLoading"
    @close="$emit('reset-callback', 'exitDataForm')"
  >
    <el-form
      ref="exitDataForm"
      id="exitDataForm"
      :model="exitDataForm"
      :rules="rules"
      label-position="top"
    >
      <div class="hidden-elems">
        <input type="hidden" name="module" value="HRPAC_NEW_EPLOYEE_EXIT" />
        <input type="hidden" name="action" :value="action" />
        <input type="hidden" name="jsqon_return" value="1" />
        <input type="hidden" name="to_pdf" value="true" />
        <input type="hidden" name="candidate_id" :value="candidateId" />
        <input type="hidden" name="vacancy_id" :value="vacancyId" />
        <input type="hidden" name="type" :value="stageType" />
      </div>
      <div class="candidate-form__section">
        <h3>Сотрудник</h3>
        <el-form-item label="Фамилия" prop="last_name" class="row">
          <el-input
            name="last_name"
            v-model.lazy="exitDataForm.last_name"
          ></el-input>
        </el-form-item>
        <el-form-item label="Имя" prop="first_name" class="row">
          <el-input
            name="first_name"
            v-model.lazy="exitDataForm.first_name"
          ></el-input>
        </el-form-item>
        <el-form-item label="Отчество" prop="middle_name" class="row">
          <el-input
            name="middle_name"
            v-model.lazy="exitDataForm.middle_name"
            class="row"
          ></el-input>
        </el-form-item>
        <el-form-item label="Дата рождения" prop="birth_date" class="row">
          <el-date-picker
            type="date"
            v-model="exitDataForm.birth_date"
            name="birth_date"
            :format="`${datepicker.dateFormat}`"
            :value-format="`${datepicker.dateFormat}`"
            :picker-options="pickerOptions"
          ></el-date-picker>
        </el-form-item>
        <el-form-item label="Телефон" prop="phone" class="row">
          <el-input
            type="text"
            name="phone"
            v-model.lazy="exitDataForm.phone"
          ></el-input>
        </el-form-item>
      </div>
      <div class="candidate-form__section">
        <h3>Время и место выхода</h3>
        <el-form-item label="Дата выхода" prop="date_exit" class="row">
          <el-date-picker
            v-model="exitDataForm.date_exit"
            type="date"
            name="date_exit"
            :format="datepicker.dateFormat"
            :value-format="datepicker.dateFormat"
            :picker-options="datepicker.pickerOptions"
            @change="
              handleChangeRange(
                'date_exit',
                'date_end_contract',
                'exitDataForm'
              )
            "
          ></el-date-picker>
        </el-form-item>
        <el-form-item label="Форма оформления" prop="layout_form" class="row">
          <el-select
            v-model="exitDataForm.layout_form"
            filterable
            no-match-text="Нет результатов поиска"
            placeholder=""
            @change="handleChangeLayout"
          >
            <el-option
              v-for="(name, key) in options.layout_form_list"
              :key="key"
              :label="name"
              :value="key"
            ></el-option>
          </el-select>
          <input
            type="hidden"
            name="layout_form"
            :value="exitDataForm.layout_form"
          />
        </el-form-item>
        <el-form-item
          v-if="
            exitDataForm.layout_form === 'contract_gpx' ||
              exitDataForm.layout_form === 'term_contract'
          "
          label="Дата окончания договора"
          prop="date_end_contract"
          class="row"
        >
          <el-date-picker
            v-model="exitDataForm.date_end_contract"
            type="date"
            :format="datepicker.dateFormat"
            :value-format="datepicker.dateFormat"
            :picker-options="datepicker.pickerOptions"
            @change="
              handleChangeRange(
                'date_exit',
                'date_end_contract',
                'exitDataForm'
              )
            "
          ></el-date-picker>
        </el-form-item>
        <input
          type="hidden"
          name="date_end_contract"
          :value="exitDataForm.date_end_contract"
        />
        <el-form-item label="Бизнес юнит" class="row">
          <el-input
            type="text"
            readonly
            disabled
            :value="selectedStage.vacancy_data.business_unit_id"
          />
        </el-form-item>
        <el-form-item label="Отдел" class="row">
          <el-input
            type="text"
            readonly
            disabled
            :value="selectedStage.vacancy_data.department_id"
          />
        </el-form-item>
        <el-form-item label="Должность" class="row">
          <el-input
            type="text"
            readonly
            disabled
            :value="selectedStage.vacancy_data.name_id"
          />
        </el-form-item>
        <el-form-item label="Грейд" prop="grade" class="row">
          <el-select
            v-model="exitDataForm.grade"
            placeholder=""
            filterable
            no-match-text="Нет результатов поиска"
          >
            <el-option
              v-for="(name, key) in options.grade"
              :key="key"
              :label="name"
              :value="key"
            ></el-option>
          </el-select>
          <input type="hidden" name="grade" :value="exitDataForm.grade" />
        </el-form-item>
        <el-form-item label="Руководитель" class="row">
          <el-input
            type="text"
            readonly
            disabled
            :value="selectedStage.vacancy_data.supervisor_id"
          />
        </el-form-item>
        <el-form-item
          label="Группа проектов"
          prop="group_of_projects"
          class="row"
        >
          <el-select
            v-model="exitDataForm.group_of_projects"
            placeholder=""
            filterable
            no-match-text="Нет результатов поиска"
          >
            <el-option
              v-for="(name, key) in options.group_of_projects"
              :key="key"
              :label="name"
              :value="key"
            ></el-option>
          </el-select>
          <input
            type="hidden"
            name="group_of_projects"
            :value="exitDataForm.group_of_projects"
          />
        </el-form-item>
        <el-form-item label="Номер места" prop="place_number" class="row">
          <el-input
            name="place_number"
            v-model.lazy="exitDataForm.place_number"
          ></el-input>
        </el-form-item>
      </div>
      <div class="candidate-form__section">
        <h3>Дополнительная информация <i>*</i></h3>
        <el-form-item prop="description" class="row">
          <el-input
            type="textarea"
            name="description"
            v-model.lazy="exitDataForm.description"
          ></el-input>
          <el-popover
            placement="top-start"
            width="auto"
            trigger="hover"
            :offset="4"
            class="form-help"
          >
            <span>Введите, что необходимо подготовить</span>
            <i slot="reference" class="el-icon-info"></i>
          </el-popover>
        </el-form-item>
      </div>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button
        type="primary"
        v-loading="loading"
        @click.prevent="saveFormData"
      >
        Сохранить
      </el-button>
      <el-button @click="$emit('reset-callback', 'exitDataForm')">
        Отмена
      </el-button>
    </span>
  </el-dialog>
</template>

<script>
import { mixin } from '@/utils/mixins';
import { scrollToError, setDateFormat } from '@/utils/helpers';
const FormData = require('form-data');

export default {
  mixins: [mixin],
  props: {
    is_visible: {
      type: Boolean
    },
    selectedStage: {
      type: Object
    }
  },
  data() {
    const validateDateRange = (rule, value, callback) => {
      this.validateDateRange(
        true,
        value,
        callback,
        [this.exitDataForm.date_exit, this.exitDataForm.date_end_contract],
        this.datepicker.dateFormat
      );
    };

    return {
      datepicker: {
        dateFormat: 'dd.MM.yyyy',
        pickerOptions: {
          firstDayOfWeek: 1
        }
      },
      pickerOptions: {
        disabledDate(time) {
          return time.getTime() > Date.now();
        },
        firstDayOfWeek: 1
      },
      candidateId: this.selectedStage.candidate_id,
      vacancyId: this.selectedStage.vacancy_id,
      stageType: this.selectedStage.stageType,
      action: '',
      formLoading: true,
      loading: false,
      exitDataForm: {},
      hasAjaxFormData: false,
      options: {},
      rules: {
        date_exit: [
          {
            required: true,
            validator: validateDateRange,
            trigger: ['blur', 'change']
          }
        ],
        layout_form: [
          {
            required: true,
            message: 'Необходимо заполнить поле',
            trigger: ['blur', 'change']
          }
        ],
        grade: [
          {
            required: true,
            message: 'Необходимо заполнить поле',
            trigger: ['blur', 'change']
          }
        ],
        date_end_contract: [
          {
            required: true,
            validator: validateDateRange,
            trigger: ['blur', 'change']
          }
        ],
        first_name: [
          {
            required: true,
            message: 'Необходимо заполнить поле',
            trigger: ['blur', 'change']
          }
        ],
        last_name: [
          {
            required: true,
            message: 'Необходимо заполнить поле',
            trigger: ['blur', 'change']
          }
        ],
        middle_name: [
          {
            required: true,
            message: 'Необходимо заполнить поле',
            trigger: ['blur', 'change']
          }
        ],
        birth_date: [
          {
            required: true,
            message: 'Необходимо заполнить поле',
            trigger: ['blur', 'change']
          }
        ],
        phone: [
          {
            required: true,
            message: 'Необходимо заполнить поле',
            trigger: ['blur', 'change']
          }
        ],
        group_of_projects: [
          {
            required: true,
            message: 'Необходимо заполнить поле',
            trigger: ['blur', 'change']
          }
        ],
        description: [
          {
            required: true,
            message: 'Необходимо заполнить поле',
            trigger: ['blur', 'change']
          }
        ]
      }
    };
  },
  created() {
    // получаем список опций и данные формы выхода нового сотрудника
    this.$axios
      .get(
        '/index.php?module=HRPAC_NEW_EPLOYEE_EXIT&action=get_lists&jsqon_return=1&to_pdf=true'
      )
      .then(resp => {
        for (let key in resp.data) {
          if (key === 'user_dat_format') {
            this.$set(
              this.datepicker,
              'dateFormat',
              setDateFormat(resp.data[key])
            );
          } else {
            this.$set(this.options, key, resp.data[key]);
          }
        }

        if (!this.exitDataForm.layout_form && this.options.layout_form_list) {
          this.$set(
            this.exitDataForm,
            'layout_form',
            Object.keys(this.options.layout_form_list)[0]
          );
        }
      })
      .catch(err =>
        this.catchError(
          err,
          'Возникла ошибка загрузки списка опций для формы выхода сотрудника',
          'get employee exit options'
        )
      );
    this.$axios
      .get('index.php', {
        params: {
          module: 'HRPAC_NEW_EPLOYEE_EXIT',
          action: 'get_new_employee_exit',
          candidate_id: this.candidateId,
          vacancy_id: this.vacancyId,
          jsqon_return: 1,
          to_pdf: true
        }
      })
      .then(resp => {
        if (resp.data && !resp.data.error) {
          this.hasAjaxFormData = true;
          this.exitDataForm = { ...resp.data };
          this.action = 'save_new_employee_exit';
        } else {
          this.action = 'create_new_employee_exit';
        }

        // заполняем поля с личными данными
        this.$refs.exitDataForm.fields.forEach(field => {
          const prop = field.$options.propsData.prop;
          const { candidate_data } = this.selectedStage;
          const communications = candidate_data.communications;
          const phoneIndex =
            communications &&
            communications.value.findIndex(
              ({ value_type }) => value_type === prop
            );

          if (
            candidate_data.hasOwnProperty(prop) &&
            candidate_data[prop].value &&
            prop !== 'description'
          ) {
            this.$set(this.exitDataForm, prop, candidate_data[prop].value);
          }

          if (phoneIndex > -1) {
            this.$set(
              this.exitDataForm,
              prop,
              communications.value[phoneIndex].value
            );
          }
        });
      })
      .catch(err =>
        this.catchError(
          err,
          'Возникла ошибка получения данных о выходе сотрудника',
          'get employee exit data'
        )
      )
      .finally(() => (this.formLoading = false));
  },
  methods: {
    saveFormData() {
      this.$refs.exitDataForm.validate((valid, fields) => {
        if (valid) {
          if (!this.requestSent) {
            this.requestSent = true;
            const form = document.getElementById('exitDataForm');
            const formData = new FormData(form);

            this.loading = true;
            this.$axios
              .post('index.php', formData)
              .then(resp => {
                if (resp.data && !resp.data.error) {
                  this.$emit('confirm-stage-select');
                  this.$emit('reset-callback', 'exitDataForm');
                } else
                  throw 'Возникла ошибка отправки данных формы. Попробуйте еще раз.';
              })
              .catch(err =>
                this.catchError(
                  err,
                  'Возникла ошибка сохранения формы выхода сотрудника. Попробуйте еще раз.',
                  'save employee exit form'
                )
              )
              .finally(() => {
                this.requestSent = false;
                this.loading = false;
              });
          }
        } else {
          const dialog = this.$refs.dialog.$el;
          const dialogTop = dialog.children[0].offsetTop;
          scrollToError(fields, dialogTop, dialog);
          console.log('Заполните обязательные поля!');
        }
      });
    },
    handleChangeLayout() {
      this.$set(this.exitDataForm, 'date_end_contract', '');
    }
  }
};
</script>
