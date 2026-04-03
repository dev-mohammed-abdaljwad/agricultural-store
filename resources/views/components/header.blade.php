<!-- Header Navigation -->
<nav class="fixed top-0 w-full z-50 bg-[#fafaf5]/80 backdrop-blur-md flex flex-row-reverse justify-between items-center px-4 sm:px-8 h-16 shadow-sm">
    <div class="flex items-center gap-4 sm:gap-8">
        <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-black text-primary tracking-tight font-headline">
            Nile Harvest
        </a>
        <div class="hidden lg:flex flex-row-reverse items-center gap-6">
            <a class="text-on-surface-variant hover:text-primary font-headline font-bold text-sm lg:text-lg transition-colors duration-200 {{ request()->routeIs('home') ? 'text-primary relative after:content-[\'\'] after:absolute after:-bottom-1 after:right-0 after:w-full after:h-1 after:bg-primary-fixed after:rounded-full' : '' }}" 
                href="{{ route('home') }}">الرئيسية</a>
            <a class="text-on-surface-variant hover:text-primary font-headline font-bold text-sm lg:text-lg transition-colors duration-200 {{ request()->routeIs('products.index', 'products.show') ? 'text-primary relative after:content-[\'\'] after:absolute after:-bottom-1 after:right-0 after:w-full after:h-1 after:bg-primary-fixed after:rounded-full' : '' }}" 
                href="{{ route('products.index') }}">السوق</a>
            @auth
                <a class="text-on-surface-variant hover:text-primary font-headline font-bold text-sm lg:text-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'text-primary relative after:content-[\'\'] after:absolute after:-bottom-1 after:right-0 after:w-full after:h-1 after:bg-primary-fixed after:rounded-full' : '' }}" 
                    href="{{ route('dashboard') }}">طلباتي</a>
            @endauth
        </div>
    </div>
    
    <div class="flex items-center gap-2 sm:gap-4">
        @auth
            <a href="{{ route('cart.index') }}" class="p-2 text-on-surface-variant hover:text-primary transition-transform active:scale-95 relative" title="السلة">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span class="absolute top-0 right-0 bg-error text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold cart-count" id="cartCount">0</span>
            </a>
            <!-- User Profile Dropdown -->
            <div class="relative group">
                <button type="button" id="userMenuBtn" class="p-2 text-on-surface-variant hover:text-primary transition-transform active:scale-95 flex items-center gap-2 focus:outline-none">
                    <span class="material-symbols-outlined">account_circle</span>
                </button>
                
                <!-- Dropdown Menu -->
                <div id="userDropdown" class="absolute left-0 mt-2 w-56 bg-surface-container-lowest rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 border border-outline-variant/20 hidden sm:block">
                    <!-- User Info -->
                    <div class="px-4 py-3 border-b border-outline-variant/20">
                        <p class="font-bold text-primary text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-on-surface-variant text-xs">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <!-- Menu Items -->
                    <div class="py-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors text-sm font-headline">
                            <span class="material-symbols-outlined text-lg">dashboard</span>
                            لوحة التحكم
                        </a>
                        
                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors text-sm font-headline">
                            <span class="material-symbols-outlined text-lg">person</span>
                            الملف الشخصي
                        </a>
                        
                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors text-sm font-headline">
                            <span class="material-symbols-outlined text-lg">settings</span>
                            الإعدادات
                        </a>
                        
                        <div class="border-t border-outline-variant/20 my-2"></div>
                        
                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-2 text-error hover:bg-error/10 transition-colors text-sm font-headline">
                                <span class="material-symbols-outlined text-lg">logout</span>
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Mobile Dropdown -->
                <div id="userDropdownMobile" class="absolute left-0 mt-2 w-56 bg-surface-container-lowest rounded-xl shadow-lg opacity-0 invisible transition-all duration-200 z-50 border border-outline-variant/20 sm:hidden">
                    <!-- User Info -->
                    <div class="px-4 py-3 border-b border-outline-variant/20">
                        <p class="font-bold text-primary text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-on-surface-variant text-xs">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <!-- Menu Items -->
                    <div class="py-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors text-sm font-headline">
                            <span class="material-symbols-outlined text-lg">dashboard</span>
                            لوحة التحكم
                        </a>
                        
                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors text-sm font-headline">
                            <span class="material-symbols-outlined text-lg">person</span>
                            الملف الشخصي
                        </a>
                        
                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-on-surface-variant hover:text-primary hover:bg-surface-container-high transition-colors text-sm font-headline">
                            <span class="material-symbols-outlined text-lg">settings</span>
                            الإعدادات
                        </a>
                        
                        <div class="border-t border-outline-variant/20 my-2"></div>
                        
                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-2 text-error hover:bg-error/10 transition-colors text-sm font-headline">
                                <span class="material-symbols-outlined text-lg">logout</span>
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="p-2 text-on-surface-variant hover:text-primary transition-transform active:scale-95">
                <span class="material-symbols-outlined">account_circle</span>
            </a>
        @endauth
    </div>
</nav>

@auth
<script>
    // Load cart count on page load
    function updateCartCount() {
        fetch('{{ route("cart.count") }}')
            .then(response => response.json())
            .then(data => {
                const cartCountEl = document.getElementById('cartCount');
                if (cartCountEl) {
                    cartCountEl.textContent = data.count || 0;
                    // Hide badge if count is 0
                    if (data.count === 0) {
                        cartCountEl.classList.add('hidden');
                    } else {
                        cartCountEl.classList.remove('hidden');
                    }
                }
            })
            .catch(error => console.log('Cart count update failed'));
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Load cart count on page load
        updateCartCount();
        
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdownMobile = document.getElementById('userDropdownMobile');
        
        if (userMenuBtn && userDropdownMobile) {
            userMenuBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const isVisible = userDropdownMobile.classList.contains('opacity-100');
                if (isVisible) {
                    userDropdownMobile.classList.add('opacity-0', 'invisible');
                    userDropdownMobile.classList.remove('opacity-100', 'visible');
                } else {
                    userDropdownMobile.classList.remove('opacity-0', 'invisible');
                    userDropdownMobile.classList.add('opacity-100', 'visible');
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('[id^="userMenu"]') && !e.target.closest('[id^="userDropdown"]')) {
                    userDropdownMobile.classList.add('opacity-0', 'invisible');
                    userDropdownMobile.classList.remove('opacity-100', 'visible');
                }
            });
        }
    });

    // Update cart count after adding/removing items
    window.updateCartCount = updateCartCount;
</script>
@endauth
