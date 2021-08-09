<template>
  <v-app>
    <v-app-bar app clipped-left elevate-on-scroll dark color="black" scroll-target="#maincontent" :bottom="$breakpoint.smAndDown">
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title role="button" @click="goTo('/')" class="pl-0">
        <v-img src="/images/logo.png" height="50px" width="50px" contain></v-img>
      </v-toolbar-title>
      <v-toolbar-items v-if="!$breakpoint.smAndDown">
        <v-btn small fab icon @click="goTo('/')">
          <v-icon>home</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items v-if="can['dashboard.view']">
        <v-btn :icon="$vuetify.breakpoint.xs" class="" text @click="goTo('/dashboard')">
          <v-icon :left="!$vuetify.breakpoint.xsOnly">dashboard</v-icon>
          <span v-if="!$vuetify.breakpoint.xsOnly">Dashboard</span>
        </v-btn>
      </v-toolbar-items>
      <v-spacer></v-spacer>
      <v-toolbar-items>
        <v-btn icon text fab small v-if="getPrevious" @click="goTo(getPrevious)">
          <v-icon>mdi-arrow-left</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items>
        <v-btn @click.stop.prevent="reloadCurrent()" text fab small href="?reload=true" icon title="Reload current page">
          <v-icon>mdi-refresh</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items v-if="$breakpoint.smAndUp">
        <v-btn @click="_changeMode()" text small fab icon title="Toggle Dark Mode">
          <v-icon>mdi-theme-light-dark</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items>
        <v-menu bottom left offset-y  transition="fab-transition" :origin="$breakpoint.smAndDown ? 'bottom right' : 'top right'">
          <template v-slot:activator="{ on, attrs }">
            <v-btn icon v-bind="attrs" v-on="on" small fab>
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
            <v-subheader>Manage Account - {{ $page.props.user.name }}</v-subheader>
            <v-list-item :href="route('profile.show')" @click.stop.prevent="goTo(route('profile.show'))">
              <v-list-item-avatar>
                <v-img :src="$user.profile_photo_url"></v-img>
              </v-list-item-avatar>
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
      </v-toolbar-items>
    </v-app-bar>
    <v-navigation-drawer :bottom="$breakpoint.smAndDown" v-model="drawer" :permanent="drawer && !$breakpoint.smAndDown" app clipped>
      <v-list nav dense>
        <v-list-item @click.native.stop.prevent="goTo('/dashboard')" href="/dashboard">
          <v-list-item-icon>
            <v-icon>mdi-view-dashboard</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-spacer></v-spacer>
        <v-list-item v-for="uri in mylinks" :key="uri.path" :href="uri.path" @click.stop.prevent="goTo(uri.path)" :title="uri.title" v-show="can[uri.permission]">
          <v-list-item-icon>
            <v-icon :color="uri.color == 'default' ? 'orange' : uri.color">{{ uri.icon }}</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ uri.name }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-spacer></v-spacer>
        <v-list-item href="/admin" @click.stop.prevent="goTo('/admin')" title="Admin" v-if="$admin">
          <v-list-item-icon>
            <v-icon color="red">security</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Admin</v-list-item-title>
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
        title: 'Dashboard'
      }
    },
    data () {
      return {
        drawer: false,
        mylinks: [
          // {
          //   name: 'Service Requests',
          //   title: 'View Requests',
          //   path: '/service_requests',
          //   permission: 'service_requests.index',
          //   color: 'default',
          //   icon: 'mdi-map-marker-plus-outline'
          // },
        ]
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
