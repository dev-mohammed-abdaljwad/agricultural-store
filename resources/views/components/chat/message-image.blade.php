<!-- Image Message Component -->
<div class="flex flex-col items-{{ $senderIsAdmin ? 'start' : 'end' }}">
    <div class="max-w-[60%] rounded-{{ $senderIsAdmin ? 'tr-2xl' : 'tl-2xl' }} rounded-{{ $senderIsAdmin ? 'tl-sm' : 'tr-sm' }} rounded-b-2xl overflow-hidden bg-surface-container-lowest">
        <img src="{{ $imageUrl }}" alt="Message image" class="w-full h-auto object-cover max-h-80 cursor-pointer" onclick="openImageModal(this.src)">
        <div class="px-4 py-2 bg-surface-container">
            <p class="text-xs text-on-surface-variant">{{ $caption ?? '' }}</p>
        </div>
    </div>
    <p class="text-[10px] text-on-surface-variant mt-1">{{ $timestamp->format('h:i A') }}</p>
    @if($senderIsAdmin)
        <!-- Read receipt for admin messages -->
        <span class="material-symbols-outlined text-sm text-primary mt-0.5">done_all</span>
    @endif
</div>
