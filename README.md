## About this package
This is a laravel version 8 boilerplate. Included are:
- [Laravel v8](https://laravel.com/docs/8.0)
- [Jetstream with inertia stack](https://jetstream.laravel.com/)
- [VueJS](https://vuejs.org)
- [Vuetify](https://vuetifyjs.com/en/introduction/why-vuetify/#guide) in place of [TailWind](https://tailwindcss.com/) even though TailWind compilation is included in laravel mix with a prefix in the class names
- [WorkBox](https://developers.google.com/web/tools/workbox/modules/workbox-webpack-plugin) service worker
- [Web Push notification](https://medium.com/@sagarmaheshwary31/push-notifications-with-laravel-and-webpush-446884265aaa)

## Other features
- Role-based authorization; Check ```app\Http\Controllers\AuthController```
- [Vue-meta](https://vue-meta.nuxtjs.org/guide/)

## Installing

```bash
composer create-project mkinyua53/laravel-inertia-boilerplate myproject
```
Install dependencies
```bash
yarn
```
Run server
```bash
php artisan serve
```
Run watch
```bash
yarn watch
```
To generate the service worker run the production server
```bash
yarn prod
```
The service-worker is installed in the ```resources\js\bootstrap.js```
The Push notification is activated for logged-in users in ```resources\views\app.blade.php``` with this line
```php
@auth
  <script defer async src="/js/push.js"></script>
@endauth
```
To ask for notification permission, in a .vue file, example in ```resources\js\Layouts\User.vue```
```js
export default {
  name: 'user-layout',
  metaInfo () {
    return {
      title: 'User Dashboard'
    }
  },
  data () {
    return {
      webpushsuccess: false,
      snackbar: false,
      message: ''
    }
  },
  methods: {
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
      //  Dispatched a custom event and listen for it to request notifications permission
      window.dispatchEvent(new CustomEvent('notifyme'));
    }
  },
  computed: {
    webpush () {
      return ('Notification' in window) && Notification.permission === 'granted'
    }
  },
  created () {
    var vm = this
    // we listen for this custom event fired after the notification subscription is saved in the server
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
```
in push.js
```js
if ('serviceWorker' in navigator) {
  const event = new Event('notifyme');
  window.addEventListener('notifyme', () => {
    if ("PushManager" in window) {
      navigator.serviceWorker.register('/service-worker.js')
        .then(() => {
          // console.log('serviceWorker installed!')
          initPush();
        })
        .catch((err) => {
          console.log(err)
        });
    }
  });
}

function storePushSubscription(pushSubscription) {
  const token = document.querySelector('meta[name=csrf-token]').getAttribute('content');

  fetch('/user/push', {
    method: 'POST',
    body: JSON.stringify(pushSubscription),
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-Token': token
    }
  })
    .then((res) => {
      return res.json();
    })
    .then((res) => {
      // Dispatch a custom event
      const event = new Event('notifyon');
      window.dispatchEvent(event)
      console.log(res)
    })
    .catch((err) => {
      console.log(err)
    });
}
```
Alternatively, you can ask for permission on ```onload``` event and avoid using custom events, but, FireFox requires that the permission request be user initiated
```js
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    if ("PushManager" in window) {
      navigator.serviceWorker.register('/service-worker.js')
        .then(() => {
          // console.log('serviceWorker installed!')
          initPush();
        })
        .catch((err) => {
          console.log(err)
        });
    }
  });
}
```

## Authorization front-end
```js
<v-btn small v-if="can && can['dashboard.view]">View Dashboard</v-btn>
```

## Credits
1. [Sagar Maheshwary](https://medium.com/@sagarmaheshwary31/push-notifications-with-laravel-and-webpush-446884265aaa) for the WebPush code
