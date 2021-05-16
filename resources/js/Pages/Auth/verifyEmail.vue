<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap class="justify-center align-center fh">
        <v-flex xs11 sm9 md7>
          <h3 class="text-center">Verify Email</h3>
          <v-card class="elevation-24">
            <v-card-text>
              <p>Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>
              <p class="green-text green accent-1" v-if="message" v-html="message"></p>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn small class="pull-right primary" @click="resendlink()" :loading="loading">Resend Verification Email</v-btn>
              <v-btn small class="pull-right red" @click="logout" type="submit">Logout</v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </transition>
</template>

<script>
  import layout from '@/Layouts/Guest'

  export default {
    name: 'VerifyEmail',
    layout,
    props: [],
    components: {},
    metaInfo () {
      return {
        title: 'Verify Email',
        titleTemplate: '%s | ' + this.$title,
      }
    },
    data () {
      return {
        message: '',
        loading: false,
      }
    },
    methods: {
      resendlink () {
        this.loading = true
        var _token = document.querySelector("meta[name='csrf-token']").getAttribute('content')
        this.$inertia.post(route('verification.send'), { _token }, {
          onSuccess: (page) => {
            if (page.props.status == 'verification-link-sent') {
              this.message = 'A new verification link has been sent to the email address you provided during registration.'
            }
          },
          onFinish: () => {
            this.loading = false
          }
        })
      },
      logout () {
        this.$inertia.post('/logout')
      }
    },
  }
</script>

<style>
   /**/
</style>
