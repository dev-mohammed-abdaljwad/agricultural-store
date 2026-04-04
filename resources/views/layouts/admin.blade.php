<!DOCTYPE html>

<html dir="rtl" lang="ar">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&amp;family=Tajawal:wght@400;500;700;900&amp;family=Manrope:wght@400;500;700&amp;family=Almarai:wght@400;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "tertiary-fixed": "#ffdea5",
              "on-primary-fixed-variant": "#23501e",
              "tertiary": "#4b3500",
              "surface-bright": "#fafaf5",
              "on-primary-fixed": "#002201",
              "surface-container": "#eeeee9",
              "on-secondary-fixed-variant": "#603f33",
              "surface-dim": "#dadad5",
              "surface": "#fafaf5",
              "surface-variant": "#e3e3de",
              "error-container": "#ffdad6",
              "tertiary-fixed-dim": "#e9c176",
              "on-tertiary-fixed": "#261900",
              "on-error-container": "#93000a",
              "on-secondary-container": "#795548",
              "outline": "#72796e",
              "surface-tint": "#3b6934",
              "background": "#fafaf5",
              "error": "#ba1a1a",
              "primary": "#154212",
              "secondary-fixed-dim": "#ebbcac",
              "inverse-primary": "#a1d494",
              "inverse-surface": "#2f312e",
              "on-primary": "#ffffff",
              "on-primary-container": "#9dd090",
              "surface-container-high": "#e8e8e3",
              "secondary-fixed": "#ffdbcf",
              "outline-variant": "#c2c9bb",
              "on-surface": "#1a1c19",
              "tertiary-container": "#674b0a",
              "on-tertiary-container": "#e4bd72",
              "surface-container-highest": "#e3e3de",
              "inverse-on-surface": "#f1f1ec",
              "on-background": "#1a1c19",
              "secondary-container": "#fdcdbc",
              "surface-container-low": "#f4f4ef",
              "on-secondary-fixed": "#2e150b",
              "primary-fixed": "#bcf0ae",
              "primary-fixed-dim": "#a1d494",
              "secondary": "#7a5649",
              "on-tertiary": "#ffffff",
              "on-secondary": "#ffffff",
              "surface-container-lowest": "#ffffff",
              "primary-container": "#2d5a27",
              "on-error": "#ffffff",
              "on-tertiary-fixed-variant": "#5d4201",
              "on-surface-variant": "#42493e"
            },
            fontFamily: {
              "headline": ["Tajawal", "Be Vietnam Pro", "sans-serif"],
              "body": ["Almarai", "Manrope", "sans-serif"],
              "label": ["Almarai", "Manrope", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem"},
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }

        body {
            font-family: 'Almarai', sans-serif;
            background-color: #fafaf5;
            color: #1a1c19;
        }
    </style>
</head>

<body class="flex min-h-screen bg-surface">
    <x-toast-container />
    
    <!-- SIDEBAR -->
    <aside id="adminSidebar" class="fixed right-0 top-0 h-screen w-64 z-40 bg-[#154212]
                                   transform translate-x-full lg:translate-x-0
                                   transition-transform duration-300 ease-in-out">
        <!-- Brand Section -->
        <div class="px-6 py-5 border-b border-white/10">
            <h1 class="text-xl font-black italic text-white font-headline">حصاد</h1>
            <p class="text-xs text-white/50 mt-1">لوحة الإدارة</p>
        </div>

        <!-- Admin Info -->
        <div class="px-6 py-4 border-b border-white/10">
            <div class="flex items-center gap-3 flex-row-reverse">
                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center">
                    <span class="text-primary font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-white text-sm font-headline">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-white/60 mt-0.5">مدير النظام</p>
                </div>
            </div>
        </div>

        <!-- Navigation Items -->
        <nav class="flex-1 pt-4 space-y-1 px-3">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-6 py-3 text-sm font-headline transition-colors rounded-l-xl flex-row-reverse
                      {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white font-bold border-r-4 border-primary-fixed' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span>لوحة التحكم</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="flex items-center gap-3 px-6 py-3 text-sm font-headline transition-colors rounded-l-xl flex-row-reverse
                      {{ request()->routeIs('admin.orders*') ? 'bg-white/10 text-white font-bold border-r-4 border-primary-fixed' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined">receipt_long</span>
                <span>الطلبات</span>
            </a>

            <a href="{{ route('admin.quotes.index') }}"
               class="flex items-center gap-3 px-6 py-3 text-sm font-headline transition-colors rounded-l-xl flex-row-reverse
                      {{ request()->routeIs('admin.quotes*') ? 'bg-white/10 text-white font-bold border-r-4 border-primary-fixed' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined">local_offer</span>
                <span>عروض الأسعار</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="flex items-center gap-3 px-6 py-3 text-sm font-headline transition-colors rounded-l-xl flex-row-reverse
                      {{ request()->routeIs('admin.products*') ? 'bg-white/10 text-white font-bold border-r-4 border-primary-fixed' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined">inventory_2</span>
                <span>المنتجات</span>
            </a>

            <a href="{{ route('admin.chat.index') }}"
               class="flex items-center gap-3 px-6 py-3 text-sm font-headline transition-colors rounded-l-xl flex-row-reverse
                      {{ request()->routeIs('chat.*') ? 'bg-white/10 text-white font-bold border-r-4 border-primary-fixed' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined">chat_bubble</span>
                <span>المحادثات</span>
                <span class="absolute left-6 top-2 bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">5</span>
            </a>

            <a href="{{ route('admin.delivery-agents.index') }}"
               class="flex items-center gap-3 px-6 py-3 text-sm font-headline transition-colors rounded-l-xl flex-row-reverse
                      {{ request()->routeIs('admin.delivery-agents*') ? 'bg-white/10 text-white font-bold border-r-4 border-primary-fixed' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined">local_shipping</span>
                <span>عمال التوصيل</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-6 py-3 text-sm font-headline transition-colors rounded-l-xl flex-row-reverse
                      {{ request()->routeIs('admin.users*') ? 'bg-white/10 text-white font-bold border-r-4 border-primary-fixed' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined">group</span>
                <span>العملاء</span>
            </a>

            <a href="{{ route('admin.analytics') }}"
               class="flex items-center gap-3 px-6 py-3 text-sm font-headline transition-colors rounded-l-xl flex-row-reverse
                      {{ request()->routeIs('admin.analytics') ? 'bg-white/10 text-white font-bold border-r-4 border-primary-fixed' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <span class="material-symbols-outlined">bar_chart</span>
                <span>التقارير</span>
            </a>
        </nav>

        <!-- Bottom Section -->
        <div class="p-6 border-t border-white/10 space-y-2">
            <a href="{{ route('admin.settings') }}"
               class="flex items-center gap-3 text-white/60 hover:text-white text-sm font-headline transition-colors w-full px-3 py-2 rounded-lg hover:bg-white/5 flex-row-reverse">
                <span class="material-symbols-outlined">settings</span>
                <span>الإعدادات</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 text-white/60 hover:text-red-300 text-sm font-headline transition-colors w-full px-3 py-2 rounded-lg hover:bg-white/5 flex-row-reverse">
                    <span class="material-symbols-outlined">logout</span>
                    <span>تسجيل الخروج</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- SIDEBAR OVERLAY (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col w-full lg:mr-64">
        <!-- TOP HEADER -->
        <header class="w-full h-16 sticky top-0 z-30 bg-[#fafaf5]/80 backdrop-blur-md
                       flex justify-between items-center px-4 lg:px-8
                       border-b border-stone-100 shadow-sm">

            <!-- LEFT SIDE -->
            <div class="flex items-center gap-4 lg:gap-6 flex-1">
                <!-- Mobile Menu Button -->
                <button onclick="toggleSidebar()" class="lg:hidden text-stone-600 hover:text-stone-900 p-2">
                    <span class="material-symbols-outlined">menu</span>
                </button>

                <!-- Page Title -->
                <h2 class="hidden sm:block text-lg lg:text-xl font-bold text-[#154212] font-headline">
                    سوق المحاصيل المصري
                </h2>

                <!-- Search Bar (Desktop only) -->
                <div class="relative hidden lg:block">
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant opacity-50">
                        search
                    </span>
                    <input type="text"
                           placeholder="ابحث عن طلبات أو منتجات..."
                           class="bg-surface-container-highest border-none rounded-full pr-10 pl-4 py-1.5 text-sm w-80
                                  focus:ring-2 focus:ring-primary/20 transition-all" />
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="flex items-center gap-2 lg:gap-4">
                <!-- Icon Buttons -->
                <div class="flex items-center gap-1">
                    <button class="hover:bg-[#f4f4ef] rounded-full p-2 text-on-surface-variant transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <button class="hover:bg-[#f4f4ef] rounded-full p-2 text-on-surface-variant transition-colors hidden sm:block">
                        <span class="material-symbols-outlined">help_outline</span>
                    </button>
                </div>

                <!-- Divider -->
                <div class="h-8 w-px bg-outline-variant opacity-20 mx-1 lg:mx-2 hidden sm:block"></div>

                <!-- Admin Profile Dropdown -->
                <div class="relative group flex items-center gap-2 lg:gap-3">
                    <!-- Profile Button -->
                    <button type="button" id="adminMenuBtn"
                            class="hover:bg-[#f4f4ef] rounded-full p-2 text-on-surface-variant transition-colors focus:outline-none">
                        <span class="material-symbols-outlined">account_circle</span>
                    </button>

                    <!-- Profile Info (hidden on mobile) -->
                    <div class="text-left hidden sm:block">
                        <p class="text-xs font-bold font-headline text-on-surface">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-on-surface-variant">مدير النظام</p>
                    </div>

                    <!-- Avatar -->
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ Auth::user()->profile_photo_path }}"
                             alt="صورة المسؤول"
                             class="w-8 lg:w-10 h-8 lg:h-10 rounded-full object-cover border-2 border-primary-fixed" />
                    @else
                        <div class="w-8 lg:w-10 h-8 lg:h-10 rounded-full bg-primary-fixed flex items-center justify-center border-2 border-primary-fixed">
                            <span class="text-primary font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    @endif

                    <!-- DESKTOP DROPDOWN (Hover) -->
                    <div class="absolute left-0 top-full mt-2 w-56 bg-surface-container-lowest rounded-xl shadow-lg
                                opacity-0 invisible group-hover:opacity-100 group-hover:visible
                                transition-all duration-200 z-50 border border-outline-variant/20
                                hidden sm:block">
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-outline-variant/20">
                            <p class="font-bold text-primary text-sm">{{ Auth::user()->name }}</p>
                            <p class="text-on-surface-variant text-xs">{{ Auth::user()->email }}</p>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('admin.settings') }}"
                               class="flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-high
                                      transition-colors text-sm font-headline">
                                <span class="material-symbols-outlined text-lg">settings</span>
                                <span>الإعدادات</span>
                            </a>

                            <div class="border-t border-outline-variant/20 my-2"></div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left flex items-center gap-3 px-4 py-2 text-error hover:bg-error/10
                                               transition-colors text-sm font-headline">
                                    <span class="material-symbols-outlined text-lg">logout</span>
                                    <span>تسجيل الخروج</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- MOBILE DROPDOWN (Click-triggered) -->
                    <div id="adminDropdownMobile"
                         class="absolute left-0 top-full mt-2 w-56 bg-surface-container-lowest rounded-xl shadow-lg
                                opacity-0 invisible transition-all duration-200 z-50 border border-outline-variant/20
                                sm:hidden">
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-outline-variant/20">
                            <p class="font-bold text-primary text-sm">{{ Auth::user()->name }}</p>
                            <p class="text-on-surface-variant text-xs">{{ Auth::user()->email }}</p>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="{{ route('admin.settings') }}"
                               class="flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-high
                                      transition-colors text-sm font-headline">
                                <span class="material-symbols-outlined text-lg">settings</span>
                                <span>الإعدادات</span>
                            </a>

                            <div class="border-t border-outline-variant/20 my-2"></div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left flex items-center gap-3 px-4 py-2 text-error hover:bg-error/10
                                               transition-colors text-sm font-headline">
                                    <span class="material-symbols-outlined text-lg">logout</span>
                                    <span>تسجيل الخروج</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- PAGE CANVAS -->
        <main class="p-4 lg:p-8 max-w-full lg:max-w-7xl lg:mx-auto w-full space-y-8 lg:space-y-12">
            @yield('content')
        </main>
    </div>

    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebar && overlay) {
                sidebar.classList.toggle('translate-x-full');
                sidebar.classList.toggle('translate-x-0');
                overlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            }
        }

        // Mobile admin dropdown toggle
        document.addEventListener('DOMContentLoaded', function () {
            const adminMenuBtn = document.getElementById('adminMenuBtn');
            const adminDropdownMobile = document.getElementById('adminDropdownMobile');

            if (adminMenuBtn && adminDropdownMobile) {
                // Click to toggle
                adminMenuBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const isVisible = adminDropdownMobile.classList.contains('opacity-100');
                    if (isVisible) {
                        adminDropdownMobile.classList.add('opacity-0', 'invisible');
                        adminDropdownMobile.classList.remove('opacity-100', 'visible');
                    } else {
                        adminDropdownMobile.classList.remove('opacity-0', 'invisible');
                        adminDropdownMobile.classList.add('opacity-100', 'visible');
                    }
                });

                // Close on outside click
                document.addEventListener('click', function (e) {
                    if (!e.target.closest('#adminMenuBtn') && !e.target.closest('#adminDropdownMobile')) {
                        adminDropdownMobile.classList.add('opacity-0', 'invisible');
                        adminDropdownMobile.classList.remove('opacity-100', 'visible');
                    }
                });
            }

            // Close sidebar on resize to desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    const sidebar = document.getElementById('adminSidebar');
                    const overlay = document.getElementById('sidebarOverlay');
                    if (sidebar) {
                        sidebar.classList.remove('translate-x-0');
                        sidebar.classList.add('translate-x-full');
                    }
                    if (overlay) overlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });
    </script>

    <!-- Universal Modal Component -->
    @include('components.modal')
</body>

</html>