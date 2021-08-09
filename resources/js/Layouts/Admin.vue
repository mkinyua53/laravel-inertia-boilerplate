<template>
  <v-app>
    <v-app-bar app clipped-left color="black" dark :bottom="$breakpoint.smAndDown" scroll-target="#maincontent">
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title role="button" @click="goTo('/')">
        <v-img src="/images/logo.png" height="50px" width="50px" contain></v-img>
      </v-toolbar-title>
      <v-toolbar-items v-if="$breakpoint.smAndUp">
        <v-btn small fab icon @click="goTo('/')">
          <v-icon>home</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items v-if="can['dashboard.view']">
        <v-btn :icon="$vuetify.breakpoint.xsOnly" class="" :fab="$vuetify.breakpoint.xsOnly" text @click="goTo('/dashboard')">
          <v-icon :left="!$vuetify.breakpoint.xsOnly">dashboard</v-icon>
          <span v-if="!$vuetify.breakpoint.xsOnly">Dashboard</span>
        </v-btn>
      </v-toolbar-items>
      <v-spacer></v-spacer>
      <v-toolbar-items>
        <v-btn @click="reloadCurrent()" text small icon fab title="Reload current page">
          <v-icon>mdi-refresh</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items v-if="$breakpoint.smAndUp">
        <v-btn @click="_changeMode()" text small icon fab title="Toggle Dark Mode">
          <v-icon>mdi-theme-light-dark</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-menu bottom left offset-y transition="fab-transition" :close-on-content-click="false" :origin="$breakpoint.smAndDown ? 'bottom right' : 'top right'">
        <template v-slot:activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on">
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </template>
        <v-list>
          <v-list-item @click="_changeMode()" title="Toggle Dark Mode" v-if="!$breakpoint.smAndUp">
            <v-list-item-icon>
              <v-icon>mdi-theme-light-dark</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Toggle Dark Mode</v-list-item-title>
              <v-list-item-subtitle>Change between dark mode and light mode</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-subheader>Manage Account</v-subheader>
          <v-list-item :href="route('profile.show')" @click.stop.prevent="goTo(route('profile.show'))">
            <v-list-item-content>
              <v-list-item-title>View Profile</v-list-item-title>
              <v-list-item-subtitle>Change password, enable 2FA, etc</v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-list-item v-if="$page.props.jetstream.canCreateTeams">
            <v-list-item-content>
              <inertia-link :href="route('teams.create')">Create New Team</inertia-link>
            </v-list-item-content>
          </v-list-item>
          <v-divider></v-divider>
          <v-list-item @click="logout">
            <v-list-item-avatar>
              <v-icon>fa-sign-out</v-icon>
            </v-list-item-avatar>
            <v-list-item-content>Logout</v-list-item-content>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>
    <v-navigation-drawer v-model="drawer" app clipped :permanent="drawer && !$breakpoint.smAndDown"  :bottom="$breakpoint.smAndDown">
      <v-list dense nav>
        <v-list-item @click.native="getprofile">
          <v-list-item-icon>
            <v-icon>mdi-account</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ $page.props.user.name }}</v-list-item-title>
            <v-list-item-subtitle>Change password, enable 2FA, etc</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="goTo('/admin')" title="Admin Info">
          <v-list-item-icon>
            <v-icon color="red">security</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Admin Dash</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="goTo('/admin/users')" title="View Users">
          <v-list-item-icon>
            <v-icon color="red">mdi-account-group</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Users</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="goTo('/admin/roles')" title="Roles">
          <v-list-item-icon>
            <v-icon color="orange">mdi-police-badge</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Roles</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="goTo('/admin/permissions')" title="View Available Laboratory Tests">
          <v-list-item-icon>
            <v-icon color="orange">mdi-shield-lock-outline</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Permissions</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>
        <v-list-item @click="goTo('/dashboard')" title="Dashboard" v-if="can['dashboard.view']">
          <v-list-item-icon>
            <v-icon color="red">mdi-tablet-dashboard</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-main id="maincontent">
      <message-bar></message-bar>
      <slot></slot>
    </v-main>
  </v-app>
</template>
<script>
  export default {
    name: 'main-layout',
    metaInfo () {
      return {
        title: 'Dashboard',
        titleTemplate: '%s | ' + this.$title
      }
    },
    data () {
      return {
        drawer: false,
      }
    },
    methods: {
      logout () {
        this.$cookie.set('loggedout')
        this.$inertia.post('/logout')
        // axios.post(route('logout').url()).then(response => {
        //   window.location = '/';
        // })
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
