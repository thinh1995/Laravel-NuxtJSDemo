<template>
  <a-menu :selectedKeys="[$nuxt.$route.name]" mode="horizontal" :style="{ lineHeight: '58px' }" theme="dark">
    <a-menu-item key="index">
      <nuxt-link to="/">
        <a-icon type="home" />Home</nuxt-link>
    </a-menu-item>
    <a-menu-item key="topics">
      <nuxt-link to="/topics">
        <a-icon type="book" />Topics</nuxt-link>
    </a-menu-item>
    <template v-if="!authenticated">
      <a-menu-item key="login">
        <nuxt-link to="/login">
          <a-icon type="lock" />Login</nuxt-link>
      </a-menu-item>
      <a-menu-item key="register">
        <nuxt-link to="/register">
          <a-icon type="user" />Register</nuxt-link>
      </a-menu-item>
    </template>
    <template v-if="authenticated">
      <a-menu-item key="dashboard">
        <nuxt-link to="/dashboard">{{ user.name }}</nuxt-link>
      </a-menu-item>
      <a-menu-item key="logout">
        <a @click.prevent="logout">Logout</a>
      </a-menu-item>
    </template>
  </a-menu>
</template>

<script>
  export default {
    methods: {
      logout() {
        this.$auth.logout();
      }
    }
  }

</script>
