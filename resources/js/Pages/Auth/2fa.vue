<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap class="justify-center align-center fh">
        <v-flex xs11 sm9 md7>
          <h3 class="text-center">Verify Email</h3>
          <v-card class="elevation-24">
            <v-card-text>
              <p v-if="! recovery">Please confirm access to your account by entering the authentication code provided by your authenticator application.</p>
              <p v-if="recovery">Please confirm access to your account by entering one of your emergency recovery codes.</p>
              <form @submit.prevent="sendCode" ref="twofaform">
                <v-text-field label="Code" type="number" required="required" v-if="!recovery" ref="code" v-model="data.code" autofocus autocomplete="one-time-code"></v-text-field>
                <v-text-field label="Recovery Code" type="text" required="required" v-if="recovery" ref="rcode" v-model="data.code" autocomplete="one-time-code" autofocus></v-text-field>
                <span class="pull-right">
                  <v-btn small class="primary" @click="recovery = !recovery">{{ recovery ? 'Use an authentication code' : 'Use a Recovery Code' }}</v-btn>
                  <v-btn small class="red" type="submit">Login</v-btn>
                </span>
              </form><br>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>

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
        recovery: false,
        data: {
          code: '',
          recovery_code: '',
        }
      }
    },
    methods: {
      sendCode () {
        this.$inertia.post('/two-factor-challenge', this.data)
      },
      logout () {
        this.$inertia.post('/logout')
      }
    },
    watch: {
      'recovery': function (value) {
        if (value) {
          // this.$refs.rcode.focus()
        } else {
          // this.$refs.code.focus()
        }
      }
    }
  }
</script>

<style>
   /**/
</style>
