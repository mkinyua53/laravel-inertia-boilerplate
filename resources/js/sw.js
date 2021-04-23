import { CacheFirst } from 'workbox-strategies/CacheFirst';
import { ExpirationPlugin } from 'workbox-expiration/ExpirationPlugin';
import { precacheAndRoute } from 'workbox-precaching/precacheAndRoute';
import { registerRoute } from 'workbox-routing/registerRoute';
// importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.0.0/workbox-sw.js');

import { clientsClaim } from 'workbox-core';
import { NetworkFirst } from 'workbox-strategies';
import { StaleWhileRevalidate } from 'workbox-strategies';

(() => {
  'use strict'

  precacheAndRoute(self.__WB_MANIFEST);

  registerRoute(
    /\.(?:png|jpg|jpeg|svg)$/,
    new CacheFirst({
      cacheName: 'images1',
      plugins: [
        new ExpirationPlugin({
          maxEntries: 20,
          purgeOnQuotaError: true,
        }),
      ]
    }));

  registerRoute(
    /\.(?:woff|woff2|ttf|eot)$/,
    new CacheFirst({
      cacheName: 'fonts',
      plugins: [
        new ExpirationPlugin({
          maxEntries: 50,
          purgeOnQuotaError: true,
        }),
      ]
    }));

  registerRoute(
    /\.(?:css)$/,
    new StaleWhileRevalidate({
      cacheName: 'important_assets',
      plugins: [
        new ExpirationPlugin({
          maxEntries: 500,
          purgeOnQuotaError: true,
        }),
      ]
    }));

  registerRoute(
    /https:\/\/www.google-analytics.com\/analytics\.js/,
    new CacheFirst,
    "GET"
  )

  registerRoute(
    '/user/sw.js',
    new NetworkFirst({
      cacheName: 'push-sw',
    })
  )

  registerRoute(
    /[\/\/]{2}.*[\/]{1}.*$/,
    new NetworkFirst({
      purgeOnQuotaError: true,
      maxAgeSeconds: 2 * 24 * 60 * 60,
      cacheName: 'website',
      maxEntries: 50
    }),
    "GET"
  )

  clientsClaim()

  self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
      self.skipWaiting();
    }
  });
  self.addEventListener('install', function (event) {
    // The promise that skipWaiting() returns can be safely ignored.
    self.skipWaiting();

    // Perform any other actions required for your
    // service worker to install, potentially inside
    // of event.waitUntil();
  });

  const WebPush = {
    init() {
      self.addEventListener('push', this.notificationPush.bind(this))
      self.addEventListener('notificationclick', this.notificationClick.bind(this))
      self.addEventListener('notificationclose', this.notificationClose.bind(this))
    },

    /**
     * Handle notification push event.
     *
     * https://developer.mozilla.org/en-US/docs/Web/Events/push
     *
     * @param {NotificationEvent} event
     */
    notificationPush(event) {
      if (!(self.Notification && self.Notification.permission === 'granted')) {
        return
      }

      // https://developer.mozilla.org/en-US/docs/Web/API/PushMessageData
      if (event.data) {
        // var msg = event.data.json();
        // console.log(msg)
        event.waitUntil(this.sendNotification(event.data.json()))
      }
    },

    /**
     * Handle notification click event.
     *
     * https://developer.mozilla.org/en-US/docs/Web/Events/notificationclick
     *
     * @param {NotificationEvent} event
     */
    notificationClick(event) {
      console.log('event', event)
      console.log('actions', event.action)

      if (event.action) {
        self.clients.openWindow(event.action)
      }

      if (event.action === 'some_action') {
        // Do something...
      } else {
        self.clients.openWindow('/')
      }
    },

    /**
     * Handle notification close event (Chrome 50+, Firefox 55+).
     *
     * https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerGlobalScope/onnotificationclose
     *
     * @param {NotificationEvent} event
     */
    notificationClose(event) {
      self.registration.pushManager.getSubscription().then(subscription => {
        if (subscription) {
          this.dismissNotification(event, subscription)
        }
      })
    },

    /**
     * Send notification to the user.
     *
     * https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerRegistration/showNotification
     *
     * @param {PushMessageData|Object} data
     */
    sendNotification(data) {
      return self.registration.showNotification(data.title, data)
    },

    /**
     * Send request to server to dismiss a notification.
     *
     * @param  {NotificationEvent} event
     * @param  {String} subscription.endpoint
     * @return {Response}
     */
    dismissNotification({ notification }, { endpoint }) {
      if (!notification.data || !notification.data.id) {
        return
      }

      const data = new FormData()
      data.append('endpoint', endpoint)

      // Send a request to the server to mark the notification as read.
      fetch(`/notifications/${notification.data.id}/dismiss`, {
        method: 'POST',
        body: data
      })
    }
  }

  WebPush.init()
})()

// workbox.precaching.precacheAndRoute(self.__precacheManifest);
