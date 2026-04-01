@extends('layouts.admin')

@section('title', 'لوحة التحكم - نيل هارفست')

@section('content')
<!-- Welcome Header -->
<section class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 lg:gap-6 mb-8 lg:mb-12">
    <div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-black font-headline text-primary mb-2 tracking-tight">أهلاً بك، {{ Auth::user()->name }}</h2>
        <p class="text-sm lg:text-base text-on-surface-variant max-w-2xl">نظرة سريعة على أداء منصة نيل هارفست اليوم</p>
    </div>
    <div class="flex gap-3">
        <button class="px-4 lg:px-6 py-2 lg:py-3 bg-surface-container-low text-on-surface font-bold text-sm lg:text-base rounded-xl flex items-center gap-2 hover:bg-surface-container transition-colors flex-shrink-0">
            <span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
            <span>آخر 30 يوم</span>
        </button>
    </div>
</section>

<!-- Stats Bento Grid -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8 lg:mb-12">
    <!-- Total Users -->
    <div class="bg-surface-container-lowest p-4 lg:p-6 rounded-2xl flex flex-col justify-between min-h-32 lg:h-40 transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/5">
        <div class="flex justify-between items-start">
            <span class="text-xs lg:text-sm text-on-surface-variant font-bold">إجمالي المستخدمين</span>
            <div class="p-2 bg-primary-fixed rounded-lg text-primary flex-shrink-0">
                <span class="material-symbols-outlined text-lg lg:text-2xl" data-icon="group">group</span>
            </div>
        </div>
        <div>
            <p class="text-2xl lg:text-3xl font-black font-headline text-primary">{{ $totalUsers }}</p>
            <p class="text-xs text-on-surface-variant mt-1 font-bold">عميل وتاجر</p>
        </div>
    </div>

    <!-- Total Vendors -->
    <div class="bg-surface-container-lowest p-4 lg:p-6 rounded-2xl flex flex-col justify-between min-h-32 lg:h-40 transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/5">
        <div class="flex justify-between items-start">
            <span class="text-xs lg:text-sm text-on-surface-variant font-bold">التجار والموردون</span>
            <div class="p-2 bg-secondary-container rounded-lg text-secondary flex-shrink-0">
                <span class="material-symbols-outlined text-lg lg:text-2xl" data-icon="business">business</span>
            </div>
        </div>
        <div>
            <p class="text-2xl lg:text-3xl font-black font-headline text-secondary">{{ $totalVendors }}</p>
            <p class="text-xs text-green-600 font-bold mt-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-xs" data-icon="trending_up">trending_up</span>
                متجرين نشطين
            </p>
        </div>
    </div>

    <!-- Total Products -->
    <div class="bg-surface-container-lowest p-4 lg:p-6 rounded-2xl flex flex-col justify-between min-h-32 lg:h-40 transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/5">
        <div class="flex justify-between items-start">
            <span class="text-xs lg:text-sm text-on-surface-variant font-bold">إجمالي المنتجات</span>
            <div class="p-2 bg-tertiary-fixed rounded-lg text-tertiary flex-shrink-0">
                <span class="material-symbols-outlined text-lg lg:text-2xl" data-icon="shopping_bag">shopping_bag</span>
            </div>
        </div>
        <div>
            <p class="text-2xl lg:text-3xl font-black font-headline text-tertiary" id="totalProductsCount">{{ \App\Models\Product::count() }}</p>
            <p class="text-xs text-on-surface-variant mt-1 font-bold">منتج نشط</p>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-surface-container-lowest p-4 lg:p-6 rounded-2xl flex flex-col justify-between min-h-32 lg:h-40 transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/5">
        <div class="flex justify-between items-start">
            <span class="text-xs lg:text-sm text-on-surface-variant font-bold">إجمالي الطلبات</span>
            <div class="p-2 bg-tertiary-fixed rounded-lg text-tertiary flex-shrink-0">
                <span class="material-symbols-outlined text-lg lg:text-2xl" data-icon="receipt_long">receipt_long</span>
            </div>
        </div>
        <div>
            <p class="text-2xl lg:text-3xl font-black font-headline text-primary">{{ $totalOrders }}</p>
            <p class="text-xs text-tertiary-fixed font-bold mt-1">{{ $pendingOrders }} بحاجة لاهتمام</p>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-surface-container-lowest p-4 lg:p-6 rounded-2xl flex flex-col justify-between min-h-32 lg:h-40 transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/5">
        <div class="flex justify-between items-start">
            <span class="text-xs lg:text-sm text-on-surface-variant font-bold">إجمالي المبيعات</span>
            <div class="p-2 bg-primary-fixed rounded-lg text-primary flex-shrink-0">
                <span class="material-symbols-outlined text-lg lg:text-2xl" data-icon="payments">payments</span>
            </div>
        </div>
        <div>
            <p class="text-lg lg:text-3xl font-black font-headline text-primary">{{ number_format($totalRevenue) }} EGP</p>
            <p class="text-xs text-green-600 font-bold mt-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-xs" data-icon="trending_up">trending_up</span>
                أداء قوي
            </p>
        </div>
    </div>
</section>

<!-- Chart & Actions Section -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start mb-8 lg:mb-12">
    <!-- Sales Chart Mockup -->
    <div class="lg:col-span-2 bg-surface-container-lowest rounded-3xl p-6 lg:p-8 min-h-80 lg:h-[450px] relative overflow-hidden group border border-outline-variant/5">
        <div class="flex flex-col gap-4 lg:gap-10 mb-4 lg:mb-10">
            <div>
                <h3 class="text-xl lg:text-2xl font-black font-headline text-primary">أداء المنصة</h3>
                <p class="text-xs lg:text-sm text-on-surface-variant">إحصائيات الطلبات الأسبوعية</p>
            </div>
            <div class="flex gap-2">
                <span class="w-3 h-3 rounded-full bg-primary"></span>
                <span class="text-xs text-on-surface-variant">طلبات فعلية</span>
            </div>
        </div>
        <!-- Decorative Visual Chart Representation -->
        <div class="absolute inset-x-4 lg:inset-x-8 bottom-12 h-40 lg:h-56 flex items-end gap-2 lg:gap-4">
            <div class="flex-1 bg-primary/10 rounded-t-xl hover:bg-primary/20 transition-all h-[40%]"></div>
            <div class="flex-1 bg-primary/10 rounded-t-xl hover:bg-primary/20 transition-all h-[65%]"></div>
            <div class="flex-1 bg-primary/10 rounded-t-xl hover:bg-primary/20 transition-all h-[50%]"></div>
            <div class="flex-1 bg-primary/30 rounded-t-xl hover:bg-primary/40 transition-all h-[85%] relative">
                <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-primary text-on-primary text-[10px] py-1 px-2 rounded-lg">الأعلى</div>
            </div>
            <div class="flex-1 bg-primary/10 rounded-t-xl hover:bg-primary/20 transition-all h-[45%]"></div>
            <div class="flex-1 bg-primary/10 rounded-t-xl hover:bg-primary/20 transition-all h-[70%]"></div>
            <div class="flex-1 bg-primary/10 rounded-t-xl hover:bg-primary/20 transition-all h-[60%]"></div>
        </div>
        <div class="absolute bottom-2 lg:bottom-4 inset-x-2 lg:inset-x-8 flex justify-between text-[8px] lg:text-[10px] text-on-surface-variant font-bold border-t border-outline-variant/10 pt-2 lg:pt-4">
            <span>السبت</span><span>الأحد</span><span>الاثنين</span><span>الثلاثاء</span><span>الأربعاء</span><span>الخميس</span><span>الجمعة</span>
        </div>
    </div>

    <!-- Quick Actions / Mini Features -->
    <div class="space-y-4 lg:space-y-6">
        <!-- CTA Card -->
        <div class="bg-primary text-on-primary p-4 lg:p-8 rounded-3xl relative overflow-hidden min-h-48 lg:h-[215px] flex flex-col justify-between">
            <div class="relative z-10">
                <h3 class="text-base lg:text-xl font-bold font-headline mb-2">إدارة متقدمة</h3>
                <p class="text-xs lg:text-sm opacity-80 leading-relaxed">راقب نشاط المنصة، أدر المستخدمين والمنتجات بسهولة</p>
            </div>
            <button onclick="openProductModal()" class="relative z-10 w-fit bg-on-primary text-primary px-4 lg:px-6 py-2 lg:py-2.5 rounded-xl font-bold text-xs lg:text-sm hover:scale-105 transition-transform">إضافة منتج جديد</button>
            <div class="absolute -bottom-8 -left-8 opacity-20">
                <span class="material-symbols-outlined text-[120px]" data-icon="admin_panel_settings">admin_panel_settings</span>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-surface-container-low p-4 lg:p-6 rounded-3xl border border-outline-variant/5">
            <h4 class="font-black text-primary mb-3 lg:mb-4 flex items-center gap-2 text-base lg:text-lg">
                <span class="material-symbols-outlined text-lg lg:text-2xl" data-icon="insights">insights</span>
                رؤى ذكية
            </h4>
            <ul class="space-y-3 lg:space-y-4">
                <li class="flex items-start gap-2 lg:gap-3">
                    <div class="w-2 h-2 rounded-full bg-tertiary mt-1 lg:mt-2 flex-shrink-0"></div>
                    <p class="text-xs lg:text-sm text-on-surface-variant">لديك <strong>{{ $pendingOrders }}</strong> طلب بانتظار المراجعة</p>
                </li>
                <li class="flex items-start gap-2 lg:gap-3">
                    <div class="w-2 h-2 rounded-full bg-primary mt-1 lg:mt-2 flex-shrink-0"></div>
                    <p class="text-xs lg:text-sm text-on-surface-variant">معدل إكمال الطلبات: <strong>{{ $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0 }}%</strong></p>
                </li>
                <li class="flex items-start gap-2 lg:gap-3">
                    <div class="w-2 h-2 rounded-full bg-secondary mt-1 lg:mt-2 flex-shrink-0"></div>
                    <p class="text-xs lg:text-sm text-on-surface-variant">انضم <strong>{{ \App\Models\User::where('role', '!=', 'admin')->whereDate('created_at', today())->count() }}</strong> مستخدم جديد اليوم</p>
                </li>
            </ul>
            <a class="inline-flex items-center mt-4 lg:mt-6 text-primary font-bold text-xs lg:text-sm gap-1 group" href="{{ route('admin.analytics') }}">
                عرض التحليلات الكاملة
                <span class="material-symbols-outlined text-xs lg:text-sm group-hover:mr-2 transition-all" data-icon="arrow_back">arrow_back</span>
            </a>
        </div>
    </div>
</section>

<!-- Recent Orders Table -->
<section class="bg-surface-container-lowest rounded-3xl overflow-hidden shadow-sm border border-outline-variant/5 mb-8 lg:mb-12">
    <div class="p-4 lg:p-8 border-b border-surface-variant lg:flex lg:justify-between lg:items-center gap-4">
        <div>
            <h3 class="text-xl lg:text-2xl font-black font-headline text-primary leading-none mb-1">آخر الطلبات</h3>
            <p class="text-xs lg:text-sm text-on-surface-variant">العمليات التي تمت خلال الـ 24 ساعة الماضية</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-primary font-bold hover:underline text-sm mt-3 lg:mt-0 inline-block lg:inline">عرض الكل</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-right border-collapse">
            <thead>
                <tr class="bg-surface-container-low">
                    <th class="px-3 lg:px-8 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant">رقم الطلب</th>
                    <th class="px-3 lg:px-8 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant hidden sm:table-cell">التاريخ</th>
                    <th class="px-3 lg:px-8 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant">العميل</th>
                    <th class="px-3 lg:px-8 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant hidden md:table-cell">القيمة</th>
                    <th class="px-3 lg:px-8 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant">الحالة</th>
                    <th class="px-3 lg:px-8 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-variant/30">
                @forelse($recentOrders as $order)
                    <tr class="hover:bg-surface-container/50 transition-colors">
                        <td class="px-3 lg:px-8 py-3 lg:py-5 font-headline font-bold text-xs lg:text-sm">#{{ $order->order_number ?? $order->id }}</td>
                        <td class="px-3 lg:px-8 py-3 lg:py-5 text-on-surface-variant text-xs lg:text-sm hidden sm:table-cell">
                            {{ $order->created_at?->format('d M') ?? 'N/A' }}
                        </td>
                        <td class="px-3 lg:px-8 py-3 lg:py-5">
                            <div class="flex items-center gap-2 lg:gap-3">
                                <div class="w-6 lg:w-8 h-6 lg:h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary font-bold flex-shrink-0 text-xs lg:text-sm">
                                    {{ substr($order->customer->name ?? 'غ', 0, 1) }}
                                </div>
                                <span class="font-bold text-on-surface text-xs lg:text-sm">{{ $order->customer->name ?? 'غير معروف' }}</span>
                            </div>
                        </td>
                        <td class="px-3 lg:px-8 py-3 lg:py-5 font-bold text-primary text-xs lg:text-sm hidden md:table-cell">{{ number_format($order->total_amount ?? 0) }}</td>
                        <td class="px-3 lg:px-8 py-3 lg:py-5">
                            <span class="inline-block px-2 lg:px-3 py-1 rounded-full text-xs font-bold
                                @if($order->status === 'pending') bg-tertiary-fixed text-tertiary
                                @elseif($order->status === 'quote_pending') bg-secondary-fixed text-secondary
                                @elseif($order->status === 'delivered') bg-primary-fixed text-primary
                                @elseif($order->status === 'processing') bg-secondary-container text-secondary
                                @else bg-surface-container text-on-surface
                                @endif
                            ">
                                @switch($order->status)
                                    @case('pending')
                                        معلق
                                        @break
                                    @case('quote_pending')
                                        عرض
                                        @break
                                    @case('delivered')
                                        مشحون
                                        @break
                                    @case('processing')
                                        معالجة
                                        @break
                                    @default
                                        {{ $order->status }}
                                @endswitch
                            </span>
                        </td>
                        <td class="px-3 lg:px-8 py-3 lg:py-5 text-left">
                            <a href="{{ route('orders.show', $order->id) }}" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors text-4 lg:text-lg" data-icon="more_vert">more_vert</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 lg:px-8 py-6 lg:py-8 text-center text-on-surface-variant text-xs lg:text-sm">
                            لا توجد طلبات حتى الآن
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<!-- Recent Users Section -->
<section class="bg-surface-container-lowest rounded-3xl overflow-hidden shadow-sm border border-outline-variant/5">
    <div class="p-4 lg:p-8 border-b border-surface-variant lg:flex lg:justify-between lg:items-center gap-4">
        <div>
            <h3 class="text-xl lg:text-2xl font-black font-headline text-primary leading-none mb-1">أحدث المستخدمين</h3>
            <p class="text-xs lg:text-sm text-on-surface-variant">أحدث 5 مستخدمين انضموا للمنصة</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-primary font-bold hover:underline text-xs lg:text-sm mt-3 lg:mt-0 inline-block lg:inline">عرض الكل</a>
    </div>

    <!-- Users List -->
    <div class="divide-y divide-surface-variant/30">
        @forelse($recentUsers as $user)
            <div class="p-4 lg:p-8 flex items-center justify-between hover:bg-surface-container/50 transition-colors gap-4">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-8 lg:w-10 h-8 lg:h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold flex-shrink-0 text-sm lg:text-base">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-xs lg:text-sm truncate">{{ $user->name }}</p>
                        <p class="text-xs text-on-surface-variant truncate">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="text-right text-xs lg:text-sm flex-shrink-0">
                    <p class="text-xs lg:text-xs font-bold text-on-surface-variant whitespace-nowrap">
                        @if($user->customer_type === 'trader')
                            تاجر
                        @else
                            عميل
                        @endif
                    </p>
                    <p class="text-xs text-on-surface-variant">{{ $user->created_at?->format('d M') ?? 'N/A' }}</p>
                </div>
            </div>
        @empty
            <div class="p-6 lg:p-8 text-center text-on-surface-variant text-xs lg:text-sm">
                لا يوجد مستخدمون بعد
            </div>
        @endforelse
    </div>
</section>

<x-product-modal />

<script>
    const uploadArea = document.querySelector('.border-dashed');
    
    uploadArea?.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('bg-primary-fixed/20');
    });

    uploadArea?.addEventListener('dragleave', () => {
        uploadArea.classList.remove('bg-primary-fixed/20');
    });

    uploadArea?.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('bg-primary-fixed/20');
        if (e.dataTransfer.files.length) {
            imageInput.files = e.dataTransfer.files;
            previewImage({ target: { files: e.dataTransfer.files } });
        }
    });

    // Form validation before submit
    document.getElementById('productForm')?.addEventListener('submit', function(e) {
        const name = this.querySelector('[name="name"]').value.trim();
        const categoryId = this.querySelector('[name="category_id"]').value.trim();
        
        if (!name || !categoryId) {
            e.preventDefault();
            alert('يرجى ملء الحقول المطلوبة (اسم المنتج والفئة)');
        }
    });
</script>

@endsection

