@extends('layouts.customer')

@section('title', $product->name . ' - حصاد')

@section('content')
<div class="min-h-screen">
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
                    <div class="relative rounded-xl overflow-hidden shadow-sm bg-surface-container-lowest w-full group">
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
                                        class="rounded-lg overflow-hidden {{ $index === 0 ? 'border-2 border-primary-fixed' : 'border border-outline-variant hover:bg-surface-container-high' }} transition-colors cursor-pointer">
                                    <img src="{{ $image->asset_url }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach

                            @if($product->images->count() > 4)
                                <div class="rounded-lg overflow-hidden border border-outline-variant relative flex items-center justify-center bg-surface-container-low">
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
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-3">
                            @csrf
                            <label class="text-sm font-bold text-on-surface">الكمية</label>
                            <div class="flex items-center gap-4">
                                <div class="flex bg-surface-container-highest rounded-md overflow-hidden flex-row-reverse">
                                    <button type="button" class="p-3 hover:bg-outline-variant transition-colors" onclick="decreaseQty()">
                                        <span class="material-symbols-outlined text-sm">remove</span>
                                    </button>
                                    <input type="number" id="quantity" name="quantity" value="1" 
                                           min="1"
                                           class="w-16 text-center font-bold bg-transparent outline-none">
                                    <button type="button" class="p-3 hover:bg-outline-variant transition-colors" onclick="increaseQty()">
                                        <span class="material-symbols-outlined text-sm">add</span>
                                    </button>
                                </div>
                                <button type="submit" class="flex-1 bg-primary text-on-primary py-4 rounded-md font-black flex items-center justify-center gap-2 hover:bg-primary-container active:scale-95 shadow-md transition-all">
                                    <span class="material-symbols-outlined">shopping_cart</span>
                                    إضافة إلى السلة
                                </button>
                            </div>
                        </form>

                      
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
                    <button data-tab="composition" class="tab-btn text-on-surface-variant font-medium hover:text-primary whitespace-nowrap pb-4 text-sm md:text-base cursor-pointer transition-colors">
                        التركيب والمحاصيل
                    </button>
                    <button data-tab="benefits" class="tab-btn text-on-surface-variant font-medium hover:text-primary whitespace-nowrap pb-4 text-sm md:text-base cursor-pointer transition-colors">
                        الفوائد والاستخدام
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

                                <!-- Display additional product fields -->
                                @if($product->registration_number)
                                    <div class="flex justify-between py-3 border-b border-outline-variant/10">
                                        <span class="text-on-surface-variant">رقم التسجيل</span>
                                        <span class="font-bold text-on-surface">{{ $product->registration_number }}</span>
                                    </div>
                                @endif

                                @if($product->frac_group)
                                    <div class="flex justify-between py-3 border-b border-outline-variant/10">
                                        <span class="text-on-surface-variant">مجموعة FRAC</span>
                                        <span class="font-bold text-on-surface">{{ $product->frac_group }}</span>
                                    </div>
                                @endif

                                @if($product->package_sizes)
                                    <div class="flex justify-between py-3 border-b border-outline-variant/10">
                                        <span class="text-on-surface-variant">أحجام الحزم</span>
                                        <span class="font-bold text-on-surface">{{ $product->package_sizes }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right: Expert Tip Card -->
                        <div class="bg-surface-container-low p-8 rounded-xl border-t-4 border-primary">
                            <h3 class="text-xl font-black text-primary mb-4 font-headline">توصية الخبراء</h3>
                            <p class="text-on-surface-variant text-sm leading-relaxed mb-6">{{ $product->expert_tip ?? 'اتبع التعليمات المرفقة بالمنتج للحصول على أفضل النتائج' }}</p>
                            
                            @if(isset($product->expert_name))
                                <div class="flex items-center gap-4 flex-row-reverse mt-6">
                                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary-fixed flex-shrink-0 flex items-center justify-center bg-primary/10">
                                        @if($product->expert_image_url)
                                            <img src="{{ $product->expert_image_url }}" 
                                                 alt="{{ $product->expert_name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <span class="material-symbols-outlined text-primary">person</span>
                                        @endif
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

                <!-- COMPOSITION & CROPS TAB -->
                <div id="tab-composition" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left: Chemical Composition -->
                        <div class="space-y-6">
                            @if($product->chemical_composition)
                                <div>
                                    <h3 class="text-xl font-black text-primary mb-4 font-headline">التركيب الكيميائي</h3>
                                    <div class="bg-surface-container-low p-6 rounded-xl">
                                        <p class="text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $product->chemical_composition }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($product->how_it_works)
                                <div>
                                    <h3 class="text-xl font-black text-primary mb-4 font-headline">كيفية العمل</h3>
                                    <div class="bg-surface-container-low p-6 rounded-xl">
                                        <p class="text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $product->how_it_works }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Right: Crops & Additional Info -->
                        <div class="space-y-6">
                            @if($product->crops && $product->crops->count() > 0)
                                <div>
                                    <h3 class="text-xl font-black text-primary mb-4 font-headline">المحاصيل المستخدمة</h3>
                                    <div class="grid grid-cols-2 gap-3">
                                        @foreach($product->crops as $crop)
                                            <div class="bg-primary-fixed/20 text-primary px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2">
                                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                                {{ $crop->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($product->extended_description)
                                <div>
                                    <h3 class="text-lg font-black text-primary mb-3 font-headline">معلومات إضافية</h3>
                                    <div class="bg-surface-container-low p-4 rounded-xl">
                                        <p class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $product->extended_description }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- BENEFITS & RECOMMENDATIONS TAB -->
                <div id="tab-benefits" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left: Benefits -->
                        @if($product->benefits)
                            <div>
                                <h3 class="text-xl font-black text-primary mb-4 font-headline">الفوائد الرئيسية</h3>
                                <div class="bg-surface-container-low p-6 rounded-xl">
                                    <div class="space-y-3">
                                        @php
                                            $benefits = explode("\n", $product->benefits);
                                        @endphp
                                        @foreach($benefits as $benefit)
                                            @if(trim($benefit))
                                                <div class="flex gap-3 items-start flex-row-reverse">
                                                    <span class="material-symbols-outlined text-primary text-xl flex-shrink-0">check_circle</span>
                                                    <p class="text-on-surface-variant text-sm leading-relaxed">{{ trim($benefit) }}</p>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Right: Usage Recommendations -->
                        @if($product->usage_recommendations)
                            <div>
                                <h3 class="text-xl font-black text-primary mb-4 font-headline">التوصيات وإرشادات الاستخدام</h3>
                                <div class="bg-surface-container-low p-6 rounded-xl">
                                    <p class="text-on-surface-variant leading-relaxed whitespace-pre-line text-sm">{{ $product->usage_recommendations }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($product->safety_notice)
                        <div class="mt-8 bg-error-fixed/20 border-r-4 border-error p-6 rounded-xl">
                            <h3 class="text-lg font-black text-error mb-3 font-headline flex items-center gap-2 flex-row-reverse">
                                <span class="material-symbols-outlined">warning</span>
                                ملاحظات أمنية مهمة
                            </h3>
                            <p class="text-on-surface-variant leading-relaxed whitespace-pre-line text-sm">{{ $product->safety_notice }}</p>
                        </div>
                    @endif
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
                        <a href="{{ route('products.index') }}" class="text-primary font-bold flex items-center gap-1 hover:underline flex-row-reverse">
                            عرض الكل
                            <span class="material-symbols-outlined">arrow_left</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $related)
                            <a href="{{ route('products.show', $related) }}" class="bg-surface-container-lowest p-4 rounded-xl shadow-sm hover:shadow-md transition-all group">
                                <!-- Image -->
                                <div class="relative overflow-hidden rounded-lg mb-4 bg-surface-container-low flex items-center justify-center">
                                    @if($related->images->first()?->asset_url)
                                        <img src="{{ $related->images->first()->asset_url }}" 
                                             alt="{{ $related->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <span class="material-symbols-outlined text-outline-variant text-5xl">image</span>
                                    @endif
                                    
                                    @if($loop->first)
                                        <span class="absolute top-2 right-2 bg-primary text-on-primary text-[10px] font-bold px-2 py-1 rounded-full">
                                            الأكثر مبيعاً
                                        </span>
                                    @endif
                                </div>

                                <!-- Info -->
                                <p class="text-[10px] font-bold text-tertiary">{{ $related->supplier_label ?? 'الموردة' }}</p>
                                <p class="font-bold text-on-surface mt-1 group-hover:text-primary transition-colors line-clamp-2">{{ $related->name }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

<script>
    function increaseQty() {
        const input = document.getElementById('quantity');
        const minQty = parseInt(input.min) || 1;
        input.value = Math.max(minQty, parseInt(input.value) + 1);
    }

    function decreaseQty() {
        const input = document.getElementById('quantity');
        const minQty = parseInt(input.min) || 1;
        input.value = Math.max(minQty, parseInt(input.value) - 1);
    }

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