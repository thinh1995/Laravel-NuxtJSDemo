<template>
  <div>
    <a-row type="flex" justify="center" align="top">
      <a-col :span="12">
        <h3>{{ topic.title }}</h3>
        <a-list itemLayout="horizontal" size="large" :dataSource="topic.posts" bordered>
          <a-list-item slot="renderItem" slot-scope="post">
            <div slot="actions">
              <template v-if="authenticated">
                <nuxt-link
                  v-if="user.id === post.user.id"
                  :to="{name: 'topics-posts-edit', params: { topicId: $route.params.id, postId: post.id }}"
                  class
                >
                  <a-icon type="edit" />
                </nuxt-link>
                <a-button
                  v-if="user.id === post.user.id"
                  type="link"
                  @click="deletePost(post.id)"
                >
                  <a-icon type="delete" />
                </a-button>
              </template>
            </div>
            <a-list-item-meta>
              <div slot="title">
                <h5>{{post.body}}</h5>
                <small class="text-muted">{{ post.created_at }} by {{ post.user.name }}</small>
              </div>
            </a-list-item-meta>
          </a-list-item>
        </a-list>
        <hr />
        <a-card title="Add a new post">
          <a-form class="create-form" @submit.prevent="createPost">
            <a-form-item :validate-status="validateStatus.body" :help="errors.body" hasFeedback>
              <a-textarea placeholder="Body" v-model="form.body" :rows="4" />
            </a-form-item>
            <a-form-item>
              <a-button type="primary" html-type="submit" class="create-form-button">
                Add a new post
              </a-button>
            </a-form-item>
          </a-form>
        </a-card>
      </a-col>
    </a-row>
  </div>
</template>
<script>
export default {
  data() {
    return {
      topic: {},
      form: {
        body: ''
      },
      validateStatus: {
        body: ''
      }
    };
  },
  async asyncData({ $axios, params }) {
    const { data } = await $axios.$get(`/topics/${params.id}`);
    return {
      topic: data
    };
  },
  methods: {
    async createPost() {
      Object.keys(this.validateStatus).forEach(field => this.validateStatus[field] = 'validating');
      try {
        await this.$axios.post(`/topics/${this.$route.params.id}/posts`, this.form);
        this.$router.push('/topics');
      } catch(e) {
        Object.keys(this.validateStatus).forEach(field => this.validateStatus[field] = '');
        if (typeof this.errors === 'object') {
          Object.keys(this.errors).forEach((field) => {
            this.validateStatus[field] = 'error';
          });
        }
      }
    },
    async deletePost(id) {
      try {
        await this.$axios.$delete(`/topics/${this.$route.params.id}/posts/${id}`);
        this.$router.push("/topics");
      } catch (e) {}
    }
  },
};
</script>