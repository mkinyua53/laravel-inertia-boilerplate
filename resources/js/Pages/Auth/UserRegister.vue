<template>
  <transition name="custom-classes-transition" enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
    <v-container fluid id="body">
      <v-layout wrap class="justify-center align-center fh">
        <v-flex xs11 sm10 md5 class="">
          <register-page></register-page>
          <v-btn text small href="/privacy-policy" @click.stop.prevent="goTo('/privacy-policy')">Privacy Policy</v-btn>
          <v-btn text small href="/terms-of-use" @click.stop.prevent="goTo('/terms-of-use')">Terms Of Use</v-btn>
        </v-flex>
      </v-layout>
    </v-container>
  </transition>
</template>

<script>
import layout from '@/Layouts/Guest'
import RegisterPage from '@/Pages/Auth/components/Register'

  export default {
    name: 'Register',
    layout,
    props: [
      //
    ],
    components: {
      RegisterPage,
    },
    metaInfo () {
      return {
        title: 'User Register',
        titleTemplate: '%s | ' + this.title
      }
    },
  }
</script>

<style>
  #body {
    background-image: url('/img/header-bg.jpg');
    background-attachment: fixed;
    background-size: cover;
  }
</style>
