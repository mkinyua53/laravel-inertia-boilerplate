<template>
  <transition name="slide-fade">
    <v-dialog :value="value" :fullscreen="fullscreen"  @click:outside="$emit('close')">
      <v-card>
        <v-card-title class="primary white--text pa-1">
          <h4 class="text-center">
            <slot name="title"></slot>
          </h4>
          <v-spacer></v-spacer>
          <v-btn small icon fab title="Refresh page" @click="$inertia.reload()" v-show="reload">
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
          <v-btn @click="$vuetify.theme.dark = !$vuetify.theme.dark" fab text small icon title="Toggle Dark Mode" v-show="theme">
            <v-icon>fa-tint</v-icon>
          </v-btn>
          <v-btn class="pa-0 mt-0" icon text @click="$emit('close')" fab title="Close" small>
            <v-icon>fa-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <slot/>
        </v-card-text>
        <v-card-actions class="pb-0 pt-0">
          <v-btn class="" text @click="$emit('close')" title="Close">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </transition>
</template>
<script>
  export default {
    name: 'DialogComponent',
    model: {
      props: 'value',
      event: 'close'
    },
    props: {
      fullscreen: {
        type: Boolean,
        default: false
      },
      value: {
        type: Boolean,
        default: false
      },
      reload: {
        type: Boolean,
        default: true
      },
      theme: {
        type: Boolean,
        default: true
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
