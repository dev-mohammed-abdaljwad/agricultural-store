@extends('layouts.customer')

@section('title', 'طلب #' . $order->order_number . ' - حصاد')

@section('content')
<!-- Top Navigation -->
<nav class="fixed top-0 w-full z-50 bg-[#fafaf5]/80 backdrop-blur-md flex flex-row-reverse justify-between items-center px-4 sm:px-6 md:px-8 h-16 shadow-sm overflow-x-hidden">
    <div class="flex items-center gap-4 sm:gap-6 md:gap-8">
        <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-black text-primary tracking-tight font-headline">
            حصاد
        </a>
        <div class="hidden lg:flex flex-row-reverse items-center gap-4 md:gap-6">
            <a class="text-on-surface-variant hover:text-primary font-headline font-bold text-sm md:text-lg transition-colors duration-200" href="{{ route('home') }}">الرئيسية</a>
            <a class="text-on-surface-variant hover:text-primary font-headline font-bold text-sm md:text-lg transition-colors duration-200" href="{{ route('products.index') }}">السوق</a>
            <a class="text-primary font-headline font-bold text-sm md:text-lg transition-colors duration-200" href="{{ route('dashboard') }}">طلباتي</a>
        </div>
    </div>
    
    <div class="flex items-center gap-2 sm:gap-3 md:gap-4">
        <a href="{{ route('dashboard') }}" class="p-2 text-on-surface-variant hover:text-primary transition-transform active:scale-95">
            <span class="material-symbols-outlined text-xl sm:text-2xl">shopping_cart</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="p-2 text-on-surface-variant hover:text-primary transition-transform active:scale-95">
                <span class="material-symbols-outlined text-xl sm:text-2xl">logout</span>
            </button>
        </form>
    </div>
</nav>

<main class="pt-20 sm:pt-24 pb-12 px-3 sm:px-4 md:px-6 lg:px-8 overflow-x-hidden">
    <div class="max-w-5xl mx-auto w-full">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-8 p-3 sm:p-4 bg-success-container text-on-success-container rounded-lg border-r-4 border-success flex items-center gap-2 sm:gap-3 text-xs sm:text-base" role="alert">
                <span class="material-symbols-outlined text-xl sm:text-2xl flex-shrink-0">check_circle</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-8 p-3 sm:p-4 bg-error/10 text-error rounded-lg border-r-4 border-error flex items-center gap-2 sm:gap-3 text-xs sm:text-base" role="alert">
                <span class="material-symbols-outlined text-xl sm:text-2xl flex-shrink-0">error</span>
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        @php
            // Calculate effective status for display
            $displayStatus = $order->status;
            if ($order->status === 'pending' && $order->quote) {
                $displayStatus = 'quote_pending';
            }
            if (($order->status === 'pending' || $order->status === 'quote_pending') && 
                $order->quote && $order->quote->status === 'accepted') {
                $displayStatus = 'quote_accepted';
            }
        @endphp
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row-reverse sm:justify-between sm:items-start gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="flex-1">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-primary font-headline mb-1 sm:mb-2">الطلب {{ $order->order_number }}</h1>
                <p class="text-on-surface-variant text-xs sm:text-base">
                    تاريخ الطلب: {{ $order->created_at->format('d M Y') }}
                </p>
            </div>
            <span class="px-4 sm:px-6 py-2 sm:py-3 rounded-full font-bold text-xs sm:text-sm md:text-base flex-shrink-0 whitespace-nowrap
                {{ \App\Helpers\OrderStatusHelper::getColorClass($displayStatus) }}"
                data-order-status="{{ $displayStatus }}">
                {{ \App\Helpers\OrderStatusHelper::translate($displayStatus) }}
            </span>
        </div>

        <!-- Order Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 mb-6 sm:mb-8">
            <!-- Left: Order Items & Quote -->
            <div class="lg:col-span-2 space-y-6 sm:space-y-8">
                <!-- Order Items -->
                <section class="bg-surface-container-high rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8 editorial-shadow">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-primary font-headline mb-4 sm:mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg sm:text-2xl">shopping_bag</span>
                        تفاصيل الطلب
                    </h2>
                    
                    <div class="space-y-4 sm:space-y-6">
                        @foreach($order->items as $item)
                            <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-4 pb-4 sm:pb-6 border-b border-outline-variant/15 last:border-b-0">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-base sm:text-lg text-on-surface mb-1 sm:mb-2 truncate">{{ $item->product->name }}</h3>
                                    <p class="text-on-surface-variant text-xs sm:text-sm mb-2 sm:mb-3">
                                        {{ $item->product->category->name }}
                                    </p>
                                    <div class="flex flex-col gap-1 sm:gap-2 text-xs sm:text-sm">
                                        <span class="font-medium text-on-surface">
                                            الكمية: <span class="font-bold text-primary">{{ $item->quantity }}</span>
                                        </span>
                                        @if($item->unit_price)
                                            <span class="text-on-surface-variant">
                                                السعر: <span class="font-bold text-primary">{{ number_format($item->unit_price) }}</span> ج.م
                                            </span>
                                        @else
                                            <span class="text-on-surface-variant">السعر: قيد التحديد</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($item->total_price)
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-on-surface-variant text-xs sm:text-sm mb-1">الإجمالي</p>
                                        <p class="font-bold text-lg sm:text-xl text-primary">{{ number_format($item->total_price) }} ج.م</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Pricing Quote (if exists) -->
                @if($order->quote)
                    <section class="bg-primary-fixed rounded-2xl p-8">
                        <h2 class="text-2xl font-bold text-primary font-headline mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined">request_quote</span>
                            عرض السعر
                        </h2>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center justify-between py-3 border-b border-primary/20 last:border-b-0">
                                <span class="text-on-surface">إجمالي البضاعة:</span>
                                <span class="font-bold text-lg text-primary">
                                    @php
                                        $totalGoods = $order->items->reduce(fn($carry, $item) => $carry + ($item->total_price ?? 0), 0) ?:
                                                      $order->items->reduce(fn($carry, $item) => $carry + ($item->quantity * ($item->unit_price ?? 0)), 0);
                                    @endphp
                                    {{ number_format($totalGoods) }} ج.م
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-primary/20">
                                <span class="text-on-surface flex items-center gap-2">
                                    <span class="material-symbols-outlined text-xl">local_shipping</span>
                                    مصاريف الشحن:
                                </span>
                                <span class="font-bold text-lg text-primary">
                                    @if($order->quote->delivery_fee && $order->quote->delivery_fee > 0)
                                        {{ number_format($order->quote->delivery_fee) }} ج.م
                                    @else
                                        <span class="text-success">مجاني</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-4 bg-on-primary/10 rounded-lg px-4">
                                <span class="font-bold text-lg text-on-surface">الإجمالي:</span>
                                <span class="font-black text-2xl text-primary">{{ number_format($order->quote->total_amount) }} ج.م</span>
                            </div>
                        </div>

                        @if($order->quote->notes)
                            <div class="bg-on-primary/5 rounded-lg p-4 mb-6">
                                <p class="text-sm text-on-surface-variant font-medium mb-2">ملاحظات:</p>
                                <p class="text-on-surface">{{ $order->quote->notes }}</p>
                            </div>
                        @endif

                        @if($order->quote->expires_at)
                            <p class="text-on-surface-variant text-sm">
                                صلاحية العرض: {{ $order->quote->expires_at->format('d M Y H:i') }}
                            </p>
                        @endif

                        <!-- Quote Actions -->
                        @if($order->quote->status === 'pending')
                            <div class="flex flex-col gap-3 mt-8">
                                <form method="POST" action="{{ route('customer.orders.acceptQuote', [$order->id, $order->quote->id]) }}">
                                    @csrf
                                    <button type="submit" class="w-full py-3 bg-success text-on-success font-bold rounded-lg hover:opacity-90 transition-all flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined">check_circle</span>
                                        وافق على العرض
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('customer.orders.rejectQuote', [$order->id, $order->quote->id]) }}">
                                    @csrf
                                    <button type="submit" class="w-full py-3 bg-error text-on-error font-bold rounded-lg hover:opacity-90 transition-all flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined">cancel</span>
                                        رفض العرض
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mt-6 p-4 bg-on-primary/10 rounded-lg text-center">
                                <p class="text-on-surface font-medium">
                                    حالة العرض: 
                                    <span class="font-bold text-primary">
                                        @if($order->quote->status === 'accepted')
                                            تم قبوله
                                        @elseif($order->quote->status === 'rejected')
                                            تم رفضه
                                        @else
                                            {{ $order->quote->status }}
                                        @endif
                                    </span>
                                </p>
                            </div>
                        @endif
                    </section>
                @endif

                <!-- Delivery Information -->
                <section class="bg-surface-container-high rounded-2xl p-8 editorial-shadow">
                    <h2 class="text-2xl font-bold text-primary font-headline mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">location_on</span>
                        معلومات التوصيل
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-on-surface-variant text-sm mb-1">الاسم:</p>
                            <p class="font-bold text-on-surface">{{ $order->delivery_name ?? Auth::user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-sm mb-1">الهاتف:</p>
                            <p class="font-bold text-on-surface">{{ $order->delivery_phone ?? Auth::user()->phone }}</p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-sm mb-1">العنوان:</p>
                            <p class="font-bold text-on-surface">{{ $order->delivery_address ?? Auth::user()->address }}</p>
                        </div>
                        <div>
                            <p class="text-on-surface-variant text-sm mb-1">المحافظة:</p>
                            <p class="font-bold text-on-surface">{{ $order->delivery_governorate ?? Auth::user()->governorate }}</p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right: Timeline & Info -->
            <div class="space-y-8">
                <!-- Status Timeline -->
                <section class="bg-surface-container-high rounded-2xl p-8 editorial-shadow">
                    <h2 class="text-xl font-bold text-primary font-headline mb-6">سير المعاملة</h2>
                    
                    <div class="relative space-y-4">
                        @php
                            $statuses = [
                                'pending',
                                'quote_pending',
                                'quote_accepted',
                                'shipped',
                                'delivered',
                            ];
                            
                            // Determine effective status based on actual business logic
                            $effectiveStatus = $order->status;
                            
                            // If order is pending but has a quote, it's actually quote_pending
                            if ($order->status === 'pending' && $order->quote) {
                                $effectiveStatus = 'quote_pending';
                            }
                            
                            // If order is quote_pending and quote is accepted, it's quote_accepted
                            if (($order->status === 'pending' || $order->status === 'quote_pending') && 
                                $order->quote && $order->quote->status === 'accepted') {
                                $effectiveStatus = 'quote_accepted';
                            }
                            
                            $currentIndex = array_search($effectiveStatus, $statuses);
                        @endphp

                        @foreach($statuses as $idx => $status)
                            <div class="flex gap-4 items-start relative">
                                <!-- Timeline Dot -->
                                <div class="relative flex flex-col items-center">
                                    <div class="w-3 h-3 rounded-full mt-2 {{ $idx <= $currentIndex ? 'bg-primary' : 'bg-outline-variant' }}"></div>
                                    @if($idx < count($statuses) - 1)
                                        <div class="w-0.5 h-12 {{ $idx < $currentIndex ? 'bg-primary' : 'bg-outline-variant' }}"></div>
                                    @endif
                                </div>
                                
                                <!-- Status Text -->
                                <div class="pt-1">
                                    <p class="font-bold {{ $idx <= $currentIndex ? 'text-primary' : 'text-on-surface-variant' }}">
                                        {{ \App\Helpers\OrderStatusHelper::translate($status) }}
                                    </p>
                                    @if($idx <= $currentIndex)
                                        <p class="text-xs text-on-surface-variant mt-1">
                                            @if($status === 'quote_pending' && $order->quote)
                                                تم {{ $order->quote->created_at->format('d/m/Y') }}
                                            @elseif($status === 'quote_accepted' && $order->quote && $order->quote->accepted_at)
                                                تم {{ $order->quote->updated_at->format('d/m/Y') }}
                                            @else
                                                تم {{ $order->created_at->format('d/m/Y') }}
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- Quick Info -->
                <section class="bg-surface-container-high rounded-2xl p-8 editorial-shadow">
                    <h2 class="text-xl font-bold text-primary font-headline mb-6">المعلومات</h2>
                    
                    <div class="space-y-4 text-sm">
                        <div class="flex items-center justify-between py-3 border-b border-outline-variant/15">
                            <span class="text-on-surface-variant">رقم الطلب:</span>
                            <span class="font-mono font-bold text-on-surface">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-outline-variant/15">
                            <span class="text-on-surface-variant">تاريخ الطلب:</span>
                            <span class="font-bold text-on-surface">{{ $order->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-on-surface-variant">رقم التتبع:</span>
                            <span class="font-mono font-bold text-primary">{{ $order->order_number }}</span>
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="mt-6 p-4 bg-on-primary/5 rounded-lg border-r-4 border-primary">
                            <p class="text-xs text-on-surface-variant font-medium mb-2">ملاحظات:</p>
                            <p class="text-sm text-on-surface">{{ $order->notes }}</p>
                        </div>
                    @endif
                </section>

                <!-- Contact Support -->
                <section class="bg-secondary-fixed rounded-2xl p-8">
                    <h2 class="text-lg font-bold text-secondary font-headline mb-4">هل تحتاج مساعدة؟</h2>
                    <p class="text-on-surface text-sm mb-6">
                        يمكنك التواصل مع فريق الدعم الخاص بنا أو مراسلة الإدارة مباشرة من خلال قسم الرسائل.
                    </p>
                    <a href="{{ route('chat.index') }}" class="block text-center py-2 px-4 bg-secondary text-on-secondary font-bold rounded-lg hover:opacity-90 transition-all">
                        اذهب إلى الرسائل
                    </a>
                </section>
            </div>
        </div>

        <!-- Chat Modal -->
        <x-order.chat-modal :order="$order" />
    </div>
</main>

<!-- Footer -->
<x-footer />

<script>
// Auto-refresh order status every 10 seconds
(function() {
    const orderId = {{ $order->id }};
    let lastStatus = '{{ $order->status }}';
    
    async function refreshOrderStatus() {
        try {
            // Get current status from web endpoint
            const response = await fetch(`/orders/${orderId}/status-check`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            
            if (!response.ok) {
                console.warn('Status check error:', response.status);
                return;
            }
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.warn('Status check returned non-JSON response');
                return;
            }
            
            const data = await response.json();
            
            // Update status badge if changed
            if (data.status && data.status !== lastStatus) {
                lastStatus = data.status;
                
                // Show notification if message is available
                if (data.notification_message) {
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-24 right-4 bg-success text-on-success px-6 py-3 rounded-lg shadow-lg z-50';
                    notification.innerHTML = '<div class="flex items-center gap-2"><span class="material-symbols-outlined text-xl">check_circle</span><span>' + data.notification_message + '</span></div>';
                    document.body.appendChild(notification);
                    
                    setTimeout(() => notification.remove(), 4000);
                    console.log('✓ تحديث: ' + data.notification_message);
                }
                
                // Reload page to reflect all changes
                setTimeout(() => location.reload(), 1500);
            }
        } catch (error) {
            console.log('Status check failed:', error.message);
        }
    }
    
    // Check status every 10 seconds
    setInterval(refreshOrderStatus, 10000);
    
    // Also check on page visibility change (when user returns to tab)
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            refreshOrderStatus();
        }
    });
    
    // Listen for real-time order status updates via Pusher
    if (window.pusher && orderId) {
        // Subscribe to customer notifications channel
        const customerNotifications = window.pusher.subscribe('private-customer.notifications.' + {{ auth()->id() }});
        
        // Also subscribe to specific order channel
        const orderChannel = window.pusher.subscribe('private-order.' + orderId);
        
        // Listen for order status updates
        const handleStatusUpdate = function(data) {
            if (data.order_id == orderId && data.message) {
                console.log('✓ تحديث مباشر من الإدارة: ' + data.message);
                lastStatus = data.status;
                
                // Show notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-24 right-4 bg-success text-on-success px-6 py-3 rounded-lg shadow-lg z-50';
                notification.innerHTML = '<div class="flex items-center gap-2"><span class="material-symbols-outlined text-xl">check_circle</span><span>' + data.message + '</span></div>';
                document.body.appendChild(notification);
                
                setTimeout(() => notification.remove(), 4000);
                
                // Reload page to show updated status
                setTimeout(() => location.reload(), 1500);
            }
        };
        
        customerNotifications.bind('order-status-updated', handleStatusUpdate);
        orderChannel.bind('order-status-updated', handleStatusUpdate);
    }
})();
</script>
@endsection
