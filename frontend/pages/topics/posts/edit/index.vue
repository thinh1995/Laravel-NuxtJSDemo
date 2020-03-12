<template>
  <a-row type="flex" justify="center" align="top">
    <a-col :span="12">
      <a-card title="Update Post Body">
        <a-form class="update-form" @submit.prevent="update">
          <a-form-item :validate-status="validateStatus.body" :help="errors.body" hasFeedback>
            <a-textarea placeholder="Body" v-model="post.body" :rows="4" />
          </a-form-item>
          <a-form-item>
            <a-button type="primary" html-type="submit" class="update-form-button">Update</a-button>
            <nuxt-link
              :to="{name: 'topics-id', params: {id: $route.params.topicId}}"
              class="ml-5"
            >Back to Topic</nuxt-link>
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
      post: {
        body: ""
      },
      validateStatus: {
        body: ""
      }
    };
  },
  async asyncData({ $axios, params }) {
    const { data } = await $axios.$get(
      `/topics/${params.topicId}/posts/${params.postId}`
    );
    return {
      post: data
    };
  },
  methods: {
    async update() {
      Object.keys(this.validateStatus).forEach(
        field => (this.validateStatus[field] = "validating")
      );
      try {
        await this.$axios.patch(
          `/topics/${this.$route.params.topicId}/posts/${this.$route.params.postId}`,
          {
            body: this.post.body
          }
        );
        this.$router.push(`/topics/${this.$route.params.topicId}`);
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