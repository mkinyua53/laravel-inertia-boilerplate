<template>
  <transition name="slide-fade">
    <v-container fluid>
      <terms-of-use></terms-of-use>
    </v-container>
  </transition>
</template>

<script>
  import layout from '@/Layouts/Guest'
  import TermsOfUse from '@/Components/components/termsofuse'

  export default {
    name: 'TermsofUsePage',
    layout,
    components: {
      TermsOfUse,
    },
    metaInfo () {
      return {
        title: 'Terms of Use',
        titleTemplate: '%s | ' + this.$title,
      }
    },
  }
</script>
