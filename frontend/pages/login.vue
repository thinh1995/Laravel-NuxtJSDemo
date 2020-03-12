<template>
  <a-row type="flex" justify="center" align="top">
    <a-col :span="6">
      <a-card title="Login">
        <a-form class="login-form" @submit.prevent="submit">
          <a-form-item :validate-status="validateStatus.email" :help="errors.email" hasFeedback>
            <a-input placeholder="Email" v-model="form.email">
              <a-icon slot="prefix" type="user" style="color: rgba(0,0,0,.25)" />
            </a-input>
          </a-form-item>
          <a-form-item :validate-status="validateStatus.password" :help="errors.password" hasFeedback>
            <a-input type="password" placeholder="Password" v-model="form.password">
              <a-icon slot="prefix" type="lock" style="color: rgba(0,0,0,.25)" />
            </a-input>
          </a-form-item>
          <a-form-item>
            <a-button type="primary" html-type="submit" class="login-form-button">
              Log in
            </a-button>
            Or
            <nuxt-link to="/register">
              register now!
            </nuxt-link>
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
        form: {
          email: '',
          password: ''
        },
        validateStatus: {
          email: '',
          password: ''
        }
      }
    },
    methods: {
      async submit() {
        this.validateStatus.email = 'validating';
        this.validateStatus.password = 'validating';
        try {
          await this.$auth.loginWith('local', {
            data: this.form
          });
          this.$router.push({
            path: this.$router.query.redirect || "/dashboard"
          });
        } catch (e) {
          Object.keys(this.validateStatus).forEach((field) => {
            this.validateStatus[field] = '';
          });
          if (typeof this.errors === 'object') {
            Object.keys(this.errors).forEach((field) => {
              this.validateStatus[field] = 'error';
            });
          }
        }
      },
    },
  }

</script>
<style>
  #login .login-form {
    max-width: 300px;
  }

  #login .login-form-forgot {
    float: right;
  }

  #login .login-form-button {
    width: 100%;
  }

</style>
