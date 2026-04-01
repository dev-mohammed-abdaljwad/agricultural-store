<!-- SideNavBar -->
<aside
    class="h-screen w-64 border-l border-stone-200 dark:border-stone-800 sticky right-0 top-0 bg-[#fafaf5] dark:bg-stone-900 fixed right-0 top-0 h-full flex flex-col p-4 z-40">
    <div class="mb-10 px-2 flex items-center gap-3">
        <div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center text-primary-fixed">
            <span class="material-symbols-outlined" data-icon="agriculture">agriculture</span>
        </div>
        <div>
            <h1 class="text-xl font-black text-[#154212] dark:text-green-500 font-headline leading-tight">المزرعة
                الرقمية</h1>
            <p class="text-xs text-on-surface-variant opacity-70">لوحة تحكم الإدارة</p>
        </div>
    </div>
    <nav class="flex-1 space-y-2">
        <!-- Dashboard -->
        <a class="flex items-center gap-3 {{ request()->routeIs('admin.dashboard') ? 'bg-[#bcf0ae] dark:bg-green-900/30 text-[#154212] dark:text-green-300 scale-[0.98]' : 'text-[#42493e] dark:text-stone-400 hover:bg-[#e3e3de] dark:hover:bg-stone-800 hover:text-[#1a1c19]' }} rounded-lg px-4 py-3 transition-all"
            href="{{ route('admin.dashboard') }}">
            <span class="material-symbols-outlined" data-icon="dashboard"
                style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.dashboard') ? 1 : 0 }};">dashboard</span>
            <span class="font-['Be_Vietnam_Pro','Tajawal'] font-bold text-lg">نظرة عامة</span>
        </a>

        <!-- Products -->
        <a class="flex items-center gap-3 {{ request()->routeIs('admin.products.*') ? 'bg-[#bcf0ae] dark:bg-green-900/30 text-[#154212] dark:text-green-300 scale-[0.98]' : 'text-[#42493e] dark:text-stone-400 hover:bg-[#e3e3de] dark:hover:bg-stone-800 hover:text-[#1a1c19]' }} rounded-lg px-4 py-3 transition-all"
            href="{{ route('admin.products.index') }}">
            <span class="material-symbols-outlined" data-icon="inventory_2">inventory_2</span>
            <span class="font-['Be_Vietnam_Pro','Tajawal'] font-bold text-lg">المنتجات</span>
        </a>

        <!-- Orders -->
        <a class="flex items-center gap-3 {{ request()->routeIs('admin.orders.*') ? 'bg-[#bcf0ae] dark:bg-green-900/30 text-[#154212] dark:text-green-300 scale-[0.98]' : 'text-[#42493e] dark:text-stone-400 hover:bg-[#e3e3de] dark:hover:bg-stone-800 hover:text-[#1a1c19]' }} rounded-lg px-4 py-3 transition-all"
            href="{{ route('admin.orders.index') }}">
            <span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
            <span class="font-['Be_Vietnam_Pro','Tajawal'] font-bold text-lg">الطلبات</span>
        </a>

        <!-- Users -->
        <a class="flex items-center gap-3 {{ request()->routeIs('admin.users.*') ? 'bg-[#bcf0ae] dark:bg-green-900/30 text-[#154212] dark:text-green-300 scale-[0.98]' : 'text-[#42493e] dark:text-stone-400 hover:bg-[#e3e3de] dark:hover:bg-stone-800 hover:text-[#1a1c19]' }} rounded-lg px-4 py-3 transition-all"
            href="{{ route('admin.users.index') }}">
            <span class="material-symbols-outlined" data-icon="group">group</span>
            <span class="font-['Be_Vietnam_Pro','Tajawal'] font-bold text-lg">المستخدمون</span>
        </a>

        <!-- Analytics -->
        <a class="flex items-center gap-3 {{ request()->routeIs('admin.analytics') ? 'bg-[#bcf0ae] dark:bg-green-900/30 text-[#154212] dark:text-green-300 scale-[0.98]' : 'text-[#42493e] dark:text-stone-400 hover:bg-[#e3e3de] dark:hover:bg-stone-800 hover:text-[#1a1c19]' }} rounded-lg px-4 py-3 transition-all"
            href="{{ route('admin.analytics') }}">
            <span class="material-symbols-outlined" data-icon="analytics">analytics</span>
            <span class="font-['Be_Vietnam_Pro','Tajawal'] font-bold text-lg">التحليلات</span>
        </a>

        <!-- Settings -->
        <a class="flex items-center gap-3 {{ request()->routeIs('admin.settings') ? 'bg-[#bcf0ae] dark:bg-green-900/30 text-[#154212] dark:text-green-300 scale-[0.98]' : 'text-[#42493e] dark:text-stone-400 hover:bg-[#e3e3de] dark:hover:bg-stone-800 hover:text-[#1a1c19]' }} rounded-lg px-4 py-3 transition-all"
            href="{{ route('admin.settings') }}">
            <span class="material-symbols-outlined" data-icon="settings">settings</span>
            <span class="font-['Be_Vietnam_Pro','Tajawal'] font-bold text-lg">الإعدادات</span>
        </a>
    </nav>
    <div class="mt-auto pt-6">
        <button onclick="openProductModal()"
            class="w-full bg-primary text-on-primary py-4 rounded-xl font-bold flex items-center justify-center gap-2 hover:opacity-90 transition-opacity">
            <span class="material-symbols-outlined" data-icon="add_circle">add_circle</span>
            <span>إضافة منتج جديد</span>
        </button>
    </div>
</aside>
