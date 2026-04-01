@extends('layouts.vendor')

@section('title', 'لوحة التحكم - نيل هارفست للتجار')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6 md:space-y-12 pb-20">
    <!-- Welcome Header -->
    <section class="flex flex-col md:flex-row md:items-end justify-between gap-4 md:gap-6">
        <div>
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black font-headline text-primary mb-1 sm:mb-2 tracking-tight">
                أهلاً بك، {{ $vendor->name }}
            </h2>
            <p class="text-on-surface-variant text-xs sm:text-sm md:text-base max-w-2xl">
                نظرة سريعة على أداء حصادك الرقمي. الأرض تجود لمن يزرعها بالذكاء.
            </p>
        </div>
        <div class="flex gap-2 sm:gap-3 flex-shrink-0">
            <button class="px-3 sm:px-6 py-2 sm:py-3 bg-surface-container-low text-on-surface font-bold rounded-lg sm:rounded-xl flex items-center gap-2 hover:bg-surface-container transition-colors text-xs sm:text-base">
                <span class="material-symbols-outlined text-lg sm:text-xl">calendar_today</span>
                <span class="hidden sm:inline">آخر 30 يوم</span>
            </button>
        </div>
    </section>

    <!-- Stats Bento Grid -->
    <section class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
        <!-- Total Sales -->
        <div class="bg-surface-container-lowest p-4 sm:p-5 md:p-6 rounded-lg sm:rounded-2xl flex flex-col justify-between transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/10">
            <div class="flex justify-between items-start gap-2">
                <span class="text-on-surface-variant font-bold text-xs sm:text-sm">إجمالي المبيعات</span>
                <div class="p-1.5 sm:p-2 bg-primary-fixed rounded-lg text-primary flex-shrink-0">
                    <span class="material-symbols-outlined text-lg sm:text-2xl">payments</span>
                </div>
            </div>
            <div>
                <p class="text-lg sm:text-2xl md:text-3xl font-black font-headline text-primary">{{ number_format($totalSales) }} EGP</p>
                <p class="text-xs text-green-600 font-bold mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">trending_up</span>
                    +12% عن الشهر الماضي
                </p>
            </div>
        </div>

        <!-- Active Products -->
        <div class="bg-surface-container-lowest p-4 sm:p-5 md:p-6 rounded-lg sm:rounded-2xl flex flex-col justify-between transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/10">
            <div class="flex justify-between items-start gap-2">
                <span class="text-on-surface-variant font-bold text-xs sm:text-sm">المنتجات النشطة</span>
                <div class="p-1.5 sm:p-2 bg-secondary-container rounded-lg text-secondary flex-shrink-0">
                    <span class="material-symbols-outlined text-lg sm:text-2xl">potted_plant</span>
                </div>
            </div>
            <div>
                <p class="text-lg sm:text-2xl md:text-3xl font-black font-headline text-primary">{{ $activeProducts }}</p>
                <p class="text-xs text-on-surface-variant mt-1">منتجات متوفرة حالياً</p>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-surface-container-lowest p-4 sm:p-5 md:p-6 rounded-lg sm:rounded-2xl flex flex-col justify-between transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/10">
            <div class="flex justify-between items-start gap-2">
                <span class="text-on-surface-variant font-bold text-xs sm:text-sm">طلبات معلقة</span>
                <div class="p-1.5 sm:p-2 bg-tertiary-fixed rounded-lg text-tertiary flex-shrink-0">
                    <span class="material-symbols-outlined text-lg sm:text-2xl">pending_actions</span>
                </div>
            </div>
            <div>
                <p class="text-lg sm:text-2xl md:text-3xl font-black font-headline text-primary">{{ $pendingOrders }}</p>
                <p class="text-xs text-error font-bold mt-1">تحتاج لمراجعة فورية</p>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-surface-container-lowest p-4 sm:p-5 md:p-6 rounded-lg sm:rounded-2xl flex flex-col justify-between transition-transform hover:scale-[1.02] duration-300 border border-outline-variant/10">
            <div class="flex justify-between items-start gap-2">
                <span class="text-on-surface-variant font-bold text-xs sm:text-sm">معدل النجاح</span>
                <div class="p-1.5 sm:p-2 bg-primary-fixed rounded-lg text-primary flex-shrink-0">
                    <span class="material-symbols-outlined text-lg sm:text-2xl">verified</span>
                </div>
            </div>
            <div>
                <p class="text-lg sm:text-2xl md:text-3xl font-black font-headline text-primary">{{ $successRate }}%</p>
                <p class="text-xs text-green-600 font-bold mt-1">أداء متميز وثابت</p>
            </div>
        </div>
    </section>

    <!-- Chart & Actions Section -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 items-start">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl md:rounded-3xl p-4 sm:p-6 md:p-8 min-h-96 md:h-[450px] relative overflow-hidden border border-outline-variant/10">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 md:gap-0 mb-6 md:mb-10">
                <div>
                    <h3 class="text-lg sm:text-xl md:text-2xl font-black font-headline text-primary">أداء المبيعات</h3>
                    <p class="text-xs sm:text-sm text-on-surface-variant">تحليل النمو الأسبوعي للمحاصيل</p>
                </div>
                <div class="flex gap-2 text-xs sm:text-sm">
                    <span class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-primary mt-1.5 sm:mt-2"></span>
                    <span class="text-on-surface-variant">مبيعات فعلية</span>
                </div>
            </div>

            <!-- Chart Bars -->
            <div class="absolute inset-x-4 sm:inset-x-6 md:inset-x-8 bottom-12 h-40 md:h-56 flex items-end gap-2 sm:gap-3 md:gap-4">
                <div class="flex-1 bg-primary/10 rounded-t-lg hover:bg-primary/20 transition-all h-[40%]"></div>
                <div class="flex-1 bg-primary/10 rounded-t-lg hover:bg-primary/20 transition-all h-[65%]"></div>
                <div class="flex-1 bg-primary/10 rounded-t-lg hover:bg-primary/20 transition-all h-[50%]"></div>
                <div class="flex-1 bg-primary/30 rounded-t-lg hover:bg-primary/40 transition-all h-[85%] relative">
                    <div class="absolute -top-6 sm:-top-8 left-1/2 -translate-x-1/2 bg-primary text-on-primary text-[8px] sm:text-[10px] py-1 px-1.5 sm:px-2 rounded-lg whitespace-nowrap">الأعلى</div>
                </div>
                <div class="flex-1 bg-primary/10 rounded-t-lg hover:bg-primary/20 transition-all h-[45%]"></div>
                <div class="flex-1 bg-primary/10 rounded-t-lg hover:bg-primary/20 transition-all h-[70%]"></div>
                <div class="flex-1 bg-primary/10 rounded-t-lg hover:bg-primary/20 transition-all h-[60%]"></div>
            </div>

            <!-- Chart Labels -->
            <div class="absolute bottom-3 inset-x-4 sm:inset-x-6 md:inset-x-8 flex justify-between text-[8px] sm:text-[10px] text-on-surface-variant font-bold border-t border-outline-variant/10 pt-2 sm:pt-3 md:pt-4">
                <span>السبت</span>
                <span>الأحد</span>
                <span>الاثنين</span>
                <span>الثلاثاء</span>
                <span>الأربعاء</span>
                <span>الخميس</span>
                <span>الجمعة</span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Add Product Card -->
            <div class="bg-primary text-on-primary p-6 sm:p-8 rounded-xl md:rounded-3xl relative overflow-hidden h-auto md:h-[215px] flex flex-col justify-between border-none">
                <div class="relative z-10">
                    <h3 class="text-base sm:text-lg md:text-xl font-bold font-headline mb-1 sm:mb-2">توسيع نطاق أعمالك</h3>
                    <p class="text-xs sm:text-sm opacity-80 leading-relaxed">قم بإضافة محاصيل جديدة للموسم القادم واستفد من طلبات الجملة.</p>
                </div>
                <a href="{{ route('vendor.products.create') }}" class="relative z-10 w-fit bg-on-primary text-primary px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg sm:rounded-xl font-bold text-xs sm:text-sm hover:scale-105 transition-transform mt-3 sm:mt-0">
                    إضافة منتج
                </a>
                <div class="absolute -bottom-8 -left-8 opacity-20 hidden sm:block">
                    <span class="material-symbols-outlined text-8xl md:text-[120px]">rocket_launch</span>
                </div>
            </div>

            <!-- Smart Insights -->
            <div class="bg-surface-container-low p-4 sm:p-6 rounded-lg md:rounded-3xl border border-outline-variant/5">
                <h4 class="font-black text-primary mb-3 sm:mb-4 flex items-center gap-2 text-sm md:text-base">
                    <span class="material-symbols-outlined text-lg md:text-2xl">insights</span>
                    رؤى ذكية
                </h4>
                <ul class="space-y-2 sm:space-y-3 md:space-y-4">
                    <li class="flex items-start gap-2 sm:gap-3">
                        <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-tertiary mt-1.5 sm:mt-2 flex-shrink-0"></div>
                        <p class="text-xs sm:text-sm text-on-surface-variant">الطلب على "البصل الأحمر" ارتفع 40% هذا الأسبوع في الدلتا.</p>
                    </li>
                    <li class="flex items-start gap-2 sm:gap-3">
                        <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-primary mt-1.5 sm:mt-2 flex-shrink-0"></div>
                        <p class="text-xs sm:text-sm text-on-surface-variant">تحقق من تقييمات البرتقال الصيفي لتعزيز ثقة المشترين.</p>
                    </li>
                </ul>
                <a href="{{ route('vendor.analytics') }}" class="inline-flex items-center mt-4 sm:mt-6 text-primary font-bold text-xs sm:text-sm gap-1 group hover:underline">
                    مشاهدة التحليلات الكاملة
                    <span class="material-symbols-outlined text-xs sm:text-sm group-hover:mr-1 sm:group-hover:mr-2 transition-all">arrow_back</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Recent Orders Table -->
    <section class="bg-surface-container-lowest rounded-lg md:rounded-3xl overflow-hidden shadow-sm border border-outline-variant/10">
        <div class="p-4 sm:p-6 md:p-8 border-b border-surface-variant lg:flex lg:justify-between lg:items-center gap-4">
            <div>
                <h3 class="text-lg sm:text-xl md:text-2xl font-black font-headline text-primary leading-none mb-1">آخر الطلبات</h3>
                <p class="text-xs sm:text-sm text-on-surface-variant">الطلبات الأخيرة من العملاء</p>
            </div>
            <a href="{{ route('vendor.orders.index') }}" class="text-primary font-bold hover:underline text-xs sm:text-sm mt-3 lg:mt-0 inline-block lg:inline">عرض الكل</a>
        </div>

        <!-- Orders Table - Responsive -->
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-surface-container-low">
                        <th class="px-3 sm:px-6 md:px-8 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant">رقم الطلب</th>
                        <th class="px-3 sm:px-6 md:px-8 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant hidden sm:table-cell">التاريخ</th>
                        <th class="px-3 sm:px-6 md:px-8 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant">المنتج</th>
                        <th class="px-3 sm:px-6 md:px-8 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant">القيمة</th>
                        <th class="px-3 sm:px-6 md:px-8 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant hidden md:table-cell">الحالة</th>
                        <th class="px-3 sm:px-6 md:px-8 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/30">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-surface-container/50 transition-colors">
                            <td class="px-3 sm:px-6 md:px-8 py-4 sm:py-5 font-headline font-bold text-xs sm:text-base">#{{ $order->order_number }}</td>
                            <td class="px-3 sm:px-6 md:px-8 py-4 sm:py-5 text-on-surface-variant text-xs sm:text-sm hidden sm:table-cell">
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                            <td class="px-3 sm:px-6 md:px-8 py-4 sm:py-5">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    @if($order->items->first()?->product->images->first())
                                        <img src="{{ $order->items->first()->product->images->first()->asset_url }}" alt="منتج"
                                             class="w-6 h-6 sm:w-8 sm:h-8 rounded overflow-hidden object-cover flex-shrink-0">
                                    @else
                                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-surface-container rounded flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-sm">image</span>
                                        </div>
                                    @endif
                                    <span class="font-bold text-on-surface text-xs sm:text-base truncate">{{ $order->items->first()?->product->name ?? 'منتج' }}</span>
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 md:px-8 py-4 sm:py-5 font-bold text-primary text-xs sm:text-base">{{ number_format($order->total_amount ?? 0) }} EGP</td>
                            <td class="px-3 sm:px-6 md:px-8 py-4 sm:py-5 hidden md:table-cell">
                                <span class="inline-block px-2 sm:px-3 py-1 rounded-full text-xs font-bold 
                                    @if($order->status === 'pending') bg-tertiary-fixed text-on-tertiary-fixed
                                    @elseif($order->status === 'quote_pending') bg-secondary-fixed text-on-secondary-fixed
                                    @elseif($order->status === 'delivered') bg-primary-fixed text-on-primary-fixed-variant
                                    @else bg-surface-container text-on-surface-variant
                                    @endif
                                ">
                                    @switch($order->status)
                                        @case('pending')
                                            معلق
                                            @break
                                        @case('quote_pending')
                                            في انتظار العرض
                                            @break
                                        @case('delivered')
                                            تم التسليم
                                            @break
                                        @default
                                            {{ $order->status }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 md:px-8 py-4 sm:py-5 text-left">
                                <a href="{{ route('orders.show', $order) }}" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors text-lg sm:text-2xl">info</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-on-surface-variant text-sm">
                                لا توجد طلبات حتى الآن
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</main>
@endsection
