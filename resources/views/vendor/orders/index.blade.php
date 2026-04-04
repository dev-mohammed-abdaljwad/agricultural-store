@extends('layouts.vendor')

@section('title', 'إدارة الطلبات - حصاد')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6 md:space-y-8 pb-20">
    <section>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary mb-2">الطلبات</h2>
        <p class="text-on-surface-variant text-sm">عرض وإدارة جميع الطلبات</p>
    </section>

    <!-- Orders Table -->
    <div class="bg-surface-container-lowest rounded-lg overflow-hidden border border-outline-variant/10 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-surface-container-low">
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant">رقم الطلب</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant hidden sm:table-cell">العميل</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant hidden md:table-cell">التاريخ</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant">الإجمالي</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant">الحالة</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm font-bold text-on-surface-variant"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/30">
                    @forelse($orders as $order)
                        <tr class="hover:bg-surface-container/50 transition-colors">
                            <td class="px-4 sm:px-6 py-3 sm:py-5 font-headline font-bold text-xs sm:text-base">#{{ $order->order_number }}</td>
                            <td class="px-4 sm:px-6 py-3 sm:py-5 text-xs sm:text-sm hidden sm:table-cell">{{ $order->user->name ?? '-' }}</td>
                            <td class="px-4 sm:px-6 py-3 sm:py-5 text-xs sm:text-sm text-on-surface-variant hidden md:table-cell">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-4 sm:px-6 py-3 sm:py-5 font-bold text-primary text-xs sm:text-base">{{ number_format($order->total_amount ?? 0) }} EGP</td>
                            <td class="px-4 sm:px-6 py-3 sm:py-5">
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
                            <td class="px-4 sm:px-6 py-3 sm:py-5 text-left">
                                <a href="{{ route('orders.show', $order) }}" class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors text-lg">info</a>
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
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="flex justify-center">
            {{ $orders->links() }}
        </div>
    @endif
</main>
@endsection
