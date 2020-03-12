<template>
  <a-row type="flex" justify="center" align="top">
    <a-col :span="12">
      <a-card title="Latest topics">
        <a-list itemLayout="horizontal" size="large" :dataSource="topics">
          <a-list-item slot="renderItem" slot-scope="topic">
            <a-list-item-meta>
              <div slot="title">
                <nuxt-link :to="{name: 'topics-id', params: {id: topic.id}}" class="btn-link">
                  <h3 class="inline-block">{{topic.title}}</h3>
                </nuxt-link>
                <small class="text-muted">{{ topic.created_at }} by {{ topic.user.name }}</small>
                <template v-if="authenticated">
                  <nuxt-link
                    v-if="user.id === topic.user.id"
                    :to="{name: 'topics-edit', params: {id: topic.id}}"
                    class="link ml-1"
                  >
                    <a-icon type="edit" />
                  </nuxt-link>
                  <a-button
                    v-if="user.id === topic.user.id"
                    type="link"
                    @click="deleteTopic(topic.id)"
                  >
                    <a-icon type="delete" />
                  </a-button>
                </template>
              </div>
              <div slot="description">
                <a-list itemLayout="horizontal" size="large" :dataSource="topic.posts" bordered>
                  <a-list-item slot="renderItem" slot-scope="post">
                    <a-list-item-meta>
                      <div slot="title">
                        <h5>{{post.body}}</h5>
                        <small class="text-muted">
                          {{ post.created_at }} by {{ post.user.name }}
                          <a-button size="small" @click="likePost(topic.id, post)">
                            {{ post.like_count }}
                            <a-icon type="like"></a-icon>
                          </a-button>
                        </small>
                      </div>
                    </a-list-item-meta>
                  </a-list-item>
                </a-list>
              </div>
            </a-list-item-meta>
          </a-list-item>
          <div slot="footer">
            <a-row type="flex" justify="end">
              <a-pagination
                :total="pagination.total"
                :defaultPageSize="pagination.pageSize"
                @change="onPageChange"
              />
            </a-row>
          </div>
        </a-list>
      </a-card>
    </a-col>
  </a-row>
</template>

<script>
export default {
  data() {
    return {
      topics: [],
      pagination: {}
    };
  },
  async asyncData({ $axios }) {
    try {
      let { data, meta } = await $axios.$get("/topics");
      return {
        topics: data,
        pagination: {
          total: meta.total,
          pageSize: meta.per_page
        }
      };
    } catch (e) {}
  },
  methods: {
    async onPageChange(page, pageSize) {
      try {
        let { data } = await this.$axios.$get(`/topics?page=${page}`);
        return (this.topics = data);
      } catch (e) {}
    },
    async deleteTopic(id) {
      try {
        await this.$axios.$delete(`/topics/${id}`);
        this.$router.push("/");
      } catch (e) {}
    },
    async likePost(topicId, post) {
      if (this.authenticated) {
        if (this.user.id === post.user.id) {
          this.$message.error("You can't like your post");
        }
        if (post.users) {
          if (post.users.some(user => user.id === this.user.id)) {
            this.$message.warning("You have already like this post");
          } else {
            try {
              await this.$axios.$post(
                `/topics/${topicId}/posts/${post.id}/likes`
              );
              let { data, meta } = await this.$axios.$get(`/topics`);
              this.topics = data;
              this.pagination = {
                total: meta.total,
                pageSize: meta.per_page
              };
            } catch (e) {
              console.log(e);
            }
          }
        }
      } else {
        this.$message.info("Please log in");
        this.$router.push("/login");
      }
    }
  }
};
</script>
