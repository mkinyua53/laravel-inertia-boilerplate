<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap class="justify-center align-center fh">
        <v-flex xs12 sm6 md5>
          <h3 class="text-center">Login</h3>
          <v-card class="elevation-22">
            <v-card-text>
              <form method="POST" action="/login" ref="login-form">
                <p class="red--text" v-if="errors.email">{{ errors.email }}</p>
                <input type="hidden" name="_token" :value="_token" />
                <v-text-field label="Username / Email" v-model="user.email" name="email" required="required"></v-text-field>
                <v-text-field label="Password" v-model="user.password" name="password" :type="pt ? 'text' : 'password'" required="required">
                  <v-icon slot="append" @click="pt = !pt">
                    {{ pt ? 'mdi-eye-off' : 'mdi-eye' }}
                  </v-icon>
                </v-text-field>
                <v-btn type="submit" small class="primary pull-right" :loading="user.processing">Login</v-btn>
              </form>
            </v-card-text>
            <v-card-actions>
              <v-btn small text href="/forgot-password">Forgot Password?</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
          <v-btn text small href="/privacy-policy">Privacy Policy</v-btn>
          <v-btn text small href="/terms-of-use">Terms Of Use</v-btn>
        </v-flex>
      </v-layout>
    </v-container>
  </transition>
</template>

<script>
  import layout from '@/Layouts/Guest'

  export default {
    name: 'Login',
    layout,
    props: [
      'errors'
    ],
    components: {},
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
        pt: false
      }
    },
    methods: {
      somemethod () {},
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
    created () {
      this.somemethod()
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

<style>
  .fh {
    height: calc(100vh - 100px);
  }
</style>
