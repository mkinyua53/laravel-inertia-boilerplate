<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap>
        <v-flex xs12>
          <h3 class="text-center">UserDashboardIndex</h3>
        </v-flex>
      </v-layout>
    </v-container>
  </transition>
</template>

<script>
  // import layout from '@/Layouts/MainLayout'

  export default {
    name: 'UserDashboardIndex',
    //layout,
    props: [],
    components: {},
    metaInfo () {
      return {
        title: 'User Dashboard',
        titleTemplate: '%s | ' + this.$title,
      }
    },
    data () {
      return {}
    },
    methods: {
      somemethod () {},
    },
    created () {
      this.somemethod()
    },
    computed: {},
    watch: {
      //
    }
  }
</script>

<style>
   /**/
</style>
