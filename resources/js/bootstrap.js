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
    const pusherHost = document.querySelector('meta[name="pusher-host"]')?.content;
    const pusherPort = document.querySelector('meta[name="pusher-port"]')?.content;

    // Check if we have required credentials
    if (!pusherKey || !pusherCluster) {
        console.warn('⚠️ Pusher credentials not found in meta tags. Check config/broadcasting.php');
        console.warn('  pusherKey:', pusherKey);
        console.warn('  pusherCluster:', pusherCluster);
        
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
            wsHost: pusherHost,
            wsPort: pusherPort || 80,
            wssPort: pusherPort || 443,
            forceTLS: true,
            enabledTransports: ['ws', 'wss'],
        });

        // Also expose pusher directly for backward compatibility
        window.pusher = new Pusher(pusherKey, {
            cluster: pusherCluster,
            forceTLS: true,
        });

        console.log('✅ Pusher initialized successfully with key:', pusherKey.substring(0, 10) + '...');
    } catch (error) {
        console.error('❌ Error initializing Pusher:', error);
        console.error('  Check that pusher-js is installed: npm install pusher-js');
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
