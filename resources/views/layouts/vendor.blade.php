<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم - نيل هارفست')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&family=Tajawal:wght@400;500;700;900&family=Manrope:wght@400;500;700&family=Almarai:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen bg-surface" dir="rtl">
    <!-- Sidebar Navigation -->
    <aside class="hidden lg:fixed lg:right-0 lg:top-0 lg:h-screen lg:w-64 lg:border-l lg:border-stone-200 lg:bg-surface lg:flex lg:flex-col lg:p-4 lg:z-40">
        <!-- Logo -->
        <div class="mb-8 sm:mb-10 px-2 flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center text-primary-fixed">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">agriculture</span>
            </div>
            <div>
                <h1 class="text-lg md:text-xl font-black text-primary font-headline leading-tight">المزرعة الرقمية</h1>
                <p class="text-xs text-on-surface-variant opacity-70">لوحة تحكم التاجر</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 space-y-2">
            <a class="flex items-center gap-3 {{ request()->routeIs('vendor.dashboard') ? 'bg-primary-fixed text-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }} rounded-lg px-4 py-3 transition-all" href="{{ route('vendor.dashboard') }}">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                <span class="font-headline font-bold text-lg">نظرة عامة</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('vendor.products.*') ? 'bg-primary-fixed text-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }} rounded-lg px-4 py-3 transition-all" href="{{ route('vendor.products.index') }}">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="font-headline font-bold text-lg">منتجاتي</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('vendor.orders.*') ? 'bg-primary-fixed text-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }} rounded-lg px-4 py-3 transition-all" href="{{ route('vendor.orders.index') }}">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span class="font-headline font-bold text-lg">الطلبات</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('vendor.analytics') ? 'bg-primary-fixed text-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }} rounded-lg px-4 py-3 transition-all" href="{{ route('vendor.analytics') }}">
                <span class="material-symbols-outlined">analytics</span>
                <span class="font-headline font-bold text-lg">التحليلات</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('vendor.settings') ? 'bg-primary-fixed text-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }} rounded-lg px-4 py-3 transition-all" href="{{ route('vendor.settings') }}">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-headline font-bold text-lg">الإعدادات</span>
            </a>
        </nav>

        <!-- Add Product Button -->
        <div class="mt-auto pt-6 border-t border-outline-variant/15">
            <a href="{{ route('products.create') ?? '#' }}" class="w-full bg-primary text-on-primary py-3 md:py-4 rounded-xl font-bold flex items-center justify-center gap-2 hover:opacity-90 transition-opacity text-sm md:text-base">
                <span class="material-symbols-outlined">add_circle</span>
                <span>إضافة منتج</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col lg:mr-64">
        <!-- Top Header -->
        <header class="w-full h-16 sticky top-0 z-30 bg-surface/80 backdrop-blur-md flex justify-between items-center px-4 sm:px-6 md:px-8 border-b border-stone-100 shadow-sm">
            <!-- Left Side -->
            <div class="flex items-center gap-3 sm:gap-4 md:gap-6 flex-1">
                <!-- Mobile Menu Button -->
                <button class="lg:hidden p-2 hover:bg-surface-container rounded-lg transition-colors" onclick="toggleSidebar()">
                    <span class="material-symbols-outlined text-xl">menu</span>
                </button>

                <h2 class="text-base sm:text-lg md:text-xl font-bold text-primary font-headline">سوق المحاصيل المصري</h2>
                <div class="relative hidden lg:block">
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant opacity-50">search</span>
                    <input class="bg-surface-container-highest border-none rounded-full pr-10 pl-4 py-1.5 text-xs sm:text-sm w-64 focus:ring-2 focus:ring-primary/20 transition-all" placeholder="ابحث عن طلبات..." type="text"/>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center gap-2 sm:gap-3 md:gap-4">
                <!-- Notification & Help -->
                <div class="flex items-center gap-1">
                    <button class="hover:bg-surface-container rounded-full p-2 text-on-surface-variant transition-colors">
                        <span class="material-symbols-outlined text-lg md:text-xl">notifications</span>
                    </button>
                    <button class="hover:bg-surface-container rounded-full p-2 text-on-surface-variant transition-colors hidden sm:block">
                        <span class="material-symbols-outlined text-lg md:text-xl">help_outline</span>
                    </button>
                </div>

                <!-- Divider -->
                <div class="h-8 w-px bg-outline-variant opacity-20 mx-1 hidden sm:block"></div>

                <!-- User Profile -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="text-left hidden sm:block">
                        <p class="text-xs font-bold font-headline text-on-surface">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-on-surface-variant">
                            {{ Auth::user()->customer_type === 'trader' ? 'تاجر' : 'مزارع' }}
                        </p>
                    </div>
                    <img alt="صورة المستخدم" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full object-cover border-2 border-primary-fixed"
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuCWqi8GXEbaVwzOGFuNZC5m6nYEEZ51jDorvlwL7mnPgTg8H9QFUS4JZAd8nLdKow3IVfXWD-NspzwcxO-dgXrHBbn0u2miFTRKkC0MRuWx6LQpxRCjo7sgII7TDAG8cVP8QhutB-o67DDJ4hMDG_0eLL5JOBgKQBQruUXS_cd3JF4nILaZ760yDnDHa_eynFg1HOlTMi6OpqklOkz0dI5VKYhcwOfFyHstx6tyakE-n4vVzxomwC2ZWyQ310qtun1faroQejbUeBBA"/>
                </div>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hidden sm:block hover:bg-surface-container rounded-full p-2 text-on-surface-variant transition-colors" title="تسجيل الخروج">
                        <span class="material-symbols-outlined text-lg md:text-xl">logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        @yield('content')
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebar" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Mobile Sidebar -->
    <aside id="mobileSidebarContent" class="fixed right-0 top-0 h-screen w-64 bg-surface border-l border-stone-200 flex flex-col p-4 z-40 hidden lg:hidden transform transition-transform duration-300">
        <!-- Logo -->
        <div class="mb-10 px-2 flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center text-primary-fixed">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">agriculture</span>
            </div>
            <div>
                <h1 class="text-xl font-black text-primary font-headline leading-tight">المزرعة الرقمية</h1>
                <p class="text-xs text-on-surface-variant opacity-70">لوحة تحكم التاجر</p>
            </div>
            <button onclick="toggleSidebar()" class="mr-auto">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 space-y-2">
            <a class="flex items-center gap-3 {{ request()->routeIs('vendor.dashboard') ? 'bg-primary-fixed text-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }} rounded-lg px-4 py-3 transition-all" href="{{ route('vendor.dashboard') }}" onclick="toggleSidebar()">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                <span class="font-headline font-bold text-lg">نظرة عامة</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('vendor.products.*') ? 'bg-primary-fixed text-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }} rounded-lg px-4 py-3 transition-all" href="{{ route('vendor.products.index') }}" onclick="toggleSidebar()">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="font-headline font-bold text-lg">منتجاتي</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('vendor.orders.*') ? 'bg-primary-fixed text-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }} rounded-lg px-4 py-3 transition-all" href="{{ route('vendor.orders.index') }}" onclick="toggleSidebar()">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span class="font-headline font-bold text-lg">الطلبات</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('vendor.analytics') ? 'bg-primary-fixed text-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }} rounded-lg px-4 py-3 transition-all" href="{{ route('vendor.analytics') }}" onclick="toggleSidebar()">
                <span class="material-symbols-outlined">analytics</span>
                <span class="font-headline font-bold text-lg">التحليلات</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('vendor.settings') ? 'bg-primary-fixed text-primary' : 'text-on-surface-variant hover:bg-surface-container-low' }} rounded-lg px-4 py-3 transition-all" href="{{ route('vendor.settings') }}" onclick="toggleSidebar()">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-headline font-bold text-lg">الإعدادات</span>
            </a>
        </nav>

        <!-- Add Product Button -->
        <div class="mt-auto pt-6 border-t border-outline-variant/15">
            <a href="{{ route('products.create') ?? '#' }}" class="w-full bg-primary text-on-primary py-4 rounded-xl font-bold flex items-center justify-center gap-2 hover:opacity-90 transition-opacity" onclick="toggleSidebar()">
                <span class="material-symbols-outlined">add_circle</span>
                <span>إضافة منتج</span>
            </a>
        </div>
    </aside>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const sidebarContent = document.getElementById('mobileSidebarContent');
            sidebar.classList.toggle('hidden');
            sidebarContent.classList.toggle('hidden');
        }
    </script>
</body>
</html>
