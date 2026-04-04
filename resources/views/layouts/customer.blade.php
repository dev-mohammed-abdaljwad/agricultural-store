<!DOCTYPE html>
<html class="light" dir="rtl" lang="ar">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'حصاد')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;700;900&family=Tajawal:wght@400;500;700;900&family=Manrope:wght@400;500;700&family=Almarai:wght@400;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-tertiary": "#ffffff",
                        "inverse-primary": "#a1d494",
                        "tertiary": "#4b3500",
                        "tertiary-fixed-dim": "#e9c176",
                        "on-tertiary-fixed": "#261900",
                        "inverse-on-surface": "#f1f1ec",
                        "primary-container": "#2d5a27",
                        "on-background": "#1a1c19",
                        "secondary-fixed-dim": "#ebbcac",
                        "on-primary-container": "#9dd090",
                        "secondary-fixed": "#ffdbcf",
                        "on-surface": "#1a1c19",
                        "surface-variant": "#e3e3de",
                        "surface-tint": "#3b6934",
                        "on-error-container": "#93000a",
                        "surface-container-lowest": "#ffffff",
                        "surface-dim": "#dadad5",
                        "on-secondary": "#ffffff",
                        "error-container": "#ffdad6",
                        "error": "#ba1a1a",
                        "on-secondary-fixed": "#2e150b",
                        "primary-fixed": "#bcf0ae",
                        "surface-container-low": "#f4f4ef",
                        "on-tertiary-fixed-variant": "#5d4201",
                        "primary": "#154212",
                        "on-primary-fixed": "#002201",
                        "secondary-container": "#fdcdbc",
                        "tertiary-container": "#674b0a",
                        "tertiary-fixed": "#ffdea5",
                        "primary-fixed-dim": "#a1d494",
                        "on-error": "#ffffff",
                        "on-secondary-container": "#795548",
                        "on-primary-fixed-variant": "#23501e",
                        "inverse-surface": "#2f312e",
                        "on-tertiary-container": "#e4bd72",
                        "background": "#fafaf5",
                        "surface-container-high": "#e8e8e3",
                        "surface-bright": "#fafaf5",
                        "secondary": "#7a5649",
                        "surface-container-highest": "#e3e3de",
                        "surface-container": "#eeeee9",
                        "surface": "#fafaf5",
                        "outline-variant": "#c2c9bb",
                        "on-secondary-fixed-variant": "#603f33",
                        "outline": "#72796e",
                        "on-primary": "#ffffff",
                        "on-surface-variant": "#42493e"
                    },
                    fontFamily: {
                        "headline": ["Tajawal", "sans-serif"],
                        "body": ["Almarai", "sans-serif"],
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
        }
        .editorial-shadow {
            box-shadow: 0 24px 48px -12px rgba(21, 66, 18, 0.08);
        }
        body {
            font-family: 'Almarai', sans-serif;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-surface font-body text-on-surface">
    <x-toast-container />
    
    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 w-full z-50 bg-[#fafaf5]/80 backdrop-blur-md flex flex-row-reverse justify-between items-center px-8 h-20">
        <div class="text-2xl font-black text-primary font-headline">حصاد</div>

        {{-- Desktop Navigation --}}
        <div class="hidden md:flex flex-row-reverse gap-8 items-center">
            <a class="text-on-surface-variant hover:text-primary transition-colors font-headline font-bold text-lg" href="{{ route('home') }}">الرئيسية</a>
            <a class="text-on-surface-variant hover:text-primary transition-colors font-headline font-bold text-lg" href="{{ route('products.index') }}">المتجر</a>
            <a class="text-on-surface-variant hover:text-primary transition-colors font-headline font-bold text-lg" href="{{ route('chat.index') }}">المحادثات</a>
            <a class="text-primary font-bold border-b-2 border-primary font-headline text-lg" href="{{ route('dashboard') }}">طلباتي</a>
        </div>

        {{-- Actions & Profile --}}
        <div class="flex items-center gap-4">
            <button class="p-2 transition-all hover:text-primary text-on-surface-variant">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <a href="{{ route('chat.index') }}" class="p-2 transition-all hover:text-primary text-on-surface-variant" title="الرسائل">
                <span class="material-symbols-outlined">mail</span>
            </a>
            <a href="{{ route('cart.index') }}" class="p-2 transition-all hover:text-primary text-on-surface-variant" title="السلة">
                <span class="material-symbols-outlined">shopping_cart</span>
            </a>

            {{-- User Profile Dropdown --}}
            <div class="relative">
                <button id="profileBtn" class="w-10 h-10 rounded-full overflow-hidden bg-surface-container-high border-2 border-primary-fixed flex items-center justify-center hover:ring-2 hover:ring-primary-fixed transition-all">
                    @if(auth()->check() && auth()->user()->avatar)
                    <img alt="صورة الملف الشخصي" src="{{ auth()->user()->avatar }}" class="w-full h-full object-cover"/>
                    @else
                    <span class="material-symbols-outlined text-primary">account_circle</span>
                    @endif
                </button>

                {{-- Dropdown Menu --}}
                <div id="profileDropdown" class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 hidden z-50">
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-on-surface hover:bg-surface-container-low transition-colors text-sm">الملف الشخصي</a>
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-on-surface hover:bg-surface-container-low transition-colors text-sm">طلباتي</a>
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-on-surface hover:bg-surface-container-low transition-colors text-sm">الإعدادات</a>
                    <hr class="my-2 border-outline-variant/20"/>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-error hover:bg-error-container/50 transition-colors text-sm">تسجيل الخروج</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Side Navigation (Desktop) --}}
    <aside class="hidden lg:flex h-screen w-64 fixed right-0 top-20 flex-col py-6 bg-surface-container-low border-l border-outline-variant/20 overflow-hidden">
        <div class="px-6 mb-8 flex-shrink-0">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-primary text-3xl">psychology</span>
                <span class="font-headline font-bold text-xl text-primary">حصاد</span>
            </div>
            <p class="text-xs text-on-surface-variant">المنصة الرقمية للزراعة</p>
        </div>

        <nav class="flex flex-col gap-1 overflow-y-auto flex-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-3 text-on-surface-variant hover:bg-surface-container transition-colors rounded-lg mx-2">
                <span class="material-symbols-outlined">dashboard</span>
                <span>لوحة التحكم</span>
            </a>
            <a href="{{ route('quotes.index') ?? '#' }}" class="flex items-center gap-3 px-6 py-3 bg-primary-fixed text-primary rounded-lg mx-2 font-bold transition-colors">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">request_quote</span>
                <span>عروض الأسعار</span>
            </a>
            <a href="{{ route('orders.index') ?? '#' }}" class="flex items-center gap-3 px-6 py-3 text-on-surface-variant hover:bg-surface-container transition-colors rounded-lg mx-2">
                <span class="material-symbols-outlined">receipt_long</span>
                <span> الطلبات</span>
            </a>
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-6 py-3 text-on-surface-variant hover:bg-surface-container transition-colors rounded-lg mx-2">
                <span class="material-symbols-outlined">psychology</span>
                <span>المنتجات</span>
            </a>
            <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-6 py-3 text-on-surface-variant hover:bg-surface-container transition-colors rounded-lg mx-2">
                <span class="material-symbols-outlined">chat_bubble</span>
                <span>الرسائل</span>
            </a>
            <a href="{{ route('settings') }}" class="flex items-center gap-3 px-6 py-3 text-on-surface-variant hover:bg-surface-container transition-colors rounded-lg mx-2">
                <span class="material-symbols-outlined">settings</span>
                <span>  الإعدادات</span>
            </a>
        </nav>

        <div class="flex-shrink-0 mt-4 border-t border-outline-variant/20 pt-4">
            <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-6 py-3 text-on-surface-variant hover:bg-surface-container transition-colors rounded-lg mx-2">
                <span class="material-symbols-outlined">support_agent</span>
                <span>الدعم الفني</span>
            </a>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="pt-28 pb-24 lg:pr-72 px-4 md:px-8">
        {{-- Flash Messages --}}
        @if ($message = Session::get('success'))
        <div class="mb-6 p-4 rounded-lg bg-success-container text-on-success-container border border-success flex items-center gap-3 animate-in fade-in slide-in-from-top">
            <span class="material-symbols-outlined text-success">check_circle</span>
            <span>{{ $message }}</span>
            <button onclick="this.parentElement.remove()" class="mr-auto text-lg hover:opacity-70">×</button>
        </div>
        @endif

        @if ($message = Session::get('error'))
        <div class="mb-6 p-4 rounded-lg bg-error-container text-on-error-container border border-error flex items-center gap-3 animate-in fade-in slide-in-from-top">
            <span class="material-symbols-outlined text-error">error</span>
            <span>{{ $message }}</span>
            <button onclick="this.parentElement.remove()" class="mr-auto text-lg hover:opacity-70">×</button>
        </div>
        @endif

        @yield('content')
    </main>

    {{-- Bottom Navigation (Mobile) --}}
    <footer class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center h-18 bg-white/80 backdrop-blur-xl border-t border-outline-variant/20 z-50 py-3 shadow-[0_-4px_24px_rgba(21,66,18,0.06)]">
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">home</span>
            <span class="text-[10px] font-bold">الرئيسية</span>
        </a>
        <a href="{{ route('orders.index') ?? '#' }}" class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">list_alt</span>
            <span class="text-[10px] font-bold">سجلي</span>
        </a>
        <a href="{{ route('quotes.index') ?? '#' }}" class="flex flex-col items-center justify-center bg-primary-fixed text-primary rounded-full w-12 h-12 mb-1 scale-110 duration-300 hover:scale-125 transition-transform">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">local_offer</span>
            <span class="text-[8px] font-bold">عروضي</span>
        </a>
        <a href="{{ route('chat.index') }}" class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">support_agent</span>
            <span class="text-[10px] font-bold">الدعم</span>
        </a>
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">person</span>
            <span class="text-[10px] font-bold">حسابي</span>
        </a>
    </footer>

    @stack('scripts')

    <script>
        // Profile Dropdown Toggle
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');

        if (profileBtn && profileDropdown) {
            // Toggle dropdown on button click
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#profileBtn') && !e.target.closest('#profileDropdown')) {
                    profileDropdown.classList.add('hidden');
                }
            });

            // Close dropdown when clicking a link
            profileDropdown.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    profileDropdown.classList.add('hidden');
                });
            });
        }
    </script>
</body>
</html>
