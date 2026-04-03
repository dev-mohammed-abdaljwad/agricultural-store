@extends('layouts.admin')

@section('title', 'الطلبات - نيل هارفست')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6 pb-20">
    <!-- Header -->
    <section>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary mb-2">إدارة الطلبات</h2>
        <p class="text-on-surface-variant text-sm">عرض وإدارة جميع طلبات العملاء</p>
    </section>

    <!-- Filter Section -->
    <section class="bg-surface-container-lowest rounded-lg p-4 sm:p-6 border border-outline-variant/15">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-on-surface mb-2">بحث</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="رقم الطلب أو اسم العميل"
                    class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright"
                >
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-on-surface mb-2">الحالة</label>
                <select name="status" class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright">
                    <option value="all">جميع الحالات</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-on-surface mb-2">من تاريخ</label>
                <input 
                    type="date" 
                    name="date_from" 
                    value="{{ request('date_from') }}"
                    class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright"
                >
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-medium text-on-surface mb-2">إلى تاريخ</label>
                <input 
                    type="date" 
                    name="date_to" 
                    value="{{ request('date_to') }}"
                    class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright"
                >
            </div>

            <!-- Search Button -->
            <div class="col-span-1 sm:col-span-2 lg:col-span-4 flex gap-2">
                <button type="submit" class="flex-1 bg-primary text-on-primary px-4 py-2 rounded-lg font-medium hover:bg-primary/90 transition-colors text-sm">
                    بحث
                </button>
                <a href="{{ route('admin.orders.index') }}" class="flex-1 bg-outline text-on-surface px-4 py-2 rounded-lg font-medium hover:bg-outline/90 transition-colors text-sm text-center">
                    إعادة تعيين
                </a>
            </div>
        </form>
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
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant">التاريخ</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/30">
                    @forelse($orders as $order)
                        <tr class="hover:bg-surface-container/50">
                            <td class="px-4 sm:px-6 py-4 font-bold text-primary">
                                <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-4 sm:px-6 py-4 hidden sm:table-cell text-sm">
                                <div>{{ $order->customer->name ?? 'N/A' }}</div>
                                <div class="text-xs text-on-surface-variant">{{ $order->customer->email ?? '' }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 font-bold text-primary">{{ number_format($order->total_amount ?? 0) }} EGP</td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold 
                                    @if($order->status === 'placed') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'quote_sent') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'quote_accepted') bg-green-100 text-green-800
                                    @elseif($order->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($order->status === 'cancelled') bg-gray-100 text-gray-800
                                    @else bg-surface-container text-on-surface-variant
                                    @endif">
                                    {{ $statuses[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-on-surface-variant">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-4 sm:px-6 py-4 text-left">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-primary hover:underline text-sm font-medium">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-on-surface-variant">لا توجد طلبات</td>
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
