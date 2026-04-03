{{-- Confirmed Quote Items Table Component --}}
@props(['items' => [], 'referenceNumber' => 'RFQ-9921-X', 'totalAmount' => 0])

<section class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm">
    {{-- Table Header --}}
    <div class="p-4 md:p-6 bg-surface-container-high border-b border-outline-variant/15">
        <h3 class="text-base md:text-lg font-bold font-headline mb-2">تفاصيل الأصناف المؤكدة</h3>
        <span class="text-xs md:text-sm text-on-surface-variant font-body">رقم المرجع: {{ $referenceNumber }}</span>
    </div>
    
    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-right border-collapse">
            {{-- Table Head --}}
            <thead>
                <tr class="bg-surface-container-low text-on-surface-variant text-xs md:text-sm">
                    <th class="px-3 md:px-6 py-3 md:py-4 font-bold text-right font-headline">الصنف</th>
                    <th class="px-3 md:px-6 py-3 md:py-4 font-bold font-headline">الكمية</th>
                    <th class="px-3 md:px-6 py-3 md:py-4 font-bold font-headline hidden sm:table-cell">سعر الوحدة</th>
                    <th class="px-3 md:px-6 py-3 md:py-4 font-bold font-headline hidden md:table-cell">تاريخ التوريد</th>
                    <th class="px-3 md:px-6 py-3 md:py-4 font-bold text-left font-headline">الإجمالي</th>
                </tr>
            </thead>
            
            {{-- Table Body --}}
            <tbody class="divide-y divide-outline-variant/10">
                @forelse($items as $item)
                    <tr class="hover:bg-surface-container-lowest transition-colors">
                        {{-- Product Info --}}
                        <td class="px-3 md:px-6 py-4 md:py-6 text-right">
                            <div class="flex items-center gap-2 md:gap-3">
                                {{-- Product Image --}}
                                <div class="w-10 h-10 md:w-12 md:h-12 bg-surface-container rounded overflow-hidden shrink-0 flex items-center justify-center">
                                    @if($item['image'] ?? null)
                                        <img 
                                            alt="{{ $item['name'] ?? 'منتج' }}"
                                            class="w-full h-full object-cover"
                                            src="{{ $item['image'] }}"
                                        />
                                    @else
                                        <span class="material-symbols-outlined text-outline-variant text-lg md:text-xl">image</span>
                                    @endif
                                </div>
                                
                                {{-- Product Details --}}
                                <div class="min-w-0">
                                    <p class="font-bold font-headline text-xs md:text-sm line-clamp-2">{{ $item['name'] ?? 'اسم المنتج' }}</p>
                                    <p class="text-[10px] md:text-xs text-on-surface-variant font-body truncate">
                                        {{ $item['supplier'] ?? 'المورد' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Quantity --}}
                        <td class="px-3 md:px-6 py-4 md:py-6 font-medium font-headline text-xs md:text-sm">{{ $item['quantity'] ?? 0 }}</td>
                        
                        {{-- Unit Price (Hidden on mobile) --}}
                        <td class="px-3 md:px-6 py-4 md:py-6 font-medium font-headline text-xs md:text-sm hidden sm:table-cell">
                            {{ number_format($item['unit_price'] ?? 0) }} ج.م
                        </td>
                        
                        {{-- Delivery Date (Hidden on tablet) --}}
                        <td class="px-3 md:px-6 py-4 md:py-6 text-xs md:text-sm text-on-surface-variant font-body hidden md:table-cell">
                            {{ $item['delivery_date'] ?? 'قريباً' }}
                        </td>
                        
                        {{-- Total --}}
                        <td class="px-3 md:px-6 py-4 md:py-6 font-black text-primary text-left font-headline text-xs md:text-sm">
                            {{ number_format($item['total_price'] ?? 0) }} ج.م
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 md:px-6 py-8 md:py-12 text-center text-on-surface-variant text-sm">
                            لا توجد منتجات في هذا الطلب
                        </td>
                    </tr>
                @endforelse
            </tbody>
            
            {{-- Table Footer --}}
            <tfoot class="bg-primary-fixed/30">
                <tr>
                    <td class="px-3 md:px-6 py-3 md:py-4 font-bold text-right font-headline text-xs md:text-sm" colspan="4">
                        الإجمالي النهائي المستحق
                    </td>
                    <td class="px-3 md:px-6 py-3 md:py-4 font-black text-lg md:text-2xl text-primary text-left font-headline">
                        {{ number_format($totalAmount) }} ج.م
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
