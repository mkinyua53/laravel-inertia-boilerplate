<template>
  <transition name="slide-fade">
    <v-snackbar
        :timeout="10000"
        v-model="snackbar"
        color="primary"
        absolute
        right
        rounded=""
        top
        multi-line
      >
        <span v-html="$message"></span>
        <template v-slot:action="{ attrs }">
          <v-btn
            color="red"
            text
            v-bind="attrs"
            @click="snackbar = false"
          >
            Close
          </v-btn>
        </template>
      </v-snackbar>
  </transition>
</template>

<script>
  // import layout from '@/Layouts/MainLayout'

  export default {
    name: 'MessageBar',
    data () {
      return {
        snackbar: true
      }
    },
    methods: {
      somemethod () {
        if (!this.$message) {
          this.snackbar = false
        }
      },
    },
    created () {
      this.somemethod()
    },
    computed: {},
    watch: {
      '$message': function (value) {
        if (!value) {
          this.snackbar = false
          return
        }
        var vm = this
        vm.snackbar = false
        setTimeout(function () {
          vm.snackbar = true
        },500)
      },
    }
  }
</script>

<style>
   /**/
</style>
