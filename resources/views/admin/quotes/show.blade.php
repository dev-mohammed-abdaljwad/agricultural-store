@extends('layouts.admin')

@section('title', 'عرض السعر #' . $quote->id . ' - حصاد')

@section('content')
<div class="min-h-screen bg-surface p-6 md:p-12">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('admin.quotes.index') }}" class="text-primary hover:text-primary-container">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-4xl font-black text-primary font-headline">عرض السعر #{{ $quote->id }}</h1>
            </div>
        </div>

        <!-- Flash Messages -->
        @if($message = session('success'))
            <div class="bg-primary-fixed/20 text-on-primary-fixed border border-primary-fixed rounded-lg p-4 mb-6 flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <p>{{ $message }}</p>
            </div>
        @endif

        <!-- Quote Header Info -->
        <div class="bg-white rounded-xl shadow-sm border border-outline-variant/10 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Order Info -->
                <div>
                    <p class="text-sm text-on-surface-variant mb-1 font-medium">الطلب</p>
                    <p class="text-2xl font-bold text-primary">#{{ $quote->order->order_number }}</p>
                    <p class="text-sm text-on-surface-variant mt-2">{{ $quote->order->customer->name }}</p>
                </div>

                <!-- Status -->
                <div>
                    <p class="text-sm text-on-surface-variant mb-1 font-medium">الحالة</p>
                    <div class="flex items-center gap-2">
                        @if($quote->status === 'pending')
                            <span class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-bold">
                                <span class="w-2 h-2 bg-yellow-600 rounded-full"></span>
                                قيد الانتظار
                            </span>
                        @elseif($quote->status === 'accepted')
                            <span class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-bold">
                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                                مقبول
                            </span>
                        @elseif($quote->status === 'rejected')
                            <span class="inline-flex items-center gap-2 bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-bold">
                                <span class="w-2 h-2 bg-red-600 rounded-full"></span>
                                مرفوض
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Total Amount -->
                <div>
                    <p class="text-sm text-on-surface-variant mb-1 font-medium">الإجمالي</p>
                    <p class="text-3xl font-black text-primary">{{ number_format($quote->total_amount, 2) }} ج.م</p>
                </div>
            </div>

            <div class="border-t border-outline-variant/10 mt-6 pt-6 text-sm text-on-surface-variant">
                <p>صحة العرض حتى: <span class="font-bold text-on-surface">{{ $quote->expires_at->format('Y-m-d') }}</span></p>
                <p>تم الإنشاء في: <span class="font-bold text-on-surface">{{ $quote->created_at->format('Y-m-d H:i') }}</span></p>
            </div>
        </div>

        <!-- Quote Items -->
        <div class="bg-white rounded-xl shadow-sm border border-outline-variant/10 p-6 mb-6">
            <h2 class="text-xl font-bold text-primary mb-6 font-headline">البنود</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-outline-variant/20">
                            <th class="text-right py-3 px-4 font-bold text-on-surface">المنتج</th>
                            <th class="text-center py-3 px-4 font-bold text-on-surface">الكمية</th>
                            <th class="text-center py-3 px-4 font-bold text-on-surface">السعر/الوحدة</th>
                            <th class="text-right py-3 px-4 font-bold text-on-surface">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($quote->items as $item)
                            <tr class="border-b border-outline-variant/10 hover:bg-surface-container-low">
                                <td class="py-4 px-4 text-on-surface font-medium">{{ $item->orderItem->product->name }}</td>
                                <td class="py-4 px-4 text-center text-on-surface">{{ $item->orderItem->quantity }} {{ $item->orderItem->product->unit }}</td>
                                <td class="py-4 px-4 text-center text-on-surface">{{ number_format($item->unit_price, 2) }} ج.م</td>
                                <td class="py-4 px-4 text-right text-on-surface font-bold">{{ number_format($item->total_price, 2) }} ج.م</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="mt-6 flex justify-end">
                <div class="text-right">
                    <div class="flex justify-between gap-8 mb-2">
                        <span class="text-on-surface-variant">الإجمالي الفرعي:</span>
                        <span class="font-bold">{{ number_format($quote->total_amount, 2) }} ج.م</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($quote->notes)
            <div class="bg-white rounded-xl shadow-sm border border-outline-variant/10 p-6 mb-6">
                <h3 class="text-lg font-bold text-primary mb-4 font-headline">ملاحظات</h3>
                <p class="text-on-surface whitespace-pre-wrap">{{ $quote->notes }}</p>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3 justify-end">
            @if($quote->status === 'pending')
                <a href="{{ route('admin.quotes.edit', $quote) }}" 
                   class="px-6 py-3 bg-surface-container-high text-on-surface rounded-lg font-bold hover:bg-surface-container-highest transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">edit</span>
                    تعديل
                </a>

                <form action="{{ route('admin.quotes.destroy', $quote) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-3 bg-error/10 text-error rounded-lg font-bold hover:bg-error/20 transition-all flex items-center justify-center gap-2 w-full" onclick="confirm('هل أنت متأكد من حذف هذا العرض؟') || event.preventDefault()">
                        <span class="material-symbols-outlined">delete</span>
                        حذف
                    </button>
                </form>

                <form action="{{ route('admin.quotes.send', $quote) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-primary text-on-primary rounded-lg font-bold hover:bg-primary-container transition-all flex items-center justify-center gap-2 w-full">
                        <span class="material-symbols-outlined">send</span>
                        إرسال للعميل
                    </button>
                </form>
            @else
                <p class="text-on-surface-variant italic py-3">تم الرد على هذا العرض بالفعل</p>
            @endif

            <a href="{{ route('admin.quotes.index') }}" 
               class="px-6 py-3 bg-surface-container-high text-on-surface rounded-lg font-bold hover:bg-surface-container-highest transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">arrow_back</span>
                العودة
            </a>
        </div>
    </div>
</div>
@endsection
