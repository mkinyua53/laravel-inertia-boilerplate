<template>
  <v-container fluid>
    <v-layout wrap class="justify-center align-center" style="height: calc(100vh - 120px);">
      <v-flex xs11 md6 sm8>
        <v-card class="elevation-24">
          <v-card-title style="border-bottom: 1px solid">
            <span class="text-center" id="title">{{ title }}</span>
            <v-spacer></v-spacer>
            <v-icon>&#128678;</v-icon>
          </v-card-title>
          <v-card-text class="py-10">
            <p class="text-center" id="message">{{ description }}</p>
          </v-card-text>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import layout from '@/Layouts/Guest'

export default {
  name: 'Error-Page',
  layout,
  props: {
    status: Number,
    error: String,
  },
  metaInfo () {
    return {
      title: this.title,
      titleTemplate: '%s | ' + this.$title
    }
  },
  computed: {
    title() {
      return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
      }[this.status]
    },
    description() {
      return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
      }[this.status]
    },
  },
  created () {
    this.$nextTick(() => {
      console.error('error', this.error)
    })
  }
}
</script>

<style scoped>
  #message {
    color: red;
    font-size: 4vw;
    line-height: 1;
  }
</style>
