<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap class="justify-center align-center">
        <v-flex xs12>
          <v-card class="elevation-22">
            <v-card-title class="py-0">
              <v-spacer></v-spacer>
              <h3 class="my-0">Login</h3>
            </v-card-title>
            <v-card-text>
              <form method="POST" action="/login" ref="login-form" @submit.prevent="login(user)">
                <!-- <p class="red--text" v-if="errors.email">{{ errors.email }}</p> -->
                <input type="hidden" name="_token" :value="_token" />
                <v-text-field label="Username / Email" v-model="user.email" name="email" required="required" :error-messages="errors.email"></v-text-field>
                <v-text-field label="Password" v-model="user.password" name="password" :type="pt ? 'text' : 'password'" required="required" :error-messages="errors.password">
                  <v-icon slot="append" @click="pt = !pt">
                    {{ pt ? 'mdi-eye-off' : 'mdi-eye' }}
                  </v-icon>
                </v-text-field>
                <v-btn type="submit" small class="primary pull-right" :loading="user.processing">Login</v-btn>
              </form>
            </v-card-text>
            <v-card-actions>
              <v-btn small text href="/forgot-password" @click.stop.prevent="goTo('/forgot-password')">Forgot Password?</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </transition>
</template>

<script>

  export default {
    name: 'Login',
    props: [
      'errors'
    ],
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
        this.$cookie.delete('loggedout')
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
    },
    created () {
      var request = new Event('loginrequest')
      window.dispatchEvent(request)
    }
  }
</script>

<style>
  .fh {
    height: calc(100vh - 100px);
  }
</style>
