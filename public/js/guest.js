
window.addEventListener('load', function () {
  login()
  // document.cookie = tes
  installSW()
})
window.addEventListener('loginrequest', function (e) {
  login()
})

function login() {
  let logcookie = getCookie('loggedout')
  if (!logcookie) {
    if (window.PasswordCredential || window.FederatedCredential) {
      navigator.credentials.get({
        password: true,
        mediation: 'optional'
      }).then(c => {
        if (c) {
          switch (c.type) {
            case 'password':
              // console.log(c)
              var token = document.querySelector('meta[name=csrf-token]').getAttribute('content')
              var myHeaders = new Headers();
              myHeaders.append('X-inertia', true);
              var form = new FormData()
              form.append('password', c.password)
              form.append('email', c.id)
              form.append('_token', token)

              fetch('/login', {
                method: 'POST',
                body: form,
                headers: myHeaders,
              })
                .then(response => {
                  const loginresponse = new CustomEvent('loginresponse', { detail: response })
                  window.dispatchEvent(loginresponse)
                  // console.log(response)
                })
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

function installSW() {
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js').then(registration => {
      // console.log('SW1 registered: ', registration);
    }).catch(registrationError => {
      console.error('SW1 registration failed: ', registrationError);
    });
  }
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
