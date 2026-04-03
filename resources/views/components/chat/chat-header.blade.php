<!-- Chat Header Component -->
<div class="px-5 py-4 border-b border-outline-variant/15 bg-surface-container-lowest flex items-center gap-4 shrink-0 {{ $class ?? '' }}">
    <!-- Avatar & Info -->
    <div class="flex items-center gap-4 flex-1">
        <div class="relative">
            <img src="{{ $avatarUrl ?? 'https://via.placeholder.com/48' }}" alt="{{ $name }}" class="w-12 h-12 rounded-full object-cover border-2 border-primary-fixed">
            @if($showOnlineStatus ?? true)
                <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full {{ $isOnline ?? true ? 'bg-green-500' : 'bg-gray-400' }} border-2 border-white"></div>
            @endif
        </div>
        <div>
            <p class="font-black text-primary text-lg font-headline">{{ $name }}</p>
            <p class="text-sm text-on-surface-variant">
                @if($subtitle)
                    {{ $subtitle }}
                @elseif($isOnline ?? true)
                    متصل الآن
                @else
                    آخر ظهور {{ $lastSeen ?? 'منذ وقت' }}
                @endif
            </p>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-3">
        @if($showCallBtn ?? true)
            <button class="p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-all" title="اتصال صوتي">
                <span class="material-symbols-outlined">call</span>
            </button>
        @endif

        @if($showMoreMenu ?? true)
            <button class="p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-all" title="خيارات أخرى">
                <span class="material-symbols-outlined">more_vert</span>
            </button>
        @endif

        <!-- Tabs -->
        @if($showTabs ?? false)
            <div class="flex border border-outline-variant/20 rounded-xl overflow-hidden ml-4">
                @foreach($tabs as $tab)
                    <button class="px-4 py-1.5 text-sm font-bold transition-colors {{ $tab['active'] ?? false ? 'bg-primary text-on-primary' : 'bg-transparent text-on-surface-variant hover:bg-surface-container' }}">
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</div>
