<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap>
        <v-flex xs12>
          <v-card class="elevation-24">
            <v-card-title>
              <v-spacer></v-spacer>
              <span>Register</span>
            </v-card-title>
            <v-card-text>
              <form ref="register" @submit.prevent="registerUser">
                <v-text-field label="Name" name="Name" :rules="[form_rules.required]" required="required" v-model="user.name" :error-messages="_error('name', 'createNewUser')"></v-text-field>
                <v-text-field label="Email" type="email" name="Email" required="required" v-model="user.email" :rules="[form_rules.required, form_rules.email]" :error-messages="_error('email', 'createNewUser')"></v-text-field>
                <v-text-field label="Username" name="username" v-model="user.username" :error-messages="_error('username', 'createNewUser')"></v-text-field>
                <v-text-field label="Password" v-model="user.password" name="Password" :rules="[form_rules.required]" min-length="8" type="password" :error-messages="_error('password', 'createNewUser')"></v-text-field>
                <v-text-field type="password" label="Confirm Password" v-model="user.password_confirmation" name="Password Confirmation" :rules="[form_rules.required]" min-length="8"></v-text-field>
                <v-btn type="submit" small color="primary" class="pull-right">Register</v-btn>
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

  export default {
    name: 'Register',
    //layout,
    props: [
      'errors'
    ],
    components: {},
    metaInfo () {
      return {
        title: 'Register',
        titleTemplate: '%s | ' + this.$title,
      }
    },
    data () {
      return {
        user: this.$inertia.form({
          name: '',
          email: '',
          username: '',
          password: '',
          password_confirmation: '',
        }, {
          bag: 'createNewUser'
        })
      }
    },
    methods: {
      registerUser () {
        this.user.post(route('register'))
      },
    },
  }
</script>

<style>
   /**/
</style>
