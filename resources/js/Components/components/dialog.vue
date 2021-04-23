<template>
  <transition name="slide-fade">
    <v-dialog v-model="open" :fullscreen="fullscreen" persistent>
      <v-card>
        <v-card-title class="primary white--text pb-1 pt-1 px-1">
          <h4 class="text-center">
            <slot name="title"></slot>
          </h4>
          <v-spacer></v-spacer>
          <v-btn @click="$vuetify.theme.dark = !$vuetify.theme.dark" text small icon title="Toggle Dark Mode">
            <v-icon>fa-tint</v-icon>
          </v-btn>
          <v-btn class="pa-0 mt-0" icon text @click="$emit('close')" title="Close">
            <v-icon>fa-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <slot/>
        </v-card-text>
        <v-card-actions class="pb-0 pt-0">
          <v-btn class="pa-0" text @click="$emit('close')" title="Close">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </transition>
</template>
<script>
  export default {
    name: 'DialogComponent',
    props: {
      fullscreen: {
        type: Boolean,
        default: false
      },
      open: {
        type: Boolean,
        required: true,
        default: false
      }
    },
    data () {
      return {
        openModal: this.open
      }
    },
    mounted: function () {
      document.addEventListener("keydown", (e) => {
        if (e.keyCode == 27) {
          this.$emit('close');
        }
      });
    }
  }
</script>
<style scoped>
  .v-card__actions {
    border-top: 1px solid;
  }
  .v-card__title {
    position: sticky;
    top: 0;
    z-index: 1;
  }
</style>
