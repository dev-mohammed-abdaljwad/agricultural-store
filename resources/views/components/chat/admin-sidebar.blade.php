<!-- Admin Sidebar Component -->
<div class="w-64 bg-primary text-on-primary flex flex-col shrink-0 overflow-hidden {{ $class ?? '' }}">
    
    <!-- Brand -->
    <div class="px-5 py-6 border-b border-white/10">
        <p class="font-black italic text-2xl font-headline">{{ $brandName ?? 'Nile Harvest' }}</p>
        <p class="text-xs text-white/50 mt-1">{{ $subtitle ?? 'Agronomist Portal' }}</p>
    </div>

    <!-- Admin Profile -->
    @if($showProfile ?? true)
        <div class="px-5 py-4 border-b border-white/10 flex items-center gap-3">
            <img src="{{ $adminAvatar ?? 'https://via.placeholder.com/40' }}" alt="Admin" class="w-10 h-10 rounded-full object-cover border-2 border-primary-fixed">
            <div class="flex-1 min-w-0">
                <p class="font-bold text-sm truncate">{{ $adminName ?? 'Dr. Admin' }}</p>
                <p class="text-[10px] text-white/50 truncate">{{ $adminRole ?? 'Managing Delta Region' }}</p>
            </div>
        </div>
    @endif

    <!-- Navigation -->
    <nav class="flex-1 pt-4 space-y-1 overflow-y-auto">
        @php
            $navItems = $navItems ?? [
                ['icon' => 'dashboard', 'label' => 'Overview', 'route' => 'admin.dashboard', 'active' => false],
                ['icon' => 'chat', 'label' => 'Active Chats', 'route' => 'admin.chat.index', 'active' => true],
                ['icon' => 'group', 'label' => 'Farmer Directory', 'route' => 'admin.farmers', 'active' => false],
                ['icon' => 'inventory_2', 'label' => 'Inventory', 'route' => 'admin.inventory', 'active' => false],
                ['icon' => 'settings', 'label' => 'Settings', 'route' => 'admin.settings', 'active' => false],
            ];
        @endphp

        @foreach($navItems as $item)
            <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}" 
               class="flex items-center gap-3 px-5 py-3 text-sm font-headline {{ $item['active'] ?? false ? 'bg-white/10 text-white font-bold border-r-4 border-primary-fixed rounded-l-xl' : 'text-white/60 hover:text-white hover:bg-white/5' }} transition-all">
                <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <!-- Bottom CTA -->
    @if($showBroadcastBtn ?? true)
        <div class="p-5 border-t border-white/10">
            <button class="w-full bg-primary-fixed text-primary py-3 rounded-xl font-bold text-sm font-headline flex items-center justify-center gap-2 hover:bg-primary-fixed/90 transition-all active:scale-95">
                <span class="material-symbols-outlined">{{ $ctaIcon ?? 'campaign' }}</span>
                {{ $ctaText ?? 'New Broadcast' }}
            </button>
        </div>
    @endif
</div>
