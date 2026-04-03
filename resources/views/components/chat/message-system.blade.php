<!-- System Message Component -->
<div class="flex justify-center">
    <div class="bg-surface-container rounded-xl px-4 py-3 flex items-center gap-3 max-w-[80%]">
        <span class="material-symbols-outlined text-primary text-xl">{{ $icon ?? 'info' }}</span>
        <div class="text-center">
            <p class="text-xs text-on-surface-variant font-bold">{{ $message }}</p>
            @if($subtext)
                <p class="text-[10px] text-on-surface-variant/60 mt-0.5">{{ $subtext }}</p>
            @endif
        </div>
    </div>
</div>
