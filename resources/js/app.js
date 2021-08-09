require('./bootstrap');

import Vue from 'vue';
import VueMeta from 'vue-meta'
import moment from 'moment'
import VueCookie from 'vue-cookie'
import swal from 'sweetalert'
import VueGtag from "vue-gtag"

import { createInertiaApp, Link } from '@inertiajs/inertia-vue';
// import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import navigateForVuetify from './Mixins/navigateForVuetify'
import vuetify from './Plugins/vuetify'
import VueGeolocation from 'vue-browser-geolocation'
import * as VueGoogleMaps from 'vue2-google-maps'

import { InertiaProgress } from '@inertiajs/progress'

InertiaProgress.init()

Vue.mixin(navigateForVuetify)

Vue.mixin({ methods: { route } });
// Vue.use(InertiaApp);
// Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.prototype.moment = moment
Vue.use(VueCookie)
Vue.use(VueGeolocation)

var mapskey = document.querySelector('meta[name=google_maps_key]').getAttribute('content')
var analyticskey = document.querySelector('meta[name=google_analytics]').getAttribute('content')

Vue.use(VueGtag, {
  config: {
    id: analyticskey,
    params: {
      send_page_view: false
    }
  }
})
Vue.use(VueGoogleMaps, {
  load: {
    key: mapskey,
    libraries: 'places', // This is required if you use the Autocomplete plugin
    installComponents: true,
    // OR: libraries: 'places,drawing'
    // OR: libraries: 'places,drawing,visualization'
    // (as you require)

    //// If you want to set the version, you can do so:
    // v: '3.26',
  }
})

Vue.use(VueMeta, {
  keyName: 'metaInfo',
  attribute: 'data-vue-meta',
  ssrAttribute: 'data-vue-meta-server-rendered',
  tagIDKeyName: 'vmid',
  refreshOnceOnNavigation: true
})

swal.setDefaults({
  html: true,
  allowOutsideClick: true
})

document.addEventListener('inertia:success', (e) => {
  setTimeout(() => {
    // console.log('event', e.target.title, e.target.URL)
    var path = {
      page_title: e.target.title,
      page_location: e.target.URL,
      page_path: e.target.location.pathname
    }
    // console.log('path', path)
    if (process.env.NODE_ENV == 'production') {
      Vue.$gtag.pageview(path)
    }
  }, 1000);
})

// Get the mode
var dark = window.matchMedia('(prefers-color-scheme: dark)')
if (dark.matches) {
  document.cookie = "mode=theme--dark";
}
var light = window.matchMedia('(prefers-color-scheme: light)')
if (light.matches) {
  document.cookie = "mode=theme--dark";
}
dark.onchange = function () {
  checkDarkMode()
}
light.onchange = function () {
  checkDarkMode()
}
function checkDarkMode() {
  var dark = window.matchMedia('(prefers-color-scheme: dark)')
  var light = window.matchMedia('(prefers-color-scheme: light)')
  if (dark.matches) {
    document.cookie = "mode=theme--dark";
  }
  if(light.matches) {
    document.cookie = "mode=theme--light";
  }
  if (!light.matches && !dark.matches) {
    document.cookie = "mode=";
  }
}


Vue.component(
  'modal-dialog',
  require('@/Components/components/dialog.vue').default
)
Vue.component(
  'my-details',
  require('@/Components/components/details.vue').default
)
Vue.component('google-map', VueGoogleMaps.Map)
Vue.component(
  'message-bar',
  require('@/Components/components/messagebar.vue').default
)
Vue.component(
  'date-picker',
  require('@/Components/components/datepicker.vue').default
)
Vue.component(
  'm-password-confirm',
  require('@/Components/components/ConfirmsPassword.vue').default
)
Vue.component(
  'inertia-link',
  Link
)

// const doc = document.getElementById('app');

// const app = new Vue({
//     vuetify,
//     render: (h) =>
//         h(InertiaApp, {
//             props: {
//                 initialPage: JSON.parse(doc.dataset.page),
//                 resolveComponent: (name) => require(`./Pages/${name}`).default,
//             },
//         }),
// }).$mount(doc);

// if (process.env.NODE_ENV != 'production') {
//   window.app = app
// }

createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup({ el, app, props }) {
    const dev = new Vue({
      vuetify,
      render: h => h(app, props),
    }).$mount(el)
    if (process.env.NODE_ENV != 'production') {
      window.app = dev
    }
  },
})
