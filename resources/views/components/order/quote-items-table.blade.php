{{-- Quote Items Table Component --}}
@props(['items' => [], 'subtotal' => 0, 'shipping' => 0, 'tax' => 0, 'total' => 0])

<div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/10 overflow-hidden">
    {{-- Table Header --}}
    <div class="p-6 border-b border-outline-variant/20">
        <h3 class="font-headline font-bold text-lg">الأصناف المطلوبة</h3>
    </div>
    
    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-right border-collapse">
            {{-- Head --}}
            <thead>
                <tr class="bg-surface-container-low text-on-surface-variant text-sm font-bold font-headline">
                    <th class="p-4 text-right">المنتج</th>
                    <th class="p-4 text-center">الكمية</th>
                    <th class="p-4">سعر الوحدة</th>
                    <th class="p-4">الإجمالي</th>
                </tr>
            </thead>
            
            {{-- Body --}}
            <tbody class="divide-y divide-outline-variant/10">
                @forelse($items as $item)
                    <tr class="hover:bg-surface-container-low transition-colors">
                        {{-- Product Info --}}
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-surface-container-high rounded-md overflow-hidden shrink-0 flex items-center justify-center">
                                    @if($item['image'] ?? null)
                                        <img 
                                            alt="{{ $item['name'] ?? 'منتج' }}"
                                            class="w-full h-full object-cover"
                                            src="{{ $item['image'] }}"
                                        />
                                    @else
                                        <span class="material-symbols-outlined text-outline-variant">image</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-sm font-headline">{{ $item['name'] ?? 'اسم المنتج' }}</p>
                                    <p class="text-xs text-on-surface-variant font-body">
                                        {{ $item['supplier'] ?? 'المورد' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Quantity --}}
                        <td class="p-4 text-center font-body">{{ $item['quantity'] ?? 0 }}</td>
                        
                        {{-- Unit Price --}}
                        <td class="p-4 font-body text-primary font-bold">
                            {{ number_format($item['unit_price'] ?? 0) }} ج.م
                        </td>
                        
                        {{-- Total --}}
                        <td class="p-4 font-body text-primary font-black">
                            {{ number_format($item['total_price'] ?? 0) }} ج.م
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-on-surface-variant">
                            لا توجد منتجات
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Totals Section --}}
    <div class="p-6 bg-surface-container-low/50 space-y-3">
        <div class="max-w-xs mr-auto space-y-3">
            {{-- Subtotal --}}
            <div class="flex justify-between text-on-surface-variant font-body">
                <span>المجموع الفرعي:</span>
                <span>{{ number_format($subtotal) }} ج.م</span>
            </div>
            
            {{-- Shipping --}}
            <div class="flex justify-between text-on-surface-variant font-body">
                <span>تكلفة التوصيل:</span>
                <span>{{ number_format($shipping) }} ج.م</span>
            </div>
            
            {{-- Tax --}}
            <div class="flex justify-between text-on-surface-variant font-body">
                <span>ضريبة القيمة المضافة (14%):</span>
                <span>{{ number_format($tax) }} ج.م</span>
            </div>
            
            {{-- Grand Total --}}
            <div class="pt-3 border-t border-outline-variant/30 flex justify-between text-xl font-black text-primary font-headline">
                <span>الإجمالي النهائي:</span>
                <span>{{ number_format($total) }} ج.م</span>
            </div>
        </div>
    </div>
</div>
