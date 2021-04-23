<template>
  <div class="layout justify-space-between" id="my-layout">
    <div class="my-title" v-html="detail[0]"></div>
    <div class="pl-1" v-html="detail[1]"></div>
  </div>
</template>

<script>
  export default {
    name: 'MyDetails',
    props: {
      detail: {
        type: Array,
        required: true
      }
    },
  }
</script>

<style>
  #my-layout {
    border-bottom: 1px ridge rgba(199, 135, 135, 0.109);
  }
  .my-title {
    font-size: larger;
    font-weight: bold;
    flex: 1 1 auto;
  }
</style>
