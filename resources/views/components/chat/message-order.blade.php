<!-- Order Card Message Component -->
<div class="flex flex-col items-{{ $senderIsAdmin ? 'start' : 'end' }}">
    <div class="max-w-[70%] rounded-tr-2xl rounded-tl-sm rounded-b-2xl overflow-hidden bg-surface-container-lowest border-2 border-primary-fixed/30">
        <!-- Order Header -->
        <div class="bg-gradient-to-r from-primary-container to-primary-container/80 px-4 py-3 border-b border-primary-fixed/20">
            <div class="flex items-center justify-between">
                <p class="font-bold text-on-surface">طلب رقم {{ $order->order_number }}</p>
                <span class="text-xs font-bold bg-primary text-on-primary px-2 py-1 rounded-full">{{ $order->status_label }}</span>
            </div>
        </div>

        <!-- Order Details -->
        <div class="p-4 space-y-3">
            <!-- Items -->
            <div>
                <p class="text-xs text-on-surface-variant font-bold mb-2">المنتجات:</p>
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-on-surface">{{ $item->product_name }}</span>
                        <span class="text-xs text-on-surface-variant">{{ $item->quantity }} × {{ $item->price }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="pt-2 border-t border-outline-variant/15 flex justify-between items-center">
                <p class="text-sm font-bold text-on-surface">الإجمالي:</p>
                <p class="text-lg font-black text-primary">{{ $order->total }} جنيه</p>
            </div>

            <!-- Action Button -->
            <a href="{{ route('admin.orders.show', $order) }}" class="block mt-3 w-full bg-primary text-on-primary rounded-lg py-2 text-center text-sm font-bold hover:bg-primary-container transition-colors">
                عرض التفاصيل الكاملة
            </a>
        </div>
    </div>
    <p class="text-[10px] text-on-surface-variant mt-1">{{ $timestamp->format('h:i A') }}</p>
</div>
