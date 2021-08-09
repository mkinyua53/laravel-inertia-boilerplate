export default {
  data () {
    return {
      form_rules: {
        required: (value) => !!value || "Required.",
        email: (value) => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "Invalid e-mail.";
        },
      },
    }
  },
  methods: {
    goTo(url, data = {}, opt = {}) {
      this.$inertia.visit(url, data, opt)
    },
    _error(field, errorBag = 'default') {
      if (!this.$page.props.errors.hasOwnProperty(errorBag)) {
        return null;
      }

      if (this.$page.props.errors[errorBag].hasOwnProperty(field)) {
        return this.$page.props.errors[errorBag][field];
      }

      return null;
    },
    _changeMode() {
      this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
      setTimeout(() => {
        if (this.$vuetify.theme.dark) {
          this.$cookie.set('prefers', 'dark')
          this.$cookie.set('mode', 'theme--dark')
        } else {
          this.$cookie.set('prefers', 'light')
          this.$cookie.set('mode', 'theme--light')
        }
      }, 200)
    },
    checkLogin () {
      var checked = this.$cookie.get('auto_login')
      if (!checked) {
        this.$cookie.set('auto_login', 'checked', { expires: '1m' })
      }
      if (window.PasswordCredential || window.FederatedCredential) {
        if (!this.$auth.getToken()) {
          navigator.credentials.get({
            password: true,
            mediation: 'optional'
          }).then(c => {
              // console.log('C ', c)
              if (c) {
                switch (c.type) {
                  case 'password':
                    return this.$auth.login(c);
                    break;
                  case 'federated':
                    // return gSignIn(c);
                    break;
                  }
              } else {
                  return Promise.resolve();
              }
          }).then(profile => {
              if (profile) {
                  // updateUI(profile);
                  // console.log('profile', profile)
              }
          }).catch(error => {
            console.error(error, 'ERROR');
            // var path = this.$route.fullpath
            // this.$router.push('/login?redirect=' + path)
          });
        }
      }
    }
  },
  computed: {
    $title() {
      return this.$page.props.title
    },
    $breakpoint() {
      return this.$vuetify.breakpoint
    },
    $darkmode() {
      var prefers = this.$cookie.get('prefers')
      if (prefers && prefers == 'dark') {
        this.$vuetify.theme.dark = true
        return true
      }
      if (prefers && prefers == 'light') {
        this.$vuetify.theme.dark = false
        return false
      } else {
        var mode = this.$cookie.get('mode')
        if (mode == 'theme--dark') {
          this.$vuetify.theme.dark = true
          return true
        } else {
          this.$vuetify.theme.dark = false
          return false
        }
      }
    },
    can () {
      return this.$page.props.auth ? this.$page.props.auth.can : null
    },
    $user () {
      return this.$page.props.user ? this.$page.props.user : null
    },
    $admin() {
      return this.$user ? this.$user.is_admin : false
    },
    $messaged () {
      if (this.$message) {
        return true
      }
      return false
    },
    $message () {
      return this.$page.props.message
    },
    previous() {
      return this.$page.props.previous
    },
    getPrevious() {
      var origin = location.origin
      var previous = this.previous
      return previous.replace(origin, '')
    },
  },
  mounted() {
    this.$nextTick(() => {
      var prefers = this.$cookie.get('prefers')
      if (prefers && prefers == 'dark') {
        this.$vuetify.theme.dark = true
        return true
      }
      if (prefers && prefers == 'light') {
        this.$vuetify.theme.dark = false
        return false
      } else {
        var mode = this.$cookie.get('mode')
        if (mode == 'theme--dark') {
          this.$vuetify.theme.dark = true
          return true
        } else {
          this.$vuetify.theme.dark = false
          return false
        }
      }
    })
  },
}
