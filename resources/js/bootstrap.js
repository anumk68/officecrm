// File: resources/js/bootstrap.js

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// === THIS IS THE FIX ===
// Set the base URL for all axios requests from the global object we created.
window.axios.defaults.baseURL = window.Laravel.appUrl;
// === END OF FIX ===


/**
 * Echo and Reverb setup
 */
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],

    // This authorizer block is still necessary
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                // Now this path will correctly resolve to:
                // http://localhost/crm-backup.../broadcasting/auth
                axios.post('broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name
                })
                .then(response => {
                    callback(false, response.data);
                })
                .catch(error => {
                    console.error("Authorization failed:", error); // Added better error logging
                    callback(true, error);
                });
            }
        };
    },
});
