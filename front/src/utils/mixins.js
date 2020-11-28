import { FIELD, SCROLL_VALUE } from '@/utils/constants';
import { scrollToError } from '@/utils/helpers';
export const mixin = {
  methods: {
    formatHtml(text) {
      const replacement = [
        [/&quot;/g, '"'],
        [/&gt;/g, '>'],
        [/&lt;/g, '<']
      ];
      return text
        .replace(...replacement[0])
        .replace(...replacement[1])
        .replace(...replacement[2]);
    },
    setDateFormat() {
      const { date, time } = this.dateFormat;

      if (date) {
        const separator = date.match(/[./-]/g)[0];
        const dateArray = date.replace(/%/g, '').split(separator);
        const format = dateArray.map(item => {
          item =
            item == 'Y' || item == 'YY'
              ? item.toLowerCase()
              : item == 'm' || item == 'mm'
              ? item.toUpperCase()
              : item.toLowerCase();
          if (item.length === 1) {
            return item == 'd' || item == 'M' ? item.repeat(2) : item.repeat(4);
          } else return item;
        });

        return format.join(separator);
      }

      if (time) {
        //
      }
      return 'dd.MM.yyyy';
    },
    closeDialog(dialogs, formName, ...params) {
      const hideDialog = dialog => {
        const dialogParams = this.hasOwnProperty('is_visible')
          ? [this['is_visible'], dialog]
          : [this[dialog], 'visible'];
        this.$set(...dialogParams, false);
      };

      if (formName && this.$refs[formName]) {
        this.$refs[formName].resetFields();
      }

      if (dialogs && Array.isArray(dialogs)) {
        dialogs.forEach(d => hideDialog(d));
      } else {
        hideDialog(dialogs);
      }

      if (params && params.length) {
        params.forEach(i => (this[i] = null));
      }
    },
    catchError(err, msg, action) {
      console.log(action + ' error.', err);
      this.$message({
        offset: 100,
        showClose: true,
        message: msg,
        type: 'error'
      });
    },
    detailViewLink(module, id) {
      return `/index.php?module=${module}&action=DetailView&record=${id}`;
    },
    editViewLink(module, id) {
      return `/index.php?module=${module}&action=EditView&record=${id}`;
    },
    listViewLink(module) {
      return `/index.php?module=${module}&action=index`;
    },
    handleScroll(evt, el) {
      if (window.scrollY > SCROLL_VALUE) {
        el.classList.add('scroll');
      } else {
        el.classList.remove('scroll');
      }
    }
  }
};

export const editView = {
  methods: {
    defaultParams(module, id) {
      return {
        module: module,
        record: id || '',
        action: 'Save',
        jsqon_return: 1
      };
    },
    selectType(type) {
      return (
        type === FIELD.TYPE.RELATE ||
        type === FIELD.TYPE.MULTIENUM ||
        type === FIELD.TYPE.CURRENCY_ID ||
        type === FIELD.TYPE.ENUM
      );
    },
    isDateField(field) {
      return (
        field.type === FIELD.TYPE.DATETIME || field.type === FIELD.TYPE.DATE
      );
    },
    setRequiredFields(required, key) {
      if (required) {
        const validation = {
          required: true,
          message: 'Необходимо заполнить поле',
          trigger: 'change'
        };
        const rule = this.rules[key]
          ? [...this.rules[key], validation]
          : [validation];
        this.$set(this.rules, key, rule);
      }
    },
    setOptions(opts, name) {
      let options = [];
      if (opts && !opts.hasOwnProperty('length')) {
        for (let key in opts) {
          if (opts[key] && !opts[key].hasOwnProperty('length')) {
            options.push({ id: key, name: opts[key].name });
          } else {
            options.push({ id: key, name: opts[key] });
          }
        }
      } else {
        opts.forEach(item => {
          const { id, name, first_name, last_name } = item;
          const label = name || `${first_name} ${last_name}`;
          options.push({ id, name: label });
        });
      }
      this.$set(this.options, name, options);
    },
    setValue(name, val) {
      this.$set(this.form, name, val);
      this.$refs.form.validateField(name);
    },
    changeOption(name, id_name) {
      // set id_name field
      if (name) {
        const selectedOpt = this.options[name].filter(
          opt => opt.name === this.form[name]
        );
        this.$set(this.form, id_name, selectedOpt[0].id);
      }
    },
    saveForm() {
      this.$refs.form.validate((valid, fields) => {
        if (valid) {
          if (!this.requestSent) {
            this.requestSent = true;

            const defaultParams = this.defaultParams(
              this.module,
              this.fields.id.value
            );
            const formData = new FormData();
            const form = { ...defaultParams, ...this.form };
            console.log(this.form);
            const params = {};

            for (let key in form) {
              if (Array.isArray(form[key])) {
                params[key] = form[key].length ? form[key] : '';
              } else {
                formData.set(key, form[key]);
              }
            }

            this.loading = true;
            this.$axios
              .post('index.php', formData, {
                header: {
                  'Content-Type': 'multipart/form-data'
                },
                params
              })
              .then(resp => {
                const newId = resp.data.id;
                location.href = this.detailViewLink(this.module, newId);
              })
              .catch(err =>
                this.catchError(
                  err,
                  'Возникла ошибка отправки данных формы. Попробуйте еще раз.',
                  'save form'
                )
              )
              .finally(() => {
                this.requestSent = false;
                this.loading = false;
              });
          }
        } else {
          scrollToError(fields, SCROLL_VALUE);
          console.log('Заполните обязательные поля!');
          return false;
        }
      });
    },
    resetForm() {
      return this.fields.id.value
        ? this.detailViewLink(this.module, this.form.id)
        : this.listViewLink(this.module);
    }
  }
};
