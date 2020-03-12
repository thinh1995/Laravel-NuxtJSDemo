<template>
  <div>
    <a-layout id="components-layout-demo-basic" class="layout">
      <a-layout-header class="header">
        <div class="logo" />
        <Nav />
      </a-layout-header>
      <a-layout-content>
        <div :style="{ background: '#fff', padding: '24px', minHeight: '280px' }">
          <nuxt />
        </div>
      </a-layout-content>
    </a-layout>
  </div>
</template>

<script>
  import Nav from '@/components/Nav'
  export default {
    components: {
      Nav
    }
  }

</script>
<style>
  #components-layout-demo-basic .logo {
    width: 120px;
    height: 31px;
    background: rgba(255, 255, 255, 0.2);
    margin: 16px 28px 16px 0;
    float: left;
  }

  #components-layout-demo-basic .ant-layout-header {
    background: #001529;
    color: #fff;
    height: 58px;
  }

</style>
