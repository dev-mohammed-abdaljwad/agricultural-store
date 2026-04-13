import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Initialize Pusher with better error handling
function initializePusher() {
    if (typeof window === 'undefined') {
        console.warn('⚠️ Window object not available for Pusher initialization');
        return;
    }

    // Get Pusher credentials from meta tags
    const pusherKey = document.querySelector('meta[name="pusher-key"]')?.content;
    const pusherCluster = document.querySelector('meta[name="pusher-cluster"]')?.content;

    // Check if we have required credentials
    if (!pusherKey || !pusherCluster) {
        console.warn('⚠️ Pusher credentials not found in meta tags. Check .env file.');
        
        // Retry after a delay in case DOM wasn't ready
        if (document.readyState !== 'complete') {
            setTimeout(initializePusher, 500);
            return;
        }
    }

    try {
        window.Pusher = Pusher;
        
        // Initialize Laravel Echo with Pusher
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: pusherKey,
            cluster: pusherCluster,
            forceTLS: true,
            enabledTransports: ['ws', 'wss'],
        });

        // Also expose pusher directly for backward compatibility
        window.pusher = new Pusher(pusherKey, {
            cluster: pusherCluster,
            forceTLS: true,
        });

        console.log('✅ Pusher initialized successfully');
    } catch (error) {
        console.error('❌ Error initializing Pusher:', error);
    }
}

// Initialize Pusher when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePusher);
} else {
    // DOM is already loaded
    initializePusher();
}

// Also retry on window load to be safe
window.addEventListener('load', () => {
    if (!window.pusher) {
        console.log('⚠️ Pusher not initialized on page load, retrying...');
        initializePusher();
    }
});
