<template>
  <transition name="slide-fade">
    <div fluid>
      <v-dialog v-model="edit" :fullscreen="$breakpoint.xsOnly">
        <v-card>
          <v-card-title class="teal white--text">
            <v-btn class="pa-0 mt-0" icon flat @click="edit = false" title="Close">
              <v-icon>fa-arrow-left</v-icon>
            </v-btn>
            <h4 class="text-center">Edit</h4>
          </v-card-title>
          <v-card-text>
            <form @submit.prevent="confirmPassword">
              <v-text-field label="Enter your password to continue" type="password" v-model="password.password"></v-text-field>
            </form>
          </v-card-text>
          <v-card-actions>
            <v-btn class="pa-0" flat @click="edit = false" title="Close">Close</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </transition>
</template>
<script>
  export default {
    name: 'ConfirmsPassword',
    data () {
      return {
        startconfirming: false,
      }
    },
    methods: {
      somemethod () {
        axios.get(route('password.confirmation').url()).then(response => {
            if (response.data.confirmed) {
                this.$emit('confirmed');
            } else {
                this.confirmingPassword = true;
                this.form.password = '';

                setTimeout(() => {
                    this.$refs.password.focus()
                }, 250)
            }
        })
      }
    },
    created () {
      this.startconfirming =  true
    },
    computed: {
      //
    },
    watch: {
      //
    }
  }
</script>
<style>
   /**/
</style>
