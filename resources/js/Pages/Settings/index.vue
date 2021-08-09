<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap>
        <v-flex xs12>
          <h3 class="text-center">Settings</h3>
        </v-flex>
      </v-layout>
    </v-container>
  </transition>
</template>

<script>
  import layout from '@/Layouts/MainLayout.vue'

  export default {
    name: 'Settings',
    metaInfo () {
      return {
        title: 'Settings',
        titleTemplate: '%s | ' + this.$title,
      }
    },
    layout,
  }
</script>
<style>
   /**/
</style>
