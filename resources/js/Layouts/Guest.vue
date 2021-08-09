<template>
  <v-app>
    <v-app-bar app elevate-on-scroll dark color="black" scroll-target="#maincontent" :bottom="$breakpoint.smAndDown" id="myheader">
      <!-- <v-app-bar-nav-icon @click="drawer = !drawer" class="hidden"></v-app-bar-nav-icon> -->
      <v-toolbar-title role="button" @click="goTo('/')">
        <v-img src="/images/logo.png" height="50px" width="50px"></v-img>
      </v-toolbar-title>
      <v-toolbar-items v-if="can && can['dashboard.view']">
        <v-btn :icon="$vuetify.breakpoint.xsOnly" class="" text href="/dashboard" @click.stop.prevent="goTo('/dashboard')">
          <v-icon :left="!$vuetify.breakpoint.xsOnly">dashboard</v-icon>
          <span v-if="!$vuetify.breakpoint.xsOnly">Dashboard</span>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items>
        <v-btn text href="/about" @click.stop.prevent="goTo('/about')">About</v-btn>
      </v-toolbar-items>
      <v-spacer></v-spacer>
      <v-toolbar-items class="px-1" v-if="$breakpoint.smAndUp">
        <v-btn @click="_changeMode()" text small fab icon title="Toggle Dark Mode">
          <v-icon>mdi-theme-light-dark</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-toolbar-items v-if="$page.props.user">
        <v-menu bottom left offset-y :close-on-content-click="false" transition="fab-transition" :origin="$breakpoint.smAndDown ? 'bottom right' : 'top right'">
          <template v-slot:activator="{ on, attrs }">
            <v-btn :icon="$breakpoint.xsOnly" v-bind="attrs" :fab="$vuetify.breakpoint.xsOnly" v-on="on" text id="mybutton">
              <v-icon :left="!$breakpoint.xsOnly">mdi-account</v-icon>
              <span v-if="!$breakpoint.xsOnly">{{ $page.props.user.name }}</span>
            </v-btn>
          </template>
          <v-list dense>
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
            <v-list-item @click.stop.prevent="goTo(route('profile.show'))" :href="route('profile.show')">
              <v-list-item-avatar>
                <v-img :src="$user.profile_photo_url"></v-img>
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>User Profile</v-list-item-title>
                <v-list-item-subtitle>Change password, enable 2FA, etc</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item v-if="$page.props.jetstream.canCreateTeams" @click.stop.prevent="goTo(route('teams.create'))" :href="route('teams.create')">
              <v-list-item-content>
                <v-list-item-title>Create New Team</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
            <v-list-item @click.stop.prevent="logout" href="/logout">
              <v-list-item-icon>
                <v-icon>mdi-logout</v-icon>
              </v-list-item-icon>
              <v-list-item-content>Logout</v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-toolbar-items>
      <v-toolbar-items v-else>
        <v-menu bottom left :close-on-content-click="false" transition="fab-transition" :origin="$breakpoint.smAndDown ? 'bottom right' : 'top right'">
          <template v-slot:activator="{ on, attrs }">
            <v-btn v-bind="attrs" v-on="on" text id="mybutton">
              <v-icon left>mdi-account</v-icon> Login Register
            </v-btn>
          </template>
          <v-list dense>
            <v-list-item @click="_changeMode()" title="Toggle Dark Mode" v-if="!$breakpoint.smAndUp">
              <v-list-item-icon>
                <v-icon>mdi-theme-light-dark</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Toggle Dark Mode</v-list-item-title>
                <v-list-item-subtitle>Change between dark mode and light mode</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-list-item href="/login" @click.stop.prevent="goTo('/login')">
              <v-list-item-icon>
                <v-icon>mdi-login</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Login</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item href="/register" @click.prevent.stop="goTo('/register')">
              <v-list-item-icon>
                <v-icon>mdi-account-plus</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Register</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-toolbar-items>
    </v-app-bar>
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
        title: 'Welcome',
        titleTemplate: '%s | ' + this.$title
      }
    },
    props: [
      'user'
    ],
    data () {
      return {
        drawer: true,
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
      const element = document.querySelector("iframe");
      // console.log(element)
      if (element) {
        element.remove()
      }
      window.addEventListener('loginresponse', function (e) {
        var url = e.detail.url
        if (url) {
          var path = '/two-factor-challenge'
          var index = url.indexOf(path)
          if (index > -1) {
            swal({
              title: '',
              text: 'Please enter your authentication code to complete the login',
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: 'lightblue',
              confirmButtonText:'Enter Code',
              cancelButtonText: 'Later',
            },
            function () {
              this.goTo(path)
            }.bind(this))
          } else {
            this.message = 'Attempting login ...'
            this.snackbar = true
            this.goTo(url)
            // this.$inertia.reload({ preserveScroll: true, preserveState: false })
          }
        } else {
          this.message = 'Attempting login ...'
          this.snackbar = true
          this.$inertia.reload({ preserveScroll: true, preserveState: false })
        }
      }.bind(this))
    },
    mounted () {
      this.$nextTick(() => {
        let button = document.getElementById('mybutton')
        let firstchild = button.firstChild
        firstchild.style.whiteSpace = 'normal'
        firstchild.style.width = '100px'
        button.style.paddingRight = '0px'
      })
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

<style scoped>
  .v-btn__content {
    white-space: normal;
    width: 100px;
  }
</style>
