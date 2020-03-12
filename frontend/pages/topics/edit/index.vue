<template>
  <a-row type="flex" justify="center" align="top">
    <a-col :span="12">
      <a-card title="Update Topic Title">
        <a-form class="update-form" @submit.prevent="update">
          <a-form-item :validate-status="validateStatus.title" :help="errors.title" hasFeedback>
            <a-input placeholder="Title" v-model="topic.title"></a-input>
          </a-form-item>
          <a-form-item>
            <a-button type="primary" html-type="submit" class="update-form-button">Update</a-button>
            <nuxt-link to="/topics" class="ml-5">Back to Topics</nuxt-link>
          </a-form-item>
        </a-form>
      </a-card>
    </a-col>
  </a-row>
</template>

<script>
export default {
  middleware: ["auth"],
  data() {
    return {
      topic: {
        title: ""
      },
      validateStatus: {
        title: ""
      }
    };
  },
  async asyncData({ $axios, params }) {
    const { data } = await $axios.$get(`/topics/${params.id}`);
    return { topic: data };
  },
  methods: {
    async update() {
      Object.keys(this.validateStatus).forEach(
        field => (this.validateStatus[field] = "validating")
      );
      try {
        await this.$axios.patch(`/topics/${this.$route.params.id}`, {
          title: this.topic.title
        });
        this.$router.push("/topics");
      } catch (e) {
        Object.keys(this.validateStatus).forEach(
          field => (this.validateStatus[field] = "")
        );
        if (typeof this.errors === "object") {
          Object.keys(this.errors).forEach(field => {
            this.validateStatus[field] = "error";
          });
        }
      }
    }
  }
};
</script>