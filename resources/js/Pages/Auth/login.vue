<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap class="justify-center align-center fh">
        <v-flex xs12 sm6 md5>
          <login-page :errors="errors"></login-page>
          <v-btn text small href="/privacy-policy" @click.stop.prevent="goTo('/privacy-policy')">Privacy Policy</v-btn>
          <v-btn text small href="/terms-of-use" @click.stop.prevent="goTo('/terms-of-use')">Terms Of Use</v-btn>
        </v-flex>
      </v-layout>
    </v-container>
  </transition>
</template>

<script>
  import layout from '@/Layouts/Guest'
  import LoginPage from '@/Pages/Auth/components/login'

  export default {
    name: 'Login',
    layout,
    props: [
      'errors'
    ],
    components: {
      LoginPage,
    },
    metaInfo () {
      return {
        title: 'Login',
        titleTemplate: '%s | ' + this.$title,
      }
    },
    data () {
      return {
        user: this.$inertia.form({
          email: '',
          password: ''
        }),
        pt: false,
        loading: false
      }
    },
    methods: {
      login (user) {
        this.user.post('/login')
      },
      turnofftext () {
        if (this.pt) {
          var vm = this
          setTimeout(function () {
            vm.pt = false
          }, 1500)
        }
      }
    },
    computed: {
      _token () {
        return document.querySelector("meta[name='csrf-token']").getAttribute('content')
      }
    },
    watch: {
      'pt': 'turnofftext'
    }
  }
</script>
