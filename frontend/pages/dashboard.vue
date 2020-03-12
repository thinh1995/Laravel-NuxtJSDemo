<template>
  <a-row type="flex" justify="center" align="top">
    <a-col :span="12">
      <a-card title="Create a new topic">
        <a-form class="create-form" @submit.prevent="create">
          <a-form-item :validate-status="validateStatus.title" :help="errors.title" hasFeedback>
            <a-input placeholder="Title" v-model="form.title"></a-input>
          </a-form-item>
          <a-form-item :validate-status="validateStatus.body" :help="errors.body" hasFeedback>
            <a-textarea placeholder="Body" v-model="form.body" :rows="4" />
          </a-form-item>
          <a-form-item>
            <a-button type="primary" html-type="submit" class="create-form-button">
              Create
            </a-button>
          </a-form-item>
        </a-form>
      </a-card>
    </a-col>
  </a-row>
</template>
<script>
  export default {
    middleware: ['auth'],
    data() {
      return {
        form: {
          title: '',
          body: ''
        },
        validateStatus: {
          title: '',
          body: ''
        }
      }
    },
    methods: {
      async create() {
        Object.keys(this.validateStatus).forEach(field => this.validateStatus[field] = 'validating');
        try {
          await this.$axios.post('/topics', this.form);
          this.$router.push("/topics");
        } catch (e) {
          Object.keys(this.validateStatus).forEach(field => this.validateStatus[field] = '');
          if (typeof this.errors === 'object') {
            Object.keys(this.errors).forEach((field) => {
              this.validateStatus[field] = 'error';
            });
          }
        }
      }
    }
  }

</script>
