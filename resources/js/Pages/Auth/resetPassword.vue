<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap class="justify-center align-center fh">
        <v-flex xs11 sm9 md7>
          <h3 class="text-center">Reset Password</h3>
          <v-card class="elevation-24">
            <v-card-text>
              <form @submit.prevent="resetPass">
                <v-text-field label="Email" name="email" v-model="user.email" required="required" type="email" :error-messages="errors.email"></v-text-field>
                <input type="hidden" name="_token" :value="_token" />
                <input type="hidden" name="token" :value="token">
                <v-text-field type="password" label="New Password" v-model="user.password" required="required" name="password" :error-messages="errors.password"></v-text-field>
                <v-text-field type="password" label="Confirm Password" v-model="user.password_confirmation" required="required" name="password"></v-text-field>
                <v-btn type="submit" small class="primary pull-right" :loading="user.processing">Reset Password</v-btn>
              </form>
              <br>
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </transition>
</template>

<script>
  import layout from '@/Layouts/Guest'

  export default {
    name: 'ResetPassword',
    layout,
    props: [
      'errors'
    ],
    components: {},
    metaInfo () {
      return {
        title: 'Reset Password',
        titleTemplate: '%s | ' + this.$title,
      }
    },
    data () {
      return {
        user: this.$inertia.form({
          token: this.token,
          password: '',
          password_confirmation: '',
          _token: this._token,
          email: ''
        })
      }
    },
    methods: {
      resetPass () {
        this.user.post('/reset-password')
      },
    },
    created () {
      this.$nextTick(() => {
        var query = location.search
        var email = query.substring(query.indexOf('=') + 1)
        this.user.email = decodeURIComponent(email)
        this.user.token = this.token
        this.user._token = this._token
      })
    },
    computed: {
      _token () {
        return document.querySelector("meta[name='csrf-token']").getAttribute('content')
      },
      token () {
        var pathname = location.pathname
        return pathname.replace('/reset-password/', '')
      }
    }
  }
</script>
