@extends('layouts.app')

@section('title', $product->name . ' - The Fertile Estate')

@section('content')
<div class="bg-surface min-h-screen" lang="ar" dir="rtl">
    <!-- NAVBAR (Fixed Top) -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-surface/80 backdrop-blur-md border-b border-[#e3e3de]/15 shadow-sm">
        <div class="max-w-[1440px] mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Right: Brand -->
            <div class="flex-shrink-0">
                <h1 class="italic font-black text-primary text-2xl font-headline">The Fertile Estate</h1>
            </div>

            <!-- Center: Desktop Navigation (Hidden on Mobile) -->
            <div class="hidden md:flex gap-8 items-center">
                <a href="#" class="text-on-surface-variant hover:text-primary transition-colors">Vendor Portal</a>
                <a href="#" class="text-primary border-b-4 border-primary-fixed pb-2 font-bold">Products</a>
                <a href="#" class="text-on-surface-variant hover:text-primary transition-colors">My Orders</a>
            </div>

            <!-- Left: Icon Buttons -->
            <div class="flex gap-4 items-center">
                <button class="text-primary hover:text-primary-container transition-colors">
                    <span class="material-symbols-outlined">language</span>
                </button>
                <button class="text-primary hover:text-primary-container transition-colors">
                    <span class="material-symbols-outlined">shopping_cart</span>
                </button>
                <button class="text-primary hover:text-primary-container transition-colors">
                    <span class="material-symbols-outlined">account_circle</span>
                </button>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- SIDEBAR (Desktop Only) -->
        <aside class="hidden lg:flex w-64 flex-col fixed right-0 top-16 h-[calc(100vh-64px)] bg-surface border-l border-[#e3e3de]/15 p-6">
            <div class="mb-8">
                <h2 class="text-lg font-bold text-primary font-headline">Welcome, Farmer</h2>
                <p class="text-on-surface-variant text-xs mt-1">Verified Egyptian Producer</p>
            </div>

            <nav class="space-y-2 flex-1">
                <!-- Active Item -->
                <div class="flex items-center gap-3 flex-row-reverse bg-primary-fixed text-primary rounded-l-full font-bold pr-6 py-3">
                    <span class="material-symbols-outlined">pest_control</span>
                    <span class="font-headline">Pesticides</span>
                </div>

                <!-- Inactive Items -->
                <a href="#" class="flex items-center gap-3 flex-row-reverse text-on-surface-variant pr-6 py-3 hover:bg-surface-container-low rounded-l-full transition-transform hover:translate-x-[-4px] duration-200">
                    <span class="material-symbols-outlined">park</span>
                    <span class="font-headline">Crops</span>
                </a>
                <a href="#" class="flex items-center gap-3 flex-row-reverse text-on-surface-variant pr-6 py-3 hover:bg-surface-container-low rounded-l-full transition-transform hover:translate-x-[-4px] duration-200">
                    <span class="material-symbols-outlined">factory</span>
                    <span class="font-headline">Manufacturers</span>
                </a>
                <a href="#" class="flex items-center gap-3 flex-row-reverse text-on-surface-variant pr-6 py-3 hover:bg-surface-container-low rounded-l-full transition-transform hover:translate-x-[-4px] duration-200">
                    <span class="material-symbols-outlined">agriculture</span>
                    <span class="font-headline">Fertilizers</span>
                </a>
                <a href="#" class="flex items-center gap-3 flex-row-reverse text-on-surface-variant pr-6 py-3 hover:bg-surface-container-low rounded-l-full transition-transform hover:translate-x-[-4px] duration-200">
                    <span class="material-symbols-outlined">shield_with_heart</span>
                    <span class="font-headline">Safety Gear</span>
                </a>
            </nav>

            <button class="w-full bg-primary text-on-primary py-3 rounded-md font-bold text-sm hover:bg-primary-container transition-colors">
                Request Consultation
            </button>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 lg:pr-64 pt-24 pb-20 md:pb-12 px-6 max-w-[1440px] mx-auto w-full">
            <!-- BREADCRUMB -->
            <nav class="flex items-center gap-2 flex-row-reverse text-on-surface-variant text-sm mb-8">
                <span class="text-primary font-bold">{{ $product->name }}</span>
                <span class="material-symbols-outlined text-xs">chevron_left</span>
                <a href="#" class="hover:text-primary transition-colors">{{ $product->category->name }}</a>
                <span class="material-symbols-outlined text-xs">chevron_left</span>
                <a href="#" class="hover:text-primary transition-colors">الرئيسية</a>
            </nav>

            <!-- PRODUCT HERO SECTION -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-12 items-start mb-16">
                <!-- LEFT: IMAGE GALLERY -->
                <div class="xl:col-span-7">
                    <div class="relative rounded-xl overflow-hidden shadow-sm bg-surface-container-lowest aspect-[4/3] group">
                        <!-- Certified Badge -->
                        @if($product->is_certified)
                            <div class="absolute top-4 right-4 z-10">
                                <span class="bg-tertiary-fixed text-on-tertiary-fixed px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1">verified</span>
                                    منتج معتمد
                                </span>
                            </div>
                        @endif

                        <!-- Main Image -->
                        @if($product->images->count() > 0)
                            <img id="mainImage" 
                                 src="{{ $product->images->first()->asset_url }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-6xl">image</span>
                            </div>
                        @endif
                    </div>

                    <!-- THUMBNAILS -->
                    @if($product->images->count() > 0)
                        <div class="grid grid-cols-4 gap-4 mt-4">
                            @foreach($product->images->take(4) as $index => $image)
                                <button onclick="document.getElementById('mainImage').src='{{ $image->asset_url }}'"
                                        class="aspect-square rounded-lg overflow-hidden {{ $index === 0 ? 'border-2 border-primary-fixed' : 'border border-outline-variant hover:bg-surface-container-high' }} transition-colors cursor-pointer">
                                    <img src="{{ $image->asset_url }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach

                            @if($product->images->count() > 4)
                                <div class="aspect-square rounded-lg overflow-hidden border border-outline-variant relative flex items-center justify-center bg-surface-container-low">
                                    <img src="{{ $product->images->get(4)?->asset_url }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover opacity-40">
                                    <span class="absolute text-lg font-bold text-primary">+{{ $product->images->count() - 4 }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- RIGHT: PURCHASE CARD -->
                <div class="xl:col-span-5">
                    <div class="bg-surface-container-low p-8 rounded-xl space-y-6">
                        <!-- Supplier Label -->
                        @if(isset($product->supplier_label))
                            <p class="text-tertiary font-bold tracking-widest text-xs uppercase font-label">{{ $product->supplier_label ?? 'الموردة' }}</p>
                        @endif

                        <!-- Product Name -->
                        <h1 class="text-4xl font-black text-primary mt-2 font-headline leading-tight">{{ $product->name }}</h1>

                        <!-- Description -->
                        @if($product->description)
                            <p class="text-on-surface-variant mt-4 text-sm leading-relaxed">{{ $product->description }}</p>
                        @endif

                        <!-- Quantity Section -->
                        <div class="space-y-3">
                            <label class="text-sm font-bold text-on-surface">الكمية ({{ $product->unit ?? 'عبوة' }})</label>
                            <div class="flex items-center gap-4">
                                <div class="flex bg-surface-container-highest rounded-md overflow-hidden flex-row-reverse">
                                    <button class="p-3 hover:bg-outline-variant transition-colors">
                                        <span class="material-symbols-outlined text-sm">remove</span>
                                    </button>
                                    <input type="number" value="{{ $product->min_order_qty ?? 1 }}" 
                                           min="{{ $product->min_order_qty ?? 1 }}"
                                           class="w-16 text-center font-bold bg-transparent outline-none">
                                    <button class="p-3 hover:bg-outline-variant transition-colors">
                                        <span class="material-symbols-outlined text-sm">add</span>
                                    </button>
                                </div>
                                <button class="flex-1 bg-primary text-on-primary py-4 rounded-md font-black flex items-center justify-center gap-2 hover:bg-primary-container active:scale-95 shadow-md transition-all">
                                    <span class="material-symbols-outlined">shopping_cart</span>
                                    إضافة إلى السلة
                                </button>
                            </div>
                        </div>

                        <!-- Shipping Info Box -->
                        <div class="bg-surface-container-highest/50 p-4 rounded-lg border-r-4 border-tertiary space-y-3">
                            <div class="flex items-center gap-2 flex-row-reverse">
                                <span class="material-symbols-outlined text-tertiary">local_shipping</span>
                                <span class="font-bold text-sm">{{ $product->shipping_partner ?? 'شركة النصر للخدمات اللوجستية' }}</span>
                            </div>
                            <p class="text-xs text-on-surface-variant leading-relaxed">{{ $product->shipping_note ?? 'خدمة توصيل سريعة وآمنة لجميع محافظات مصر' }}</p>
                            @if(isset($product->shipping_regions) && is_array($product->shipping_regions))
                                <div class="flex gap-2 flex-wrap">
                                    @foreach($product->shipping_regions as $region)
                                        <span class="bg-primary-fixed/30 text-primary text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $region }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- PRODUCT INFO TABS -->
            <div class="mt-16">
                <!-- Tab Bar -->
                <div class="flex gap-8 border-b border-outline-variant/20 mb-8 overflow-x-auto" style="scrollbar-width: none;">
                    <button data-tab="specs" class="tab-btn active text-primary font-bold border-b-2 border-primary pb-4 whitespace-nowrap text-sm md:text-base cursor-pointer">
                        المواصفات الفنية
                    </button>
                    <button data-tab="usage" class="tab-btn text-on-surface-variant font-medium hover:text-primary whitespace-nowrap pb-4 text-sm md:text-base cursor-pointer transition-colors">
                        طريقة الاستخدام
                    </button>
                    <button data-tab="safety" class="tab-btn text-on-surface-variant font-medium hover:text-primary whitespace-nowrap pb-4 text-sm md:text-base cursor-pointer transition-colors">
                        إرشادات السلامة
                    </button>
                    <button data-tab="manufacturer" class="tab-btn text-on-surface-variant font-medium hover:text-primary whitespace-nowrap pb-4 text-sm md:text-base cursor-pointer transition-colors">
                        بيانات المصنع
                    </button>
                </div>

                <!-- SPECS TAB (Active by default) -->
                <div id="tab-specs" class="tab-content active">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Left: Specs Table -->
                        <div class="space-y-6">
                            <h2 class="text-2xl font-black text-primary font-headline">المواصفات الفنية</h2>
                            <div class="space-y-4">
                                @if($product->specs && $product->specs->count() > 0)
                                    @foreach($product->specs as $spec)
                                        <div class="flex justify-between py-3 border-b border-outline-variant/10">
                                            <span class="text-on-surface-variant">{{ $spec->key }}</span>
                                            <span class="font-bold text-on-surface">{{ $spec->value }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-on-surface-variant">لا توجد مواصفات متاحة</p>
                                @endif
                            </div>
                        </div>

                        <!-- Right: Expert Tip Card -->
                        <div class="bg-surface-container-low p-8 rounded-xl border-t-4 border-primary">
                            <h3 class="text-xl font-black text-primary mb-4 font-headline">توصية الخبراء</h3>
                            <p class="text-on-surface-variant text-sm leading-relaxed mb-6">{{ $product->expert_tip ?? 'اتبع التعليمات المرفقة بالمنتج للحصول على أفضل النتائج' }}</p>
                            
                            @if(isset($product->expert_name))
                                <div class="flex items-center gap-4 flex-row-reverse mt-6">
                                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary-fixed flex-shrink-0">
                                        <img src="{{ $product->expert_image_url ?? 'https://via.placeholder.com/48' }}" 
                                             alt="{{ $product->expert_name }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm">{{ $product->expert_name }}</p>
                                        <p class="text-[10px] text-on-surface-variant">{{ $product->expert_title ?? '' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- OTHER TABS (Hidden by default) -->
                <div id="tab-usage" class="tab-content hidden">
                    <div class="text-on-surface-variant leading-relaxed prose prose-sm prose-invert">
                        {!! $product->usage_instructions ?? 'يرجى الرجوع إلى شركة المصنع للحصول على معلومات تفصيلية' !!}
                    </div>
                </div>

                <div id="tab-safety" class="tab-content hidden">
                    <div class="text-on-surface-variant leading-relaxed prose prose-sm prose-invert">
                        {!! $product->safety_instructions ?? 'يرجى الرجوع إلى شركة المصنع للحصول على معلومات تفصيلية' !!}
                    </div>
                </div>

                <div id="tab-manufacturer" class="tab-content hidden">
                    <div class="text-on-surface-variant leading-relaxed prose prose-sm prose-invert">
                        {!! $product->manufacturer_info ?? 'يرجى الرجوع إلى شركة المصنع للحصول على معلومات تفصيلية' !!}
                    </div>
                </div>
            </div>

            <!-- RELATED PRODUCTS SECTION -->
            @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                <section class="mt-20">
                    <div class="flex justify-between items-end mb-8 flex-row-reverse">
                        <div>
                            <h2 class="text-3xl font-black text-primary font-headline">منتجات مشابهة</h2>
                            <p class="text-on-surface-variant mt-1">حلول متكاملة لمحاصيلك</p>
                        </div>
                        <a href="#" class="text-primary font-bold flex items-center gap-1 hover:underline flex-row-reverse">
                            عرض الكل
                            <span class="material-symbols-outlined">arrow_left</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $related)
                            <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm hover:shadow-md transition-all group">
                                <!-- Image -->
                                <div class="relative aspect-square overflow-hidden rounded-lg mb-4">
                                    <img src="{{ $related->images->first()?->image_url ?? 'https://via.placeholder.com/300' }}" 
                                         alt="{{ $related->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    
                                    @if($loop->first)
                                        <span class="absolute top-2 right-2 bg-primary text-on-primary text-[10px] font-bold px-2 py-1 rounded-full">
                                            الأكثر مبيعاً
                                        </span>
                                    @endif
                                </div>

                                <!-- Info -->
                                <p class="text-[10px] font-bold text-tertiary">{{ $related->supplier_label ?? 'الموردة' }}</p>
                                <p class="font-bold text-on-surface mt-1 group-hover:text-primary transition-colors line-clamp-2">{{ $related->name }}</p>

                                <!-- Cart Button -->
                                <div class="mt-4 flex justify-between items-center">
                                    <button class="bg-surface-container-high p-2 rounded-full hover:bg-primary-fixed transition-colors">
                                        <span class="material-symbols-outlined text-sm text-on-surface">shopping_cart</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="mt-16 py-12 px-8 bg-[#f4f4ef] border-t border-[#e3e3de]/20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-7xl mx-auto text-right font-body">
            <!-- Column 1: Brand -->
            <div>
                <h3 class="font-bold text-primary font-headline text-lg mb-6">The Fertile Estate</h3>
                <p class="text-[#42493e] leading-relaxed text-sm mb-4">
                    منصتك الموثوقة للعثور على أفضل المنتجات الزراعية من أكبر الموردين في مصر
                </p>
                <div class="flex gap-3 flex-row-reverse">
                    <button class="text-primary hover:text-primary-container transition-colors">
                        <span class="material-symbols-outlined">social_leaderboard</span>
                    </button>
                    <button class="text-primary hover:text-primary-container transition-colors">
                        <span class="material-symbols-outlined">language</span>
                    </button>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div>
                <h3 class="uppercase tracking-wider text-primary font-bold text-sm mb-6">روابط سريعة</h3>
                <ul class="space-y-4">
                    <li><a href="#" class="text-[#42493e] hover:text-[#2D5A27] transition-colors text-sm">سياسة التوصيل</a></li>
                    <li><a href="#" class="text-[#42493e] hover:text-[#2D5A27] transition-colors text-sm">طرق الدفع</a></li>
                    <li><a href="#" class="text-[#42493e] hover:text-[#2D5A27] transition-colors text-sm">تواصل الدعم</a></li>
                    <li><a href="#" class="text-[#42493e] hover:text-[#2D5A27] transition-colors text-sm">من نحن</a></li>
                    <li><a href="#" class="text-primary font-semibold underline underline-offset-4 text-sm">شروط الخدمة</a></li>
                </ul>
            </div>

            <!-- Column 3: Contact -->
            <div>
                <h3 class="uppercase tracking-wider text-primary font-bold text-sm mb-6">تواصل معنا</h3>
                <p class="text-[#42493e] leading-relaxed text-sm mb-3">شارع النيل، القاهرة، مصر</p>
                <p class="text-[#42493e] leading-relaxed text-sm mb-3">+20 2 1234 5678</p>
                <p class="text-[#42493e] leading-relaxed text-sm mb-8">info@nileharvest.com</p>
                <p class="text-xs text-[#42493e] opacity-70">© 2026 The Fertile Estate. جميع الحقوق محفوظة</p>
            </div>
        </div>
    </footer>

    <!-- MOBILE BOTTOM NAV (Hidden on md+) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-surface/90 backdrop-blur-md border-t border-outline-variant/10 px-6 py-4 z-50 flex justify-between items-center">
        <div class="flex flex-col items-center gap-1 flex-1">
            <span class="material-symbols-outlined text-lg text-on-surface">home</span>
            <span class="text-[10px] text-on-surface">الرئيسية</span>
        </div>
        <div class="flex flex-col items-center gap-1 flex-1">
            <span class="material-symbols-outlined text-lg text-primary" style="font-variation-settings: 'FILL' 1">pest_control</span>
            <span class="text-[10px] text-primary font-bold">المبيدات</span>
        </div>
        <div class="flex flex-col items-center gap-1 flex-1">
            <span class="material-symbols-outlined text-lg text-on-surface">shopping_cart</span>
            <span class="text-[10px] text-on-surface">السلة</span>
        </div>
        <div class="flex flex-col items-center gap-1 flex-1">
            <span class="material-symbols-outlined text-lg text-on-surface">account_circle</span>
            <span class="text-[10px] text-on-surface">حسابي</span>
        </div>
    </nav>
</div>

<script>
    // Tab switching functionality
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.tab;
            
            // Remove active from all buttons and contents
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('active', 'text-primary', 'border-b-2', 'border-primary', 'font-bold');
                b.classList.add('text-on-surface-variant', 'font-medium');
            });
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            
            // Add active to clicked button and corresponding content
            btn.classList.remove('text-on-surface-variant', 'font-medium');
            btn.classList.add('active', 'text-primary', 'border-b-2', 'border-primary', 'font-bold');
            document.getElementById(`tab-${tab}`).classList.remove('hidden');
        });
    });
</script>

@endsection

