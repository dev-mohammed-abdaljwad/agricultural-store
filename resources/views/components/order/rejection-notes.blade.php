@props([
    'notes' => '',
])

<div class="bg-surface-container-low p-6 rounded-xl flex flex-col justify-center">
    <h3 class="font-headline font-bold text-lg mb-4">ملاحظات الرفض</h3>
    
    <div class="bg-white p-4 rounded-lg border border-outline-variant/20 italic text-on-surface-variant text-sm relative">
        <span class="material-symbols-outlined absolute -top-3 -right-2 text-primary bg-surface p-1 rounded-full text-lg">
            format_quote
        </span>
        <span class="block pr-4">{{ $notes }}</span>
    </div>

    {{ $slot }}
</div>
