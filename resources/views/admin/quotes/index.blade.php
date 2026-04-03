@extends('layouts.admin')

@section('title', 'إدارة عروض الأسعار - نيل هارفست')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6 pb-20">
    <!-- Header -->
    <section>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary mb-2">إدارة عروض الأسعار</h2>
        <p class="text-on-surface-variant text-sm">إنشاء وإدارة عروض الأسعار للعملاء</p>
    </section>

    <!-- Flash Messages -->
    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg text-sm">
            {{ $message }}
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded-lg text-sm">
            {{ $message }}
        </div>
    @endif

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

            <!-- Source Filter -->
            <div>
                <label class="block text-sm font-medium text-on-surface mb-2">النوع</label>
                <select name="source" class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright">
                    <option value="">جميع</option>
                    <option value="pending_orders" {{ request('source') === 'pending_orders' ? 'selected' : '' }}>
                        طلبات بدون عروض
                    </option>
                </select>
            </div>

            <!-- Search Button -->
            <div class="col-span-1 sm:col-span-2 lg:col-span-4 flex gap-2">
                <button type="submit" class="flex-1 bg-primary text-on-primary px-4 py-2 rounded-lg font-medium hover:bg-primary/90 transition-colors text-sm">
                    بحث
                </button>
                <a href="{{ route('admin.quotes.index') }}" class="flex-1 bg-outline text-on-surface px-4 py-2 rounded-lg font-medium hover:bg-outline/90 transition-colors text-sm text-center">
                    إعادة تعيين
                </a>
            </div>
        </form>
    </section>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-surface-container-lowest rounded-lg p-4 border border-outline-variant/10">
            <p class="text-on-surface-variant text-sm mb-1">عروض مسودة</p>
            <p class="text-2xl font-bold text-primary">{{ $quotes->where('status', 'draft')->count() }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-lg p-4 border border-outline-variant/10">
            <p class="text-on-surface-variant text-sm mb-1">عروض قيد الانتظار</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $quotes->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-lg p-4 border border-outline-variant/10">
            <p class="text-on-surface-variant text-sm mb-1">عروض مقبولة</p>
            <p class="text-2xl font-bold text-green-600">{{ $quotes->where('status', 'accepted')->count() }}</p>
        </div>
        <div class="bg-surface-container-lowest rounded-lg p-4 border border-outline-variant/10">
            <p class="text-on-surface-variant text-sm mb-1">طلبات بلا عروض</p>
            <p class="text-2xl font-bold text-blue-600">{{ $pendingOrders }}</p>
        </div>
    </div>

    <!-- Quotes Table -->
    <section class="bg-surface-container-lowest rounded-lg overflow-hidden border border-outline-variant/10">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-surface-container-low">
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant">رقم الطلب</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant hidden sm:table-cell">العميل</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant">المبلغ</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant">الحالة</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant">صلاحية</th>
                        <th class="px-4 sm:px-6 py-4 text-sm font-bold text-on-surface-variant"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/30">
                    @forelse($quotes as $quote)
                        <tr class="hover:bg-surface-container/50">
                            <td class="px-4 sm:px-6 py-4 font-bold text-primary">
                                <a href="{{ route('admin.quotes.show', $quote) }}" class="hover:underline">
                                    #{{ $quote->order->order_number }}
                                </a>
                            </td>
                            <td class="px-4 sm:px-6 py-4 hidden sm:table-cell text-sm">{{ $quote->order->customer->name ?? 'N/A' }}</td>
                            <td class="px-4 sm:px-6 py-4 font-bold text-primary">{{ number_format($quote->total_amount ?? 0, 2) }} EGP</td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold 
                                    @if($quote->status === 'draft') bg-gray-100 text-gray-800
                                    @elseif($quote->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($quote->status === 'accepted') bg-green-100 text-green-800
                                    @elseif($quote->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-surface-container text-on-surface-variant
                                    @endif">
                                    {{ $statuses[$quote->status] ?? $quote->status }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-sm text-on-surface-variant">
                                {{ $quote->expires_at ? $quote->expires_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-left">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.quotes.show', $quote) }}" class="text-primary hover:underline text-sm font-medium">
                                        عرض
                                    </a>
                                    @if($quote->status === 'draft')
                                        <a href="{{ route('admin.quotes.edit', $quote) }}" class="text-secondary hover:underline text-sm font-medium hidden sm:inline">
                                            تعديل
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-on-surface-variant">لا توجد عروض</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Pagination -->
    @if($quotes->hasPages())
        <div class="flex justify-center">
            {{ $quotes->links() }}
        </div>
    @endif

    <!-- Create Quote for Pending Orders (if any) -->
    @if($pendingOrders > 0)
        <section class="bg-blue-50 border border-blue-200 rounded-lg p-4 sm:p-6">
            <h3 class="text-lg font-bold text-blue-900 mb-2">⚠️ طلبات بدون عروض أسعار</h3>
            <p class="text-blue-800 mb-4">هناك {{ $pendingOrders }} طلب(ات) بدون عروض أسعار. اذهب إلى إدارة الطلبات لإنشاء عروض.</p>
            <a href="{{ route('admin.orders.index', ['status' => 'placed']) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700">
                اذهب إلى الطلبات
            </a>
        </section>
    @endif
</main>
@endsection
