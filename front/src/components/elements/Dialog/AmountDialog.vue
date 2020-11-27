<template>
  <el-dialog
    :visible.sync="is_visible"
    :title="title"
    :show-close="false"
    :close-on-click-modal="false"
    width="40%"
    center
    @close="$emit('cancel-callback')"
  >
    <div class="amountWrap">
      <span class="title">{{ subtitle }}</span>
      <el-form
        ref="amountForm"
        :model="form"
        :rules="rules"
        label-position="left"
        label-width="60%"
      >
        <el-form-item label="Количество позиций" prop="amount">
          <el-input
            v-model.lazy="form.amount"
            :value="defaultValue"
            type="number"
            name="amount"
            :min="1"
            :max="max"
            required
          ></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="validateForm">
          Ок
        </el-button>
        <el-button type="primary" @click="$emit('cancel-callback')">
          Отмена
        </el-button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
export default {
  props: {
    is_visible: {
      type: Boolean
    },
    title: {
      type: String
    },
    subtitle: {
      type: String
    },
    defaultValue: {
      type: Number,
      default: 1
    },
    max: {
      type: Number,
      default: 100
    }
  },
  data() {
    return {
      form: {
        amount: '1'
      },
      rules: {
        amount: [
          {
            required: true,
            message: 'Необходимо заполнить поле',
            trigger: 'change'
          }
        ]
      }
    };
  },
  mounted() {
    this.form.amount = this.defaultValue;
  },
  methods: {
    validateForm() {
      this.$refs.amountForm.validate(valid => {
        if (valid) {
          this.$emit('save-amount', this.form.amount);
        } else {
          console.log('Заполните обязательные поля!');
          return false;
        }
      });
    }
  },
};
</script>
