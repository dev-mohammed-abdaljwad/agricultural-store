<!-- Customer/Farmer Profile Panel Component -->
<div class="w-96 bg-surface-container-low flex flex-col overflow-y-auto p-5 space-y-5 shrink-0 {{ $class ?? '' }}" dir="rtl">
    
    <!-- Profile Card -->
    <div class="bg-surface-container-lowest rounded-2xl p-5">
        <div class="flex items-center gap-4 mb-4">
            <img src="{{ $profileImage ?? 'https://via.placeholder.com/64' }}" alt="Profile" class="w-16 h-16 rounded-full object-cover border-3 border-primary-fixed">
            <div class="flex-1">
                <p class="font-black text-primary text-xl font-headline">{{ $name }}</p>
                <p class="text-sm text-on-surface-variant mt-1 dir-ltr">{{ $phone ?? '' }}</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-3">
            @foreach($stats as $stat)
                <div class="bg-surface-container rounded-xl p-3 text-center">
                    <p class="text-xs text-on-surface-variant mb-1">{{ $stat['label'] }}</p>
                    <p class="font-black text-primary text-2xl">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Orders -->
    @if($showOrders ?? true)
        <div>
            <div class="flex justify-between items-center mb-3">
                <p class="font-bold text-primary text-sm">{{ $ordersTitle ?? 'آخر الطلبات' }}</p>
                @if($showViewAll ?? true)
                    <a href="{{ $viewAllRoute ?? '#' }}" class="text-xs text-primary font-bold hover:underline">عرض الكل</a>
                @endif
            </div>

            <div class="space-y-2 max-h-48 overflow-y-auto">
                @forelse($orders as $order)
                    <div class="bg-surface-container-lowest rounded-xl px-4 py-3 flex items-center justify-between">
                        <div class="flex-1">
                            <p class="font-bold text-sm text-on-surface">#{{ $order->id }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $order->title ?? '' }}</p>
                        </div>
                        <span class="text-[10px] font-bold {{ isset($order->status) && $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }} px-2 py-0.5 rounded-full whitespace-nowrap">
                            {{ $order->status_label ?? 'Unknown' }}
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-on-surface-variant text-center py-4">لا توجد طلبات</p>
                @endforelse
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    @if($showActions ?? true)
        <div class="space-y-3 mt-auto">
            @foreach($actions as $action)
                <a href="{{ $action['route'] ?? '#' }}" 
                   class="w-full {{ $action['primary'] ?? false ? 'bg-primary text-on-primary' : 'bg-surface-container-highest text-on-surface' }} py-4 rounded-xl font-bold font-headline flex items-center justify-center gap-2 hover:{{ $action['primary'] ?? false ? 'bg-primary-container' : 'bg-surface-container' }} transition-all active:scale-95">
                    <span class="material-symbols-outlined">{{ $action['icon'] }}</span>
                    {{ $action['label'] }}
                </a>
            @endforeach
        </div>
    @endif
</div>
