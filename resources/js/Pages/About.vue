<template>
  <transition name="slide-fade">
    <div>
      <v-container fluid class="p-header">
        <v-layout wrap>
          <v-flex xs12>
            <div class="layout wrap justify-center p-header align-center">
              <v-img src="/images/logo.png" width="60vh" class="p-image"></v-img>
            </div>
          </v-flex>
        </v-layout>
      </v-container>
      <v-container fluid class="p-bg">
        <v-layout wrap>
          <v-flex xs12>
            <h2 class="text-center title">About Us</h2>
          </v-flex>
        </v-layout>
      </v-container>
    </div>
  </transition>
</template>

<script>
  import layout from '@/Layouts/Guest'

  export default {
    name: 'About',
    layout,
    metaInfo () {
      return {
        title: 'About Us',
        titleTemplate: '%s | ' + this.$title,
      }
    },
  }
</script>
