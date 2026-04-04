@extends('layouts.customer')

@section('title', 'لوحة التحكم - حصاد')

@section('content')
<!-- Header -->

<!-- Main Dashboard -->
<main class="flex-grow pt-20 sm:pt-24 md:pt-28 pb-16 sm:pb-20 px-4 sm:px-6 md:px-8 max-w-7xl mx-auto w-full">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 sm:mb-12 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-headline font-black text-primary mb-1 sm:mb-2">
                مرحباً بك، {{ Auth::user()->name }}
            </h1>
            <p class="text-on-surface-variant font-body text-sm sm:text-base">
                @if(Auth::user()->customer_type === 'farmer')
                    مزارع محترف
                @else
                    تاجر محاصيل
                @endif
                • {{ Auth::user()->governorate }}
            </p>
        </div>
        
        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="flex">
            @csrf
            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-error text-on-error rounded-lg font-headline font-bold text-sm hover:bg-error/90 transition-colors active:scale-95">
                <span class="material-symbols-outlined text-lg">logout</span>
                تسجيل الخروج
            </button>
        </form>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 md:gap-6 mb-8 sm:mb-12">
        <div class="bg-surface-container-lowest p-4 sm:p-5 md:p-6 rounded-lg md:rounded-xl border border-outline-variant/15 editorial-shadow">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex-1">
                    <p class="text-on-surface-variant text-xs sm:text-sm mb-1 sm:mb-2">إجمالي الطلبات</p>
                    <p class="text-xl sm:text-2xl md:text-3xl font-headline font-black text-primary">{{ $totalOrders }}</p>
                </div>
                <span class="material-symbols-outlined text-primary text-3xl sm:text-4xl opacity-20 flex-shrink-0">shopping_basket</span>
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-4 sm:p-5 md:p-6 rounded-lg md:rounded-xl border border-outline-variant/15 editorial-shadow">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex-1">
                    <p class="text-on-surface-variant text-xs sm:text-sm mb-1 sm:mb-2">الطلبات المعلقة</p>
                    <p class="text-xl sm:text-2xl md:text-3xl font-headline font-black text-tertiary">{{ $pendingOrders }}</p>
                </div>
                <span class="material-symbols-outlined text-tertiary text-3xl sm:text-4xl opacity-20 flex-shrink-0">schedule</span>
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-4 sm:p-5 md:p-6 rounded-lg md:rounded-xl border border-outline-variant/15 editorial-shadow">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex-1">
                    <p class="text-on-surface-variant text-xs sm:text-sm mb-1 sm:mb-2">الطلبات المكتملة</p>
                    <p class="text-xl sm:text-2xl md:text-3xl font-headline font-black text-primary">{{ $completedOrders }}</p>
                </div>
                <span class="material-symbols-outlined text-primary text-3xl sm:text-4xl opacity-20 flex-shrink-0">check_circle</span>
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-4 sm:p-5 md:p-6 rounded-lg md:rounded-xl border border-outline-variant/15 editorial-shadow">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex-1">
                    <p class="text-on-surface-variant text-xs sm:text-sm mb-1 sm:mb-2">الطلبات الملغاة</p>
                    <p class="text-xl sm:text-2xl md:text-3xl font-headline font-black text-error">{{ $cancelledOrders }}</p>
                </div>
                <span class="material-symbols-outlined text-error text-3xl sm:text-4xl opacity-20 flex-shrink-0">cancel</span>
            </div>
        </div>
        
        <div class="bg-surface-container-lowest p-4 sm:p-5 md:p-6 rounded-lg md:rounded-xl border border-outline-variant/15 editorial-shadow">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex-1">
                    <p class="text-on-surface-variant text-xs sm:text-sm mb-1 sm:mb-2">الرسائل الجديدة</p>
                    <p class="text-xl sm:text-2xl md:text-3xl font-headline font-black text-secondary">{{ $unreadMessages }}</p>
                </div>
                <span class="material-symbols-outlined text-secondary text-3xl sm:text-4xl opacity-20 flex-shrink-0">mail</span>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="bg-surface-container-lowest rounded-xl p-6 sm:p-8 editorial-shadow border border-outline-variant/15 mb-8 sm:mb-12">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
            <h2 class="text-xl sm:text-2xl font-headline font-bold text-primary">آخر الطلبات</h2>
            <a href="{{ route('orders.index') }}" class="text-primary font-bold hover:underline flex items-center gap-2 text-sm sm:text-base">
                عرض الكل
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        
        @if($recentOrders->count() > 0)
            <div class="overflow-x-auto -mx-6 sm:-mx-8">
                <table class="w-full text-right text-sm sm:text-base">
                    <thead class="border-b border-outline-variant/30 bg-surface-container-highest/50">
                        <tr>
                            <th class="px-4 sm:px-6 py-2 sm:py-3 font-bold text-on-surface-variant text-xs sm:text-sm">رقم الطلب</th>
                            <th class="px-4 sm:px-6 py-2 sm:py-3 font-bold text-on-surface-variant text-xs sm:text-sm hidden sm:table-cell">التاريخ</th>
                            <th class="px-4 sm:px-6 py-2 sm:py-3 font-bold text-on-surface-variant text-xs sm:text-sm">المبلغ</th>
                            <th class="px-4 sm:px-6 py-2 sm:py-3 font-bold text-on-surface-variant text-xs sm:text-sm">الحالة</th>
                            <th class="px-4 sm:px-6 py-2 sm:py-3 font-bold text-on-surface-variant text-xs sm:text-sm hidden md:table-cell">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr class="border-b border-outline-variant/15 hover:bg-surface-container-low transition-colors">
                                <td class="px-4 sm:px-6 py-2 sm:py-3 font-bold text-primary text-xs sm:text-base">#{{ $order['order_number'] }}</td>
                                <td class="px-4 sm:px-6 py-2 sm:py-3 text-on-surface-variant text-xs sm:text-sm hidden sm:table-cell">
                                    {{ $order['created_at']->format('d M Y') }}
                                </td>
                                <td class="px-4 sm:px-6 py-2 sm:py-3 font-bold text-on-surface text-xs sm:text-base">
                                    {{ $order['total_amount'] > 0 ? number_format($order['total_amount']) . ' جنيه' : '-' }}
                                </td>
                                <td class="px-4 sm:px-6 py-2 sm:py-3">
                                    <span class="inline-block px-2 sm:px-3 py-1 rounded-full text-xs font-bold 
                                        @if($order['status'] === 'delivered') bg-primary/20 text-primary
                                        @elseif($order['status'] === 'quote_sent') bg-tertiary/20 text-tertiary
                                        @elseif($order['status'] === 'pending') bg-secondary/20 text-secondary
                                        @else bg-outline-variant/20 text-on-surface-variant @endif
                                    ">
                                        {{ $order['status_label'] }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-2 sm:py-3 hidden md:table-cell">
                                    <a href="{{ route('orders.show', $order['id']) }}" class="text-primary font-bold hover:underline text-xs sm:text-sm">
                                        عرض التفاصيل
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-on-surface-variant py-8 text-sm sm:text-base">لا توجد طلبات حتى الآن</p>
        @endif
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-8 sm:mb-12">
        <a href="{{ route('products.index') }}" class="bg-primary-fixed p-6 sm:p-8 rounded-xl border border-primary/20 hover:shadow-lg transition-all">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <h3 class="font-headline font-bold text-base sm:text-lg text-primary mb-1 sm:mb-2">تصفح المنتجات</h3>
                    <p class="text-on-surface-variant text-xs sm:text-sm">اكتشف آلاف المنتجات الزراعية المتاحة</p>
                </div>
                <span class="material-symbols-outlined text-primary text-2xl sm:text-3xl opacity-40 flex-shrink-0">shopping_bag</span>
            </div>
        </a>
        
        <a href="{{ route('orders.index') }}" class="bg-secondary-fixed p-6 sm:p-8 rounded-xl border border-secondary/20 hover:shadow-lg transition-all">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <h3 class="font-headline font-bold text-base sm:text-lg text-secondary mb-1 sm:mb-2">محادثة الدعم</h3>
                    <p class="text-on-surface-variant text-xs sm:text-sm">تواصل معنا عبر طلباتك والرسائل</p>
                </div>
                <span class="material-symbols-outlined text-secondary text-2xl sm:text-3xl opacity-40 flex-shrink-0">support_agent</span>
            </div>
        </a>
    </div>
</main>

<!-- Footer -->
<x-footer />
@endsection
