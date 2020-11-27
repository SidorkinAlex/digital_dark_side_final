<template>
  <el-dialog
    :visible.sync="is_visible"
    :close-on-click-modal="false"
    @close="resetCallback"
    class="table-list"
    v-loading="selectLoading"
  >
    <filters
      v-if="filtersConfig"
      :key="updateFilterKey"
      :filter-config="filtersConfig"
      :page="page"
      :page-url="url"
      :f-module="module"
      @filter="searchData"
    ></filters>
    <h5>HR Шаблоны вакансий</h5>
    <pagination
      v-if="pageData"
      :queries="pageData.queries"
      :offsets="pageData.offsets"
      :p-module="module"
      class="table-top-nav__right"
      @set-data="setListData"
      @set-page="setPage"
      @set-loader="listLoading"
    ></pagination>
    <el-table
      ref="multipleTable"
      :data="tableData"
      size="mini"
      highlight-current-row
      @current-change="saveSelectedData"
      @sort-change="sortListData"
      v-loading="sorting"
      align="left"
    >
      <el-table-column label="Предпросмотр">
        <template slot-scope="scope">
          <el-button
            size="mini"
            icon="el-icon-view"
            @click="showPreview($event, scope.row)"
          >
          </el-button>
        </template>
      </el-table-column>
      <el-table-column
        v-for="(col, i) in tableFields"
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
      v-if="pageData"
      :queries="pageData.queries"
      :offsets="pageData.offsets"
      :p-module="module"
      class="table-top-nav__right"
      @set-data="setListData"
      @set-page="setPage"
      @set-loader="listLoading"
    ></pagination>
  </el-dialog>
</template>

<script>
import { formatHtml, setUrlParam } from '@/utils/helpers';
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
    templateId: {
      type: String
    },
    resetCallback: {
      type: Function
    },
    module: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      filtersConfig: null,
      filters: {},
      updateFilterKey: 0,
      sorting: false,
      selectLoading: false,
      tableData: [],
      tableFields: [],
      selection: null,
      ids: [],
      page: '',
      url: '',
      pageData: null
    };
  },
  mounted() {
    this.setListData(this.initialData);
    this.$emit('set-loading', false);
  },
  methods: {
    listLoading(bool) {
      this.tableData = [];
      this.sorting = bool;
    },
    setListData(resp) {
      const {
        sugarconfig,
        mod_strings,
        custom_search,
        data: objData
      } = resp.data;

      if (objData) {
        const { data, pageData } = objData;
        const templates = data;
        const { action, module } = pageData.queries.baseURL;

        this.url = setUrlParam(action, module);
        this.tableFields = [];
        this.filtersConfig = { ...custom_search };

        if (templates.hasOwnProperty('length')) {
          templates.forEach(item => {
            item['DESCRIPTION'] = formatHtml(item['DESCRIPTION']);
            const grade = item['GRADE'].replace(/\^/g, '').split(',');
            item['GRADE'] = Array.from(
              grade,
              i => custom_search.filters['grade_advanced'].options[i]
            ).join(', ');
          });
          this.tableData = templates;
        } else {
          for (let key in templates) {
            templates[key]['DESCRIPTION'] = formatHtml(
              templates[key]['DESCRIPTION']
            );
            const grade = templates[key]['GRADE'].replace(/\^/g, '').split(',');
            templates[key]['GRADE'] = Array.from(
              grade,
              i => custom_search.filters['grade_advanced'].options[i]
            ).join(', ');
            this.tableData.push(templates[key]);
          }
        }
        for (let key in sugarconfig) {
          this.tableFields.push({
            id: key,
            name: mod_strings[sugarconfig[key].label]
          });
        }

        this.pageData = Object.assign({}, pageData);
        this.sorting = false;
        this.requestSent = false;
      }
    },
    setPage(page) {
      this.page = String(page);
    },
    saveSelectedData(row) {
      this.selectLoading = true;
      this.selection = row;
      this.$emit('select-template', row.ID, row.NAME);
    },
    searchData(filters) {
      this.$set(this.filters, filters);
      this.listLoading(true);
      this.$axios
        .get('index.php', {
          params: Object.assign({}, this.listUrlParams, filters)
        })
        .then(resp => {
          this.setListData(resp);
          this.updateFilterKey++;
        })
        .catch(err =>
          this.catchError(
            err,
            'Возникла ошибка поиска шаблонов',
            'search templates'
          )
        );
    },
    sortListData(sort) {
      const sortOrder = sort.order === 'descending' ? 'DESC' : 'ASC';
      const sortProp = sort.prop.toLowerCase();
      const params = Object.assign({}, this.listUrlParams, this.filters);

      this.listLoading(true);
      params.lvso = sortOrder;
      params.HRPAC_VACANCY_TEMPLATE2_HRPAC_VACANCY_TEMPLATE_ORDER_BY = sortProp;

      this.$axios
        .get('index.php', {
          params
        })
        .then(resp => this.setListData(resp))
        .catch(err =>
          this.catchError(
            err,
            'Возникла ошибка сортировки данных',
            'templates sort'
          )
        );
    },
    showPreview(e, row) {
      e.stopPropagation();
      this.$emit('show-preview', e, { id: row.ID, name: row.NAME });
      // в будущем не отправлять запрос на получение по id данных шаблона, чтобы отобразить в предпросмотре.
      // использовать данные текущего запроса из попапа и в цикле отображать поля
    }
  },
  components: { Pagination, Filters }
};
</script>
