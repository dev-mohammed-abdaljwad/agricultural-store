@extends('layouts.admin')

@section('title', 'إنشاء عرض سعر - حصاد')

@section('content')
<div class="min-h-screen bg-surface p-6 md:p-12">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('admin.quotes.index') }}" class="text-primary hover:text-primary-container">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-4xl font-black text-primary font-headline">إنشاء عرض سعر جديد</h1>
            </div>
            <p class="text-on-surface-variant">الطلب رقم #{{ $order->id }} - العميل: {{ $order->customer->name }}</p>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.quotes.store', $order) }}" method="POST" class="space-y-8">
            @csrf

            <!-- Order Items Section -->
            <div class="bg-white rounded-xl shadow-sm border border-outline-variant/10 p-6">
                <h2 class="text-xl font-bold text-primary mb-6 font-headline">المنتجات</h2>
                
                <div class="space-y-6" id="items-container">
                    @foreach($orderItems as $index => $item)
                        <div class="border border-outline-variant/20 rounded-lg p-4 item-row" data-quantity="{{ $item->quantity }}">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <!-- Product -->
                                <div>
                                    <label class="block text-sm font-bold mb-2">المنتج</label>
                                    <div class="bg-surface-container-low p-3 rounded-lg">
                                        <p class="font-bold text-on-surface">{{ $item->product->name }}</p>
                                        <p class="text-xs text-on-surface-variant">الكمية: {{ $item->quantity }} {{ $item->product->unit }}</p>
                                    </div>
                                    <input type="hidden" name="items[{{ $index }}][order_item_id]" value="{{ $item->id }}">
                                </div>

                                <!-- Unit Price -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold mb-2">السعر للوحدة (ج.م)</label>
                                    <input type="number" 
                                           name="items[{{ $index }}][unit_price]" 
                                           placeholder="0.00"
                                           step="0.01"
                                           min="0"
                                           class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary"
                                           oninput="calculateTotal()">
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="bg-surface-container-low p-3 rounded-lg text-right">
                                <p class="text-sm text-on-surface-variant mb-1">الإجمالي</p>
                                <p class="text-2xl font-black text-primary item-total">0.00 ج.م</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total Amount -->
                <div class="mt-6 bg-primary-fixed/10 border border-primary-fixed rounded-lg p-6 text-center">
                    <p class="text-sm text-on-surface-variant mb-2">إجمالي عرض السعر</p>
                    <p class="text-4xl font-black text-primary" id="grand-total">0.00 ج.م</p>
                </div>
            </div>

            <!-- Quote Details -->
            <div class="bg-white rounded-xl shadow-sm border border-outline-variant/10 p-6">
                <h2 class="text-xl font-bold text-primary mb-6 font-headline">تفاصيل العرض</h2>

                <div class="space-y-4">
                    <!-- Valid Until -->
                    <div>
                        <label class="block text-sm font-bold mb-2">صحة العرض حتى</label>
                        <input type="date" 
                               name="valid_until"
                               value="{{ now()->addDays(7)->format('Y-m-d') }}"
                               required
                               class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary">
                        <p class="text-xs text-on-surface-variant mt-1">عدد الأيام متبقية</p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-bold mb-2">ملاحظات إضافية (اختياري)</label>
                        <textarea name="notes" 
                                  rows="4"
                                  placeholder="مثال: شروط الدفع، طرق التسليم، إلخ"
                                  class="w-full px-4 py-3 rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary resize-none"></textarea>
                    </div>

                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="bg-error/20 text-error border border-error rounded-lg p-4">
                            <ul class="space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('admin.quotes.index') }}" 
                   class="px-8 py-3 bg-surface-container-high text-on-surface rounded-lg font-bold hover:bg-surface-container-highest transition-all text-center">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-primary text-on-primary rounded-lg font-bold hover:bg-primary-container transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">send</span>
                    إنشاء وإرسال العرض
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function calculateTotal() {
        let grandTotal = 0;
        
        document.querySelectorAll('.item-row').forEach((row) => {
            const quantity = parseFloat(row.dataset.quantity) || 0;
            const unitPrice = parseFloat(row.querySelector('input[name*="unit_price"]').value) || 0;
            const itemTotal = quantity * unitPrice;
            
            row.querySelector('.item-total').textContent = itemTotal.toFixed(2) + ' ج.م';
            grandTotal += itemTotal;
        });
        
        document.getElementById('grand-total').textContent = grandTotal.toFixed(2) + ' ج.م';
    }

    // Calculate on page load and whenever input changes
    document.addEventListener('DOMContentLoaded', calculateTotal);
    document.addEventListener('input', calculateTotal);
</script>
@endsection
