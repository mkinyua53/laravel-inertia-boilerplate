<template>
  <transition name="slide-fade">
    <v-container fluid>
      <privacy-policy></privacy-policy>
    </v-container>
  </transition>
</template>

<script>
  import layout from '@/Layouts/Guest'
  import PrivacyPolicy from '@/Components/components/privacypolicy'

  export default {
    name: 'PrivacyPolicyPage',
    layout,
    components: {
      PrivacyPolicy,
    },
    metaInfo () {
      return {
        title: 'Privacy Policy',
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
      '$route': 'somemethod',
    }
  }
</script>

<style>
   /**/
</style>
