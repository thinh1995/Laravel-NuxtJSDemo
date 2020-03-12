<template>
  <a-row type="flex" justify="center" align="top">
    <a-col :span="8">
      <a-card title="Register">
        <a-form @submit.prevent="submit">
          <a-form-item v-bind="formItemLayout" label="Name" :validate-status="validateStatus.name" :help="errors.name" hasFeedback>
            <a-input v-model="form.name" />
          </a-form-item>
          <a-form-item v-bind="formItemLayout" label="E-mail" :validate-status="validateStatus.email" :help="errors.email" hasFeedback>
            <a-input v-model="form.email" />
          </a-form-item>
          <a-form-item v-bind="formItemLayout" label="Password" :validate-status="validateStatus.password" :help="errors.password" hasFeedback>
            <a-input type="password" v-model="form.password" />
          </a-form-item>
          <a-form-item v-bind="formItemLayout" label="Confirm Password" :validate-status="validateStatus.password_confirm" :help="errors.password_confirm" hasFeedback>
            <a-input type="password" v-model="form.password_confirm" />
          </a-form-item>
          <a-form-item v-bind="tailFormItemLayout">
            <a-button type="primary" html-type="submit">
              Register
            </a-button>
          </a-form-item>
        </a-form>
      </a-card>
    </a-col>
  </a-row>
</template>

<script>
  export default {
    middleware: ['guest'],
    data() {
      return {
        validateStatus: {
          name: '',
          email: '',
          password: '',
          password_confirm: ''
        },
        form: {
          name: '',
          email: '',
          password: '',
          password_confirm: ''
        },
        formItemLayout: {
          labelCol: {
            xs: {
              span: 24
            },
            sm: {
              span: 8
            },
          },
          wrapperCol: {
            xs: {
              span: 24
            },
            sm: {
              span: 16
            },
          },
        },
        tailFormItemLayout: {
          wrapperCol: {
            xs: {
              span: 24,
              offset: 0,
            },
            sm: {
              span: 16,
              offset: 8,
            },
          },
        },
      };
    },
    methods: {
      async submit() {
        Object.keys(this.validateStatus).forEach(field => this.validateStatus[field] = 'validating');
        try {
          await this.$axios.$post('register', this.form);
          if (Object.keys(this.errors).length === 0) {
            await this.$auth.loginWith('local', {
              data: {
                email: this.form.email,
                password: this.form.password
              }
            });
            this.$router.push({
              path: this.$router.query.redirect || "/dashboard"
            });
          }
        } catch (e) {
          Object.keys(this.validateStatus).forEach(field => this.validateStatus[field] = '');
          if (typeof this.errors === 'object') {
            Object.keys(this.errors).forEach((field) => {
              this.validateStatus[field] = 'error';
            });
          }
        }
      },
    },
  };

</script>
