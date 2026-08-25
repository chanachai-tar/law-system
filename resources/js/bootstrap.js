import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

// Disable all console logging from Pusher
Pusher.logToConsole = false;

// Only initialize Echo if explicitly requested or WebSocket is active
if (window.ENABLE_WEBSOCKETS) {
    try {
        const host = window.location.hostname || '127.0.0.1';
        const port = import.meta.env.VITE_REVERB_PORT ? Number(import.meta.env.VITE_REVERB_PORT) : 8080;
        const scheme = import.meta.env.VITE_REVERB_SCHEME || 'http';

        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: import.meta.env.VITE_REVERB_APP_KEY || 'law_system_key',
            wsHost: host,
            wsPort: port,
            wssPort: port,
            forceTLS: scheme === 'https',
            enabledTransports: ['ws', 'wss'],
        });

        if (window.Echo.connector && window.Echo.connector.pusher) {
            window.Echo.connector.pusher.connection.bind('error', () => {});
            window.Echo.connector.pusher.connection.bind('unavailable', () => {});
            window.Echo.connector.pusher.connection.bind('failed', () => {});
        }
    } catch (e) {
        // Silent
    }
}
