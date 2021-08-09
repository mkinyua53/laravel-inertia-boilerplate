<template>
  <v-app>
    <v-app-bar app clipped-left elevate-on-scroll color="black" dark scroll-target="#maincontent" :bottom="$breakpoint.smAndDown">
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title role="button" href="/" @click.stop.prevent="goTo('/')">
        <v-img src="/images/logo.png" width="50px" height="50px" contain></v-img>
      </v-toolbar-title>
      <v-toolbar-items v-if="$breakpoint.smAndUp">
        <v-btn small icon fab href="/" @click.stop.prevent="goTo('/')">
          <v-icon>home</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items v-if="can['dashboard.view']">
        <v-btn :icon="$breakpoint.xsOnly" class="" href="/dashboard" :fab="$breakpoint.xsOnly" text @click.stop.prevent="goTo('/dashboard')">
          <v-icon :left="!$breakpoint.xsOnly">dashboard</v-icon>
          <span v-if="!$breakpoint.xsOnly">Dashboard</span>
        </v-btn>
      </v-toolbar-items>
      <v-spacer></v-spacer>
      <v-toolbar-items>
        <v-btn @click="reloadCurrent()" text small fab icon title="Reload current page">
          <v-icon>mdi-refresh</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items v-if="$breakpoint.smAndUp">
        <v-btn @click="_changeMode()" text small icon fab title="Toggle Dark Mode">
          <v-icon>mdi-theme-light-dark</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items>
        <v-menu bottom left offset-y transition="fab-transition" :origin="$breakpoint.smAndDown ? 'bottom right' : 'top right'">
          <template v-slot:activator="{ on, attrs }">
            <v-btn icon v-bind="attrs" v-on="on" fab>
              <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item @click.stop.prevent="_changeMode()" title="Toggle Dark Mode" v-if="!$breakpoint.smAndUp">
              <v-list-item-icon>
                <v-icon>mdi-theme-light-dark</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Toggle Dark Mode</v-list-item-title>
                <v-list-item-subtitle>Change between dark mode and light mode</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item @click="turnonnotif" title="Turn on Notification" v-if="!webpush && !webpushsuccess">
              <v-list-item-icon>
                <v-icon color="red">mdi-bell-plus-outline</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Turn on Notifications</v-list-item-title>
                <v-list-item-subtitle>Turn on webpush</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-subheader>Manage Account - {{ $page.props.user.name }}</v-subheader>
            <v-list-item :href="route('profile.show')" @click.prevent="goTo(route('profile.show'))">
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
              <v-list-item-icon>
                <v-icon>fa-sign-out</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Logout</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-toolbar-items>
    </v-app-bar>
    <v-navigation-drawer v-model="drawer" :bottom="$breakpoint.smAndDown" app clipped :permanent="drawer && !$breakpoint.smAndDown">
      <v-list dense nav>
        <v-list-item href="/user/profile" @click.stop.prevent.native="getprofile" role="button">
          <v-list-item-icon>
            <v-icon>mdi-account</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ $page.props.user.name }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item href="/user/client" @click.stop.prevent="goTo('/user/client')" title="Client" v-if="!$user.professional">
          <v-list-item-icon>
            <v-icon color="red">mdi-account-group</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Client Profile</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item href="/user/requests" @click.stop.prevent="goTo('/user/requests')" title="User Service Requests" v-if="!$user.professional">
          <v-list-item-icon>
            <v-icon color="red">mdi-message-draw</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Service Request</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item href="/user/professional" @click.stop.prevent="goTo('/user/professional')" title="Professional profile" v-if="!$user.client">
          <v-list-item-icon>
            <v-icon color="orange">mdi-account-tie</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Professional Profile</v-list-item-title>
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
      <template v-slot:append>
        <div class="pa-1">
          <v-btn text @click="logout" small class="pull-right">
            Logout
          </v-btn>
        </div>
      </template>
    </v-navigation-drawer>
    <v-main id="maincontent">
      <message-bar></message-bar>
      <v-snackbar
        :timeout="10000"
        v-model="snackbar"
        color="primary"
        absolute
        left
        rounded=""
        top
        multi-line
      >
        <span v-html="message"></span>
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
      <slot></slot>
    </v-main>
  </v-app>
</template>

<script>

  export default {
    name: 'user-layout',
    metaInfo () {
      return {
        title: 'User Dashboard'
      }
    },
    data () {
      return {
        drawer: false,
        webpushsuccess: false,
        snackbar: false,
        message: ''
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
      },
      turnonnotif () {
        if (!('Notification' in window)) {
          this.message = 'Your browser doesn\'t support Notifications'
          this.snackbar = true
          return
        }
        if (Notification.permission === 'denied') {
          this.message = 'You have explicitly denied notifications for this app. <br>Please reset notifications settings for this url ' + window.location.origin
          this.snackbar = true
        }
        window.dispatchEvent(new CustomEvent('notifyme'));
      }
    },
    computed: {
      professional () {
        return this.$user.professional
      },
      webpush () {
        return ('Notification' in window) && Notification.permission === 'granted'
      }
    },
    created () {
      var vm = this
      window.addEventListener('notifyon', function () {
        vm.webpushsuccess = true
      })
    },
    watch: {
      'webpushsuccess': function (value) {
        if (value) {
          this.message = 'Notifications turned on successfully'
          this.snackbar = true
        }
      }
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
