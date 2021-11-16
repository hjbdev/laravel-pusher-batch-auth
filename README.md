# Laravel Pusher Batch Auth

Package to give support for [dirkbonhomme/pusher-js-auth](https://github.com/dirkbonhomme/pusher-js-auth) into Laravel.

Registers the `/broadcasting/auth/batch` route, send your auth requests there instead.


Example:

```js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import PusherBatchAuthorizer from 'pusher-js-auth';

const echo = new Echo({
    broadcaster: 'pusher',
    client: new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        authEndpoint: '/broadcasting/auth/batch',
        authorizer: PusherBatchAuthorizer,
        authDelay: 500,
        forceTLS: true,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    }),
});
```

