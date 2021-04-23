<template>
  <v-app>
    <v-app-bar app elevate-on-scroll scroll-target="#main-content" :clipped-left="aside">
      <slot name="toolbar" @open-drawer="drawer = !drawer"></slot>
      <v-toolbar-items class="px-1">
        <v-btn @click="_changeMode()" text small icon title="Toggle Dark Mode">
          <v-icon>mdi-theme-light-dark</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items v-if="$page.props.user">
        <v-menu bottom left offset-y>
          <template v-slot:activator="{ on, attrs }">
            <v-btn :icon="$breakpoint.xsOnly" v-bind="attrs" v-on="on" text>
              <v-icon :left="!$breakpoint.xsOnly">mdi-account</v-icon>
              <span v-if="!$breakpoint.xsOnly">{{ $page.props.user.name }}</span>
            </v-btn>
          </template>
          <v-list dense>
            <v-subheader>Manage Account</v-subheader>
            <v-list-item @click.stop.prevent="goTo(route('profile.show'))" :href="route('profile.show')">
              <v-list-item-avatar>
                <v-icon>mdi-account</v-icon>
              </v-list-item-avatar>
              <v-list-item-content>
                <span>User Profile</span>
              </v-list-item-content>
            </v-list-item>
            <v-list-item @click.stop.prevent="goTo(route('profile.show'))" :href="route('profile.show')">
              <v-list-item-avatar>
                <v-icon>mdi-needle</v-icon>
              </v-list-item-avatar>
              <v-list-item-content>
                <span>Patient Profile</span>
              </v-list-item-content>
            </v-list-item>
            <v-list-item @click.stop.prevent="goTo(route('user.professional.show'))" :href="route('user.professional.show')">
              <v-list-item-avatar>
                <v-icon>mdi-stethoscope</v-icon>
              </v-list-item-avatar>
              <v-list-item-content>
                <span>Professional Profile</span>
              </v-list-item-content>
            </v-list-item>
            <v-list-item v-if="$page.props.jetstream.canCreateTeams" @click.stop.prevent="goTo(route('teams.create'))" :href="route('teams.create')">
              <v-list-item-content>
                <span>Create New Team</span>
              </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
            <v-list-item @click.stop.prevent="logout" href="/logout">
              <v-list-item-avatar>
                <v-icon>mdi-logout</v-icon>
              </v-list-item-avatar>
              <v-list-item-content>Logout</v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-toolbar-items>
      <v-toolbar-items v-else>
        <v-menu bottom left>
          <template v-slot:activator="{ on, attrs }">
            <v-btn v-bind="attrs" v-on="on" text>
              <v-icon left>mdi-account</v-icon> Login/Register
            </v-btn>
          </template>
          <v-list>
            <v-list-item @click.stop.prevent="goTo('/auth/login')" href="/auth/login">
            <v-list-item-avatar>
              <v-icon>mdi-login</v-icon>
            </v-list-item-avatar>
              <v-list-item-content>
                Login
              </v-list-item-content>
            </v-list-item>
            <v-list-item href="/register">
              <v-list-item-avatar>
                <v-icon>mdi-account-plus</v-icon>
              </v-list-item-avatar>
              <v-list-item-content>
                Register
              </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
            <v-list-item role="button" @click.stop.prevent="goTo('/client/register')" href="/client/register">
              <v-list-item-avatar>
                <v-icon>mdi-needle</v-icon>
              </v-list-item-avatar>
              <v-list-item-content>
                Register as a Patient
              </v-list-item-content>
            </v-list-item>
            <v-list-item role="button" @click.stop.prevent="goTo('/professional/register')" href="/professional/register">
              <v-list-item-avatar>
                <v-icon>mdi-stethoscope</v-icon>
              </v-list-item-avatar>
              <v-list-item-content>
                Register as a Professional
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-toolbar-items>
    </v-app-bar>
    <v-navigation-drawer va="drawer" app clipped :permanent="aside" expand-on-hover v-if="aside">
      <slot name="navigation-bar"></slot>
    </v-navigation-drawer>
    <v-main id="main-content">
      <message-bar></message-bar>
      <slot name="content"></slot>
    </v-main>
  </v-app>
</template>
<script>
  export default {
    name: 'main-layout',
    metaInfo () {
      return {
        title: 'Welcome',
        titleTemplate: '%s | ' + this.$title
      }
    },
    props: {
      aside: {
        type: Boolean,
        default: false
      }
    },
    data () {
      return {
        drawer: false,
      }
    },
    methods: {
      logout () {
        axios.post(route('logout').url()).then(response => {
          window.location = '/';
        })
      },
      getprofile () {
        this.$inertia.visit('/user/profile')
      },
      goToLink (url) {
        this.$inertia.visit(url)
      },
      reloadCurrent () {
        this.$inertia.reload({ preserveScroll: true, preserveState: false })
      }
    },
    created () {
      //
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
  .hidden {
    display: none;
  }
  [role="button"] {
    cursor: pointer;
  }
  @media screen and (min-width: 703px) {
    .v-dialog:not(.v-dialog--fullscreen) {
      max-width: 60vw;
    }
  }
  .v-dialog > .v-card > .v-card__text {
    padding: 1px;
  }
  .v-dialog {
    margin: 5px;
  }
  .v-card__title.primary {
    color: white;
  }
</style>
