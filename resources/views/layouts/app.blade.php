<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'حصاد')</title>
    
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
                        "error-container": "#ffdad6",
                        "on-error": "#ffffff",
                        "primary": "#154212",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-high": "#e8e8e3",
                        "on-error-container": "#93000a",
                        "tertiary-container": "#674b0a",
                        "success": "#1b5e20",
                        "on-success": "#ffffff",
                        "success-container": "#abebc8",
                        "on-success-container": "#002110",
                        "warning": "#f57f17",
                        "on-warning": "#ffffff",
                        "warning-container": "#ffd54f",
                        "on-warning-container": "#5d4000",
                        "info": "#0288d1",
                        "on-info": "#ffffff",
                        "info-container": "#b3e5fc",
                        "on-info-container": "#01579b"
                    },
                    fontFamily: {
                        "headline": ["Tajawal", "Be Vietnam Pro", "sans-serif"],
                        "body": ["Almarai", "Manrope", "sans-serif"],
                        "label": ["Almarai", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem"},
                },
            },
        }
    </script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        body {
            font-family: 'Almarai', sans-serif;
            background-color: #fafaf5;
            color: #1a1c19;
        }
        .editorial-shadow {
            box-shadow: 0 24px 48px -12px rgba(21, 66, 18, 0.08);
        }
        .step-active {
            background-color: #bcf0ae;
            color: #154212;
        }
        .glass-nav {
            background: rgba(250, 250, 245, 0.8);
            backdrop-filter: blur(12px);
        }
        .bg-harvest-overlay {
            background: linear-gradient(rgba(21, 66, 18, 0.7), rgba(21, 66, 18, 0.7));
        }
        input:focus {
            outline: none;
            border-bottom: 2px solid #154212 !important;
            box-shadow: none !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-surface text-on-surface min-h-screen flex flex-col">
    <x-toast-container />
    
    @yield('content')
    
    <!-- Pusher JavaScript SDK -->
    @auth
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        
        <!-- Initialize Pusher -->
        <script>
            if ('{{ env("BROADCAST_DRIVER") }}' === 'pusher') {
                window.pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                    cluster: '{{ env("PUSHER_APP_CLUSTER", "eu") }}',
                    forceTLS: true,
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }
                });
                console.log('[Pusher] ✅ Initialized with cluster: {{ env("PUSHER_APP_CLUSTER", "eu") }}');
            } else {
                console.warn('[Pusher] Broadcast driver is not Pusher, real-time features may be unavailable');
            }
        </script>
        
        <!-- Chat Pusher Integration (deprecated - using full-page chat views instead) -->
        <!-- Disabled: PopupManager and auto-popup features -->
        <!-- <script src="{{ asset('js/chat/PopupManager.js') }}"></script> -->
        <!-- <script src="{{ asset('js/chat/chat-pusher.js') }}"></script> -->
    @endauth
    
    <!-- Universal Modal Component -->
    @include('components.modal')
    
    @stack('scripts')
</body>
</html>
