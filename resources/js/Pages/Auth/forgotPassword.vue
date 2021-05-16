<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap class="justify-center align-center fh">
        <v-flex xs11 sm10 md7>
          <h3 class="text-center">Forgot Password</h3>
          <v-card class="elevation-24">
            <!-- <v-card-title>
              <v-spacer></v-spacer>
              Forgot Password
            </v-card-title> -->
            <v-card-text>
              <p>Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
              <form :action="route('password.email')" @submit.prevent="postReq" method="post">
                <input type="hidden" name="_token" :value="_token" />
                <v-text-field label="Email" name="email" required="required" type="email" v-model="password.email" :error-messages="errors.email"></v-text-field>
                <v-btn small :loading="password.processing" type="submit" class="pull-right primary">Email Reset Password Link</v-btn><br>
              </form>
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
    name: 'ForgotPassword',
    layout,
    props: [
      'errors'
    ],
    metaInfo () {
      return {
        title: 'Forgot Password',
        titleTemplate: '%s | ' + this.$title,
      }
    },
    data () {
      return {
        loading: false,
        password: this.$inertia.form({
          email: '',
        }, {
          bag: 'default'
        })
      }
    },
    methods: {
      postReq () {
        this.password.post(route('password.email'), {
          onSuccess: (page) => {
            if (!page.props.errors.email) {
              swal({ title: 'Link sent to your email', type: 'success' })
            }
          }
        })
      },
    },
    computed: {
      _token () {
        return document.querySelector("meta[name='csrf-token']").getAttribute('content')
      }
    }
  }
</script>
