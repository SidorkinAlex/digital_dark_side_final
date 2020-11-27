<template>
  <el-dialog
    :visible.sync="is_visible"
    :close-on-click-modal="false"
    @close="resetCallback"
    class="table-list"
    v-loading="selectLoading"
  >
    <el-dialog
      v-if="inner_dialog.visible"
      :title="inner_dialog.title"
      :visible.sync="inner_dialog.visible"
      append-to-body
      :close-on-click-modal="false"
      :show-close="false"
    >
      <div v-html="inner_dialog.subtitle"></div>
      <div slot="footer" class="dialog-footer">
        <el-button
          v-for="(btn, i) in inner_dialog.btns[status]"
          :key="i"
          @click="btn.callback"
        >
          {{ btn.title }}
        </el-button>
      </div>
    </el-dialog>
    <filters
      :key="filtersKey"
      :filter-config="filtersConfig"
      :date-format="dateFormat"
      :page="page"
      :page-url="url"
      :f-module="module"
      @filter="searchCandidate"
    ></filters>
    <h5>HR Кандидаты Список</h5>
    <el-button
      class="select-data-btn"
      type="primary"
      size="small"
      :disabled="!multiselection.length"
      @click="saveCandidatesData"
    >
      Выбрать
    </el-button>
    <pagination
      :queries="pageData.queries"
      :offsets="pageData.offsets"
      :p-module="module"
      class="table-top-nav__right"
      @set-data="setCandidatesData"
      @set-page="setPage"
      @set-loader="listLoading"
    ></pagination>
    <el-table
      ref="multipleTable"
      :data="candidatesList"
      size="mini"
      @selection-change="selectCandidate"
      @sort-change="sortCandidates"
      v-loading="sorting"
      align="left"
    >
      <el-table-column type="selection"></el-table-column>
      <el-table-column
        v-for="(col, i) in candidateFields"
        :key="`${col.id}_${i}`"
        :property="col.id"
        :label="col.name"
        header-align="left"
        sortable
        :sort-orders="['ascending', 'descending']"
      >
      </el-table-column>
    </el-table>
    <pagination
      :queries="pageData.queries"
      :offsets="pageData.offsets"
      :p-module="module"
      class="table-top-nav__right"
      @set-data="setCandidatesData"
      @set-page="setPage"
      @set-loader="listLoading"
    ></pagination>
  </el-dialog>
</template>

<script>
import { formatHtml, detailView, setUrlParam } from '@/utils/helpers';
import { mixin } from '@/utils/mixins';
import Pagination from 'Elements/Pagination/Pagination.vue';
import Filters from 'Elements/Filters/Filters';

export default {
  mixins: [mixin],
  props: {
    listUrlParams: {
      type: Object
    },
    initialData: {
      type: Object
    },
    is_visible: {
      type: Boolean
    },
    vacancyId: {
      type: String
    },
    resetCallback: {
      type: Function
    },
    dateFormat: {
      type: String,
      default: 'd/m/Y'
    },
    module: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      filtersConfig: {},
      filters: {},
      filtersKey: 0,
      sorting: false,
      selectLoading: false,
      candidatesList: [],
      candidateFields: [],
      multiselection: [],
      ids: [],
      page: '',
      url: '',
      pageData: {},
      blocked_candidates: null,
      status: null,
      inner_dialog: {
        visible: false,
        title: '',
        subtitle: '',
        btns: {
          stoped: [
            {
              title: 'Да',
              callback: this.confirmSelection
            },
            {
              title: 'Нет',
              callback: this.closeCallback
            }
          ],
          completed: [
            {
              title: 'ОК',
              callback: this.closeCallback
            }
          ]
        }
      }
    };
  },
  mounted() {
    this.setCandidatesData(this.initialData);
    this.$emit('set-loading', false);
  },
  methods: {
    listLoading(bool) {
      this.candidatesList = [];
      this.sorting = bool;
    },
    closeCallback() {
      const params = ['status', 'blocked_candidates'];
      this.closeDialog('inner_dialog', null, ...params);
    },
    setCandidatesData(resp) {
      const {
        sugarconfig,
        mod_strings,
        custom_search,
        data: { data, pageData }
      } = resp.data;
      const candidates = data;
      const { action, module } = pageData.queries.baseURL;

      this.url = setUrlParam(action, module);
      this.candidateFields = [];
      this.filtersConfig = { ...custom_search };

      if (candidates.hasOwnProperty('length')) {
        candidates.forEach(item => {
          item['LAST_WORK'] = formatHtml(item['LAST_WORK']);
        });
        this.candidatesList = candidates;
      } else {
        for (let key in candidates) {
          candidates[key]['LAST_WORK'] = formatHtml(
            candidates[key]['LAST_WORK']
          );
          this.candidatesList.push(candidates[key]);
        }
      }
      for (let key in sugarconfig) {
        this.candidateFields.push({
          id: key,
          name: mod_strings[sugarconfig[key].label]
        });
      }

      this.pageData = Object.assign({}, pageData);
      this.sorting = false;
      this.requestSent = false;
    },
    setPage(page) {
      this.page = String(page);
    },
    selectCandidate(candidate) {
      this.multiselection = candidate;
    },
    redirect() {
      if (this.ids.length === 1) {
        this.selectLoading = false;
        location.replace(detailView('HRPAC_CANDIDATES', this.ids[0]));
      } else {
        location.reload();
      }
    },
    saveCandidatesData() {
      if (!this.requestSent) {
        this.requestSent = true;
        this.ids = [];
        this.multiselection.forEach(select => this.ids.push(select.ID));

        this.$axios
          .get('index.php', {
            params: {
              module: 'HRPAC_VACANCY',
              record: this.vacancyId,
              action: 'add_from_vacancy',
              return_module: 'HRPAC_VACANCY',
              return_id: this.vacancyId,
              subpanel_id: this.ids,
              isDuplicate: false,
              child_field: 'hrpac_vacancy_hrpac_candidates_1',
              subpanel_field_name: 'hrpac_vacancy_hrpac_candidates_1',
              subpanel_module_name: 'hrpac_vacancy_hrpac_candidates_1',
              refresh_page: 1,
              to_pdf: true
            }
          })
          .then(resp => {
            const { status, data } = resp.data;
            const noArrayLength =
              !!data && data.hasOwnProperty('length') && !data.length;

            this.status = status;
            this.blocked_candidates = Object.keys(data);

            if (status === 'completed' && noArrayLength) {
              this.redirect();
            } else {
              this.inner_dialog.title =
                this.status === 'stoped'
                  ? 'Подобрать кандидата к вакансии?'
                  : '';
              this.inner_dialog.subtitle = '';

              for (let id in data) {
                data[id].forEach(vacancy => {
                  const mes = `<p data-id="${id}"><span>${vacancy.mes}</span></p>`;
                  this.inner_dialog.subtitle += mes;
                });
              }
              this.$set(this.inner_dialog, 'visible', true);
            }
          })
          .catch(err =>
            this.catchError(
              err,
              'Возникла ошибка при сохранении формы выбора кандидатов',
              'candidate selection check'
            )
          )
          .finally(() => (this.requestSent = false));
      }
    },
    searchCandidate(filters) {
      this.$set(this.filters, filters);
      this.listLoading(true);
      this.$axios
        .get('index.php', {
          params: Object.assign({}, this.listUrlParams, filters)
        })
        .then(resp => {
          this.setCandidatesData(resp);
          this.filtersKey++;
        })
        .catch(err =>
          this.catchError(
            err,
            'Возникла ошибка поиска кандидатов',
            'search candidates'
          )
        );
    },
    sortCandidates(sort) {
      const sortOrder = sort.order === 'descending' ? 'DESC' : 'ASC';
      const sortProp = sort.prop.toLowerCase();
      const params = Object.assign({}, this.listUrlParams, this.filters);

      this.listLoading(true);
      params.lvso = sortOrder;
      params.HRPAC_CANDIDATES2_HRPAC_CANDIDATES_ORDER_BY = sortProp;

      this.$axios
        .get('index.php', {
          params
        })
        .then(resp => this.setCandidatesData(resp))
        .catch(err =>
          this.catchError(
            err,
            'Возникла ошибка сортировки кандидатов',
            'candidates sort'
          )
        );
    },
    confirmSelection() {
      if (this.blocked_candidates.length) {
        this.$set(this.inner_dialog, 'visible', false);
        this.selectLoading = true;
        this.$axios
          .get('index.php', {
            params: {
              module: 'HRPAC_VACANCY',
              record: this.vacancyId,
              subpanel_id: this.blocked_candidates,
              action: 'add_from_vacancy',
              forse_add: 1
            }
          })
          .then(() => this.redirect())
          .catch(err =>
            this.catchError(
              err,
              'Возникла ошибка сохранения выбранных кандидатов',
              'confirm selection'
            )
          );
      }
    }
  },
  components: { Pagination, Filters }
};
</script>
