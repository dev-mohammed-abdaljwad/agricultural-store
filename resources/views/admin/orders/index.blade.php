@extends('layouts.admin')

@section('title', 'الطلبات - نيل هارفست')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6 pb-20">
    <section>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary mb-2">إدارة الطلبات</h2>
        <p class="text-on-surface-variant text-sm">عرض وإدارة جميع طلبات المنصة</p>
    </section>

    <!-- Orders Table -->
    <section class="bg-surface-container-lowest rounded-lg overflow-hidden border border-outline-variant/10">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-surface-container-low">
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant">رقم الطلب</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant hidden sm:table-cell">العميل</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant">القيمة</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant">الحالة</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/30">
                    @forelse($orders as $order)
                        <tr class="hover:bg-surface-container/50">
                            <td class="px-4 sm:px-6 py-4 font-bold">#{{ $order->order_number }}</td>
                            <td class="px-4 sm:px-6 py-4 hidden sm:table-cell text-sm">{{ $order->customer->name ?? 'N/A' }}</td>
                            <td class="px-4 sm:px-6 py-4 font-bold text-primary">{{ number_format($order->total_amount ?? 0) }} EGP</td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold 
                                    @if($order->status === 'pending') bg-tertiary-fixed text-on-tertiary-fixed
                                    @else bg-surface-container text-on-surface-variant
                                    @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-left">
                                <a href="{{ route('orders.show', $order) }}" class="text-primary hover:underline text-sm">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant">لا توجد طلبات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="flex justify-center">
            {{ $orders->links() }}
        </div>
    @endif
</main>
@endsection
