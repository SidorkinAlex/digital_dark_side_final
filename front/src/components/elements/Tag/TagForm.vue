<template>
  <div class="tags">
    <el-button
      plain
      class="button-new-tag"
      size="mini"
      :disabled="Boolean(loading)"
      @click="visibleTagForm = !visibleTagForm"
    >
      {{ visibleTagForm ? '– Скрыть' : '+ Новый тег' }}
    </el-button>
    <div class="saved-tags" :v-if="addedTags.length">
      <el-tag
        v-for="{ tag_id, tag_name } in addedTags"
        :key="tag_id"
        closable
        :effect="hasMyTag(tag_id) ? 'dark' : 'light'"
        type="primary"
        size="mini"
        @close="removeTag($event, tag_id)"
      >
        {{ tag_name }}
      </el-tag>
    </div>

    <el-collapse v-model="activeCollapse" accordion>
      <el-collapse-item name="1">
        <el-form
          ref="tagForm"
          :model="tagForm"
          :rules="rules"
          size="mini"
          class="tag-form"
        >
          <el-form-item prop="tag_id">
            <el-select
              v-model="tagForm.tag_id"
              @input.native="handleChangeTag($event)"
              @change="tagAction = ACTION.ADD_TAG_TO_CANDIDATE"
              @visible-change="handleBlur"
              placeholder="Наименование тега"
              filterable
              multiple
            >
              <el-option
                v-for="{ id, name } in tagsList"
                :key="id"
                :label="name"
                :value="id"
              ></el-option>
              <p slot="empty" class="el-select-dropdown__empty">
                {{ noTagMatches }}
              </p>
              <div v-if="visibleTagBtn" class="add-tag-btn">
                <el-form-item prop="my_tag">
                  <el-radio-group v-model="tagForm.my_tag" @change="createTag">
                    <el-radio label="0">Создать общий тег</el-radio>
                    <el-radio label="1">Создать личный тег</el-radio>
                  </el-radio-group>
                </el-form-item>
              </div>
            </el-select>
          </el-form-item>
          <el-button
            type="primary"
            @click="addTag"
            size="mini"
            v-loading="tagLoading"
            :disabled="!tagForm.tag_id || !tagForm.tag_id.length"
            >Добавить</el-button
          >
        </el-form>
      </el-collapse-item>
    </el-collapse>
    <form v-show="false" id="tagForm">
      <input
        v-for="(val, key) in tagParams"
        :key="key"
        type="hidden"
        :name="key"
        :value="val"
      />
      <input
        v-if="tagAction === ACTION.ADD_TAG_TO_CANDIDATE"
        type="hidden"
        name="candidate_id"
        :value="taskId"
      />
      <div v-if="tagAction === ACTION.ADD_TAG_TO_CANDIDATE">
        <input
          type="hidden"
          v-for="tag in tagForm.tag_id"
          :key="tag"
          name="tag_id[]"
          :value="tag"
        />
      </div>
      <input
        v-if="tagAction === ACTION.CREATE_TAG"
        type="hidden"
        name="tag_name"
        :value="tagForm.tag_name"
      />
      <input
        v-if="tagAction === ACTION.CREATE_TAG"
        type="hidden"
        name="my_tag"
        :value="tagForm.my_tag"
      />
    </form>
  </div>
</template>

<script>
import { MODULE, ACTION } from '@/utils/constants';
import { mixin } from '@/utils/mixins';
import './tag.scss';
export default {
  mixins: [mixin],
  props: {
    taskId: String,
    options: Array
  },
  data() {
    return {
      ACTION,
      visibleTagForm: false,
      visibleTagBtn: false,
      tagParams: {
        module: MODULE.HRPAC_TAGS,
        jsqon_return: 1,
        to_pdf: true
      },
      tags: [],
      addedTags: [],
      tagForm: {},
      rules: {},
      tagAction: '',
      loading: false,
      tagLoading: false,
      activeCollapse: ''
    };
  },
  created() {
    this.tags = [...this.options];
    this.$axios
      .get('index.php', {
        params: {
          module: this.tagParams.module,
          action: ACTION.GET_CANDIDATE_TAGS,
          candidate_id: this.taskId,
          jsqon_return: 1,
          to_pdf: true
        }
      })
      .then(resp => (this.addedTags = [...resp.data]))
      .catch(err =>
        this.catchError(
          err,
          'Возникла ошибка загрузки списка тегов задач',
          'get task tags'
        )
      )
      .finally(() => (this.loading = false));
  },
  computed: {
    noTagMatches() {
      return this.tag_id && Object.values(this.tag_id).length
        ? 'Не найдено...'
        : 'Справочник пуст';
    },
    tagsList() {
      return [...new Set(this.tags)].filter(
        tag => !this.addedTags.map(({ tag_id }) => tag_id).includes(tag.id)
      );
    }
  },
  methods: {
    addTag() {
      this.tagAction = ACTION.ADD_TAG_TO_CANDIDATE;
      if (this.tagForm.tag_id.length && !this.requestSent) {
        this.requestSent = true;
        this.tagLoading = true;
        const form = document.getElementById('tagForm');
        const formData = new FormData(form);
        formData.set('action', this.tagAction);

        this.$axios
          .post('index.php', formData)
          .then(resp => {
            if (resp.data && !resp.data.error) {
              this.tagForm.tag_id.forEach(tagId => {
                if (
                  !this.addedTags.filter(({ tag_id }) => tag_id === tagId)
                    .length
                ) {
                  const tag = this.tags.find(({ id }) => id === tagId);
                  const addedTag = {
                    tag_id: tag.id,
                    tag_name: tag.name,
                    my_tag: tag.my_tag
                  };
                  this.addedTags.push(addedTag);
                  this.$refs.tagForm.resetFields();
                }
              });
            } else throw 'Возникла ошибка при добавлении тегов';
          })
          .catch(err =>
            this.catchError(
              err,
              'Возникла ошибка при добавлении тегов',
              'add tag'
            )
          )
          .finally(() => {
            this.tagLoading = false;
            this.requestSent = false;
          });
      }
    },
    createTag() {
      this.tagAction = ACTION.CREATE_TAG;
      if (!this.requestSent) {
        this.requestSent = true;
        const form = document.getElementById('tagForm');
        const formData = new FormData(form);
        formData.set('action', this.tagAction);

        this.$axios
          .post('index.php', formData)
          .then(resp => {
            if (resp.data && !resp.data.error) {
              const id = resp.data[0].tag_id;
              const newTag = {
                id,
                my_tag: this.tagForm.my_tag,
                name: this.tagForm.tag_name
              };
              this.tags.push(newTag);
              this.tagForm.tag_id.push(id);
              this.$set(this.tagForm, 'tag_name', '');
              this.$set(this.tagForm, 'my_tag', '');
              this.visibleTagBtn = false;
            } else throw 'Возникла ошибка при создании тега';
          })
          .catch(err =>
            this.catchError(
              err,
              'Возникла ошибка при создании тега',
              'create tag'
            )
          )
          .finally(() => (this.requestSent = false));
      }
    },
    removeTag(e, id) {
      if (!this.requestSent) {
        this.requestSent = true;
        this.tagAction = ACTION.REMOVE_TAG_FROM_CANDIDATE;

        if (id) {
          const formData = new FormData();
          const el = e.target.closest('.el-tag');
          const loader = `<div class="el-loading-mask"><div class="el-loading-spinner"><svg viewBox="25 25 50 50" class="circular"><circle cx="50" cy="50" r="20" fill="none" class="path"></circle></svg></div></div>`;

          el.innerHTML += loader;
          formData.set('action', this.tagAction);
          formData.set('candidate_id', this.taskId);
          formData.set('tag_id', id);

          for (let key in this.tagParams) {
            formData.set(key, this.tagParams[key]);
          }

          this.$axios
            .post('index.php', formData)
            .then(resp => {
              if (resp.data && !resp.data.error) {
                this.tags = this.tags.filter(({ id: tagId }) => tagId !== id);
                this.addedTags = this.addedTags.filter(
                  ({ tag_id }) => tag_id !== id
                );

                if (this.tagForm.tag_id) {
                  this.$set(
                    this.tagForm,
                    'tag_id',
                    this.tagForm.tag_id.filter(tagId => tagId !== id)
                  );
                }
              } else throw 'Возникла ошибка при удалении тега';
            })
            .catch(err =>
              this.catchError(
                err,
                'Возникла ошибка при удалении тега',
                'remove tag'
              )
            )
            .finally(() => {
              this.requestSent = false;
              el.querySelector('.el-loading-mask').remove();
            });
        }
      }
    },
    hasMyTag(tagId) {
      return (
        this.tags.filter(({ id, my_tag }) => id === tagId && Number(my_tag))
          .length > 0
      );
    },
    handleChangeTag(e) {
      this.tagAction = ACTION.CREATE_TAG;
      this.$set(this.tagForm, 'tag_name', e.target.value);
      const query = !this.tags.find(({ name }) => name === e.target.value);
      this.visibleTagBtn = e.target.value ? query : false;
    },
    handleBlur(visible) {
      if (!visible) {
        this.$set(this.tagForm, 'tag_name', '');
        this.$set(this.tagForm, 'my_tag', '');
        this.visibleTagBtn = false;
        this.tagAction = ACTION.ADD_TAG_TO_CANDIDATE;
      }
    }
  },
  watch: {
    visibleTagBtn: function() {
      if (this.visibleTagBtn) {
        const timer = setInterval(() => {
          const elSelect = $('.add-tag-btn').closest('.el-scrollbar.is-empty');

          if (elSelect.length) {
            elSelect.show(); // переопределяем библиотечные стили
            $('.is-empty ~ .el-select-dropdown__empty').insertBefore(
              '.is-empty'
            );
            clearInterval(timer);
          }
        }, 100);
      }
    },
    visibleTagForm: function() {
      if (this.visibleTagForm) {
        this.activeCollapse = ['1'];
      } else {
        this.activeCollapse = '';
      }
    }
  }
};
</script>
