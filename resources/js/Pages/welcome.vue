<template>
  <transition name="slide-fade">
    <div>
      <v-container fluid class="p-header">
        <v-layout wrap>
          <v-flex xs12>
            <div class="layout wrap justify-center p-header align-center">
              <v-img lazy-src="/images/placeholder.gif" src="/images/logo.png" height="50vh" class="p-image"></v-img>
            </div>
          </v-flex>
        </v-layout>
      </v-container>
      <v-container fluid class="p-header2">
        <v-layout wrap justify-center align-center>
          <v-flex xs12 sm6 md6 order-md1 order-sm1 order-xs1 class="pa-5">
            <v-card class="elevation-24" href="/register">
              <v-card-text class="text-center">
                <span>Sign-up</span>
              </v-card-text>
            </v-card>
          </v-flex>
          <v-flex xs12 sm6 md6 order-md1 order-sm1 order-xs2 class="pa-5">
            <v-card class="elevation-24" href="/login" @click.stop.prevent="goTo('/login')">
              <v-card-text class="text-center">
                <span>Log-In</span>
              </v-card-text>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </div>
  </transition>
</template>

<script>
  import guestLayout from '@/Layouts/Guest'

  export default {
    name: 'Welcome',
    layout: guestLayout,
    components: {
      //
    },
    props: [
      'packages'
    ],
    metaInfo () {
      return {
        title: 'Welcome',
        titleTemplate: '%s | ' + this.$title,
      }
    },
    data () {
      return {
        data: '',
      }
    },
    computed: {
      pInfo () {
        return {
          backgroundColor: this.$vuetify.theme.isDark ? '#1e1e1e' : 'white',
          color: this.$vuetify.theme.isDark ? 'white' : 'black',
          fontSize: 'larger',
          margin: '2px',
          padding: '10px',
        }
      }
    },
  }
</script>
