<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    
    <!-- Pusher Configuration -->
    <meta name="pusher-key" content="{{ config('broadcasting.connections.pusher.key') }}"/>
    <meta name="pusher-cluster" content="{{ config('broadcasting.connections.pusher.options.cluster') }}"/>
    <meta name="pusher-host" content="{{ config('broadcasting.connections.pusher.options.host', 'api-' . config('broadcasting.connections.pusher.options.cluster') . '.pusher.com') }}"/>
    <meta name="pusher-port" content="{{ config('broadcasting.connections.pusher.options.port', 443) }}"/>
    
    <title>@yield('title', 'حصاد - المحادثات')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&family=Tajawal:wght@400;500;700;800;900&family=Almarai:wght@400;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Tailwind & Config -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-primary-fixed": "#002201",
                        "tertiary-fixed-dim": "#e9c176",
                        "on-tertiary-fixed": "#261900",
                        "on-secondary-fixed-variant": "#603f33",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-fixed-variant": "#5d4201",
                        "secondary-fixed": "#ffdbcf",
                        "secondary-container": "#fdcdbc",
                        "on-surface": "#1a1c19",
                        "surface-dim": "#dadad5",
                        "surface-bright": "#fafaf5",
                        "outline-variant": "#c2c9bb",
                        "inverse-on-surface": "#f1f1ec",
                        "outline": "#72796e",
                        "on-secondary-fixed": "#2e150b",
                        "primary-fixed-dim": "#a1d494",
                        "on-primary-fixed-variant": "#23501e",
                        "on-background": "#1a1c19",
                        "secondary-fixed-dim": "#ebbcac",
                        "error": "#ba1a1a",
                        "surface-tint": "#3b6934",
                        "on-secondary-container": "#795548",
                        "on-primary-container": "#9dd090",
                        "surface-container-low": "#f4f4ef",
                        "surface-container": "#eeeee9",
                        "surface-variant": "#e3e3de",
                        "on-tertiary-container": "#e4bd72",
                        "primary-fixed": "#bcf0ae",
                        "on-primary": "#ffffff",
                        "inverse-surface": "#2f312e",
                        "secondary": "#7a5649",
                        "surface": "#fafaf5",
                        "primary-container": "#2d5a27",
                        "on-secondary": "#ffffff",
                        "background": "#fafaf5",
                        "on-surface-variant": "#42493e",
                        "tertiary-fixed": "#ffdea5",
                        "inverse-primary": "#a1d494",
                        "tertiary": "#4b3500",
                        "surface-container-highest": "#e3e3de",
                        "on-tertiary-container": "#2a1800",
                        "primary": "#3b6934",
                        "surface-200": "#e8e8e3",
                        "surface-300": "#d6d6cf",
                        "surface-400": "#b8b8af",
                        "surface-500": "#8b8b7f",
                        "surface-600": "#5b5b51",
                        "surface-700": "#3f3f37",
                        "surface-800": "#2a2a22",
                        "surface-900": "#1a1a13",
                    }
                }
            }
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Direct Pusher Initialization (loaded before Vite scripts) -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // Initialize Pusher immediately when library is loaded
        function initPusher() {
            const pusherKey = document.querySelector('meta[name="pusher-key"]')?.content?.trim();
            const pusherCluster = document.querySelector('meta[name="pusher-cluster"]')?.content?.trim();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            console.log('[Direct Init] Pusher credentials:', {
                key: pusherKey ? '✅ ' + pusherKey.substring(0, 10) + '...' : '❌ Missing',
                cluster: pusherCluster ? '✅ ' + pusherCluster : '❌ Missing',
                csrf: csrfToken ? '✅ Present' : '❌ Missing'
            });

            if (pusherKey && pusherCluster) {
                window.Pusher = Pusher;
                window.pusher = new Pusher(pusherKey, {
                    cluster: pusherCluster,
                    forceTLS: true,
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    }
                });
                console.log('✅ Pusher initialized successfully with auth endpoint: /broadcasting/auth');
            } else {
                console.error('❌ Missing Pusher credentials in meta tags. Check .env file has PUSHER_APP_KEY and PUSHER_APP_CLUSTER');
            }
        }

        // Initialize when Pusher library loads
        if (typeof Pusher !== 'undefined') {
            console.log('[Direct Init] Pusher library already loaded, initializing now...');
            initPusher();
        } else {
            console.log('[Direct Init] Waiting for Pusher library to load...');
            document.addEventListener('DOMContentLoaded', initPusher);
            window.addEventListener('load', initPusher);
        }
    </script>
</head>
<body class="bg-surface antialiased">
    <x-toast-container />
    
    <div class="flex h-screen overflow-hidden bg-surface">
        <!-- Mobile Menu Button (visible only on mobile) -->
        <button id="sidebarToggleBtn" class="fixed top-3 left-3 sm:top-4 sm:left-4 z-50 md:hidden bg-primary text-white p-1.5 sm:p-2 rounded-lg">
            <i class="material-symbols-outlined text-base sm:text-lg">menu</i>
        </button>

        <!-- Sidebar -->
        <aside id="conversationsSidebar" class="hidden sm:fixed inset-y-0 right-0 z-40 w-64 sm:w-72 md:w-80 bg-white border-l border-surface-200 overflow-hidden flex flex-col md:block md:relative md:z-0 transition-transform duration-300 -translate-x-full sm:-translate-x-full md:translate-x-0 shadow-lg md:shadow-none">
            <!-- Sidebar Header -->
            <div class="border-b border-surface-200 p-3 sm:p-4 bg-white">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <h2 class="text-base sm:text-lg font-bold text-surface-900">{{ __('messages.conversations') }}</h2>
                    <button id="sidebarCloseBtn" class="md:hidden text-surface-600 hover:text-surface-900">
                        <i class="material-symbols-outlined">close</i>
                    </button>
                </div>

                <!-- Search Bar -->
                <div class="relative">
                    <i class="material-symbols-outlined absolute right-3 top-2.5 text-surface-400 text-sm">search</i>
                    <input 
                        type="text" 
                        id="conversationSearch" 
                        placeholder="{{ __('messages.search_conversations') }}" 
                        class="w-full pr-10 pl-3 py-2 border border-surface-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-xs sm:text-sm"
                    >
                </div>
            </div>

            <!-- Conversations List -->
            <div id="conversationsList" class="flex-1 overflow-y-auto">
                <!-- Loading State -->
                <div id="conversationsLoading" class="flex flex-col items-center justify-center py-8 sm:py-12">
                    <div class="w-6 h-6 sm:w-8 sm:h-8 border-4 border-surface-200 border-t-primary rounded-full animate-spin"></div>
                    <p class="text-xs sm:text-sm text-surface-500 mt-2 sm:mt-3">{{ __('messages.loading') }}</p>
                </div>

                <!-- Conversations Items -->
                <div id="conversationsContainer" class="space-y-1 p-1 sm:p-2">
                    <!-- Populated by JavaScript -->
                </div>

                <!-- Empty State -->
                <div id="conversationsEmpty" class="hidden flex flex-col items-center justify-center py-8 sm:py-12 px-3 sm:px-4">
                    <i class="material-symbols-outlined text-3xl sm:text-4xl text-surface-300 mb-1 sm:mb-2">chat_bubble_outline</i>
                    <p class="text-xs sm:text-sm text-surface-500 text-center">{{ __('messages.no_conversations') }}</p>
                </div>
            </div>

            <!-- Sidebar Footer -->
            <div class="border-t border-surface-200 p-3 sm:p-4 bg-surface-50">
                <div class="flex items-center justify-between p-2 sm:p-3 bg-white rounded-lg border border-surface-200 hover:border-primary/30 transition">
                    <div class="min-w-0">
                        <p class="text-xs sm:text-sm font-semibold text-surface-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-surface-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline flex-shrink-0 ml-2">
                        @csrf
                        <button type="submit" title="{{ __('messages.logout') }}" class="text-surface-600 hover:text-red-600 transition">
                            <i class="material-symbols-outlined text-base">logout</i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <nav class="bg-white border-b border-surface-200 shadow-sm">
                <div class="h-12 sm:h-14 md:h-16 px-3 sm:px-4 md:px-6 lg:px-8 flex items-center justify-between">
                    <!-- Left: Branding -->
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('products.index') }}" class="text-primary hover:text-primary-dark transition flex-shrink-0">
                            <i class="material-symbols-outlined text-lg sm:text-xl">arrow_back</i>
                        </a>
                        <div class="min-w-0">
                            <h1 class="text-xs sm:text-sm font-bold text-surface-900 truncate">{{ __('messages.support_chat') }}</h1>
                            <p class="text-xs text-surface-500 hidden sm:block">{{ __('messages.help_and_support') }}</p>
                        </div>
                    </div>

                    <!-- Right: User Menu -->
                    <div class="flex items-center gap-2 sm:gap-3 md:gap-4 flex-shrink-0">
                        <!-- Unread Count Badge -->
                        <button onclick="document.getElementById('conversationsList').scrollTop = 0" class="relative text-surface-600 hover:text-primary transition">
                            <i class="material-symbols-outlined text-lg sm:text-xl">notifications</i>
                            <span id="unreadBadge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-4 sm:w-5 h-4 sm:h-5 flex items-center justify-center hidden text-xs">0</span>
                        </button>

                        <!-- Mobile Sidebar Toggle -->
                        <button id="sidebarToggleBtnNav" class="md:hidden text-surface-600 hover:text-primary transition">
                            <i class="material-symbols-outlined text-lg">menu</i>
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Content Area -->
            <main class="flex-1 overflow-hidden bg-gradient-to-b from-primary/5 to-white">
                @yield('chat-content')
            </main>
        </div>

        <!-- Overlay for mobile sidebar -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden" onclick="document.getElementById('conversationsSidebar').classList.add('-translate-x-full')"></div>
    </div>

    <!-- JavaScript for Chat Layout -->
    <script>
        // Sidebar Toggle for Mobile
        const sidebarToggleBtns = document.querySelectorAll('#sidebarToggleBtn, #sidebarToggleBtnNav');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebar = document.getElementById('conversationsSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        // Handle sidebar toggle - works with both hidden and -translate-x-full
        function toggleSidebar() {
            const isHidden = sidebar.classList.contains('hidden');
            const isTranslated = sidebar.classList.contains('-translate-x-full');
            
            if (isHidden) {
                // On xs screens: show sidebar
                sidebar.classList.remove('hidden');
                overlay.classList.remove('hidden');
            } else if (isTranslated) {
                // On sm screens: slide in
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                // Already visible, hide it
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        sidebarToggleBtns.forEach(btn => {
            btn.addEventListener('click', toggleSidebar);
        });

        sidebarCloseBtn.addEventListener('click', () => {
            const isHidden = sidebar.classList.contains('hidden');
            if (isHidden) {
                sidebar.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
            overlay.classList.add('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Load Conversations List
        async function loadConversations() {
            const container = document.getElementById('conversationsContainer');
            const loading = document.getElementById('conversationsLoading');
            const empty = document.getElementById('conversationsEmpty');
            const route = '{{ auth()->user()->isAdmin() ? route("admin.chat.index") : route("chat.index") }}';

            try {
                const response = await fetch(route);
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Extract conversation items from the page
                const items = doc.querySelectorAll('a[href*="/conversations/"]');
                
                if (items.length === 0) {
                    loading.classList.add('hidden');
                    empty.classList.remove('hidden');
                    return;
                }

                loading.classList.add('hidden');
                container.innerHTML = '';

                items.forEach(item => {
                    const clone = item.cloneNode(true);
                    clone.classList.remove('block', 'group');
                    clone.classList.add('block', 'hover:bg-primary/5', 'p-3', 'rounded-lg', 'transition', 'conversation-item');
                    container.appendChild(clone);
                });

                // Add click handlers to close sidebar on mobile
                document.querySelectorAll('.conversation-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const isHidden = sidebar.classList.contains('hidden');
                        if (isHidden) {
                            sidebar.classList.add('hidden');
                        } else {
                            sidebar.classList.add('-translate-x-full');
                        }
                        overlay.classList.add('hidden');
                    });
                });
            } catch (error) {
                console.error('Failed to load conversations:', error);
                loading.innerHTML = '<p class="text-sm text-red-500">{{ __("messages.error_loading") }}</p>';
            }
        }

        // Load conversations on page load
        loadConversations();

        // Search conversations
        document.getElementById('conversationSearch').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.conversation-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });

        // Update unread count
        async function updateUnreadCount() {
            const route = '{{ auth()->user()->isAdmin() ? url("/admin/chat/unread-count") : url("/chat/unread-count") }}';
            try {
                const response = await fetch(route);
                const data = await response.json();
                const badge = document.getElementById('unreadBadge');
                
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            } catch (error) {
                console.error('Failed to update unread count:', error);
            }
        }

        // Update unread count on load and every 10 seconds
        updateUnreadCount();
        setInterval(updateUnreadCount, 10000);

        // Refresh conversations every 30 seconds
        setInterval(loadConversations, 30000);
    </script>

    <!-- Pusher Initialization Check -->
    <script>
        // Simple utility to check Pusher status
        window.checkPusherStatus = function() {
            console.log('=== Pusher Status ===');
            console.log('Pusher library:', typeof window.Pusher !== 'undefined' ? '✅ Loaded' : '❌ Not loaded');
            console.log('Pusher instance:', typeof window.pusher !== 'undefined' ? '✅ Initialized' : '❌ Not initialized');
            console.log('Echo instance:', typeof window.Echo !== 'undefined' ? '✅ Initialized' : '❌ Not initialized');
            
            // Check meta tags
            console.log('Meta tags:');
            console.log('  pusher-key:', document.querySelector('meta[name="pusher-key"]')?.content ? '✅ Found' : '❌ Missing');
            console.log('  pusher-cluster:', document.querySelector('meta[name="pusher-cluster"]')?.content ? '✅ Found' : '❌ Missing');
            
            return {
                pusherLoaded: typeof window.Pusher !== 'undefined',
                pusherInitialized: typeof window.pusher !== 'undefined',
                echoInitialized: typeof window.Echo !== 'undefined'
            };
        };

        // Auto-log status after a short delay
        setTimeout(() => {
            if (typeof window.pusher === 'undefined') {
                console.warn('⚠️ Pusher not yet initialized. Run `window.checkPusherStatus()` to diagnose.');
            } else {
                console.log('✅ Pusher ready! Status:', window.checkPusherStatus());
            }
        }, 1000);
    </script>

    @stack('scripts')
</body>
</html>
