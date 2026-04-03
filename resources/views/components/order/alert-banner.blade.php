{{-- Alert Banner Component --}}
@props(['message' => 'وصلك عرض سعر جديد'])

<div class="bg-blue-50 border-r-4 border-blue-600 p-4 flex items-center gap-3 rounded-lg shadow-sm">
    <span class="material-symbols-outlined text-blue-600" style="font-variation-settings: 'FILL' 1;">info</span>
    <p class="text-blue-900 font-bold text-sm font-headline">{{ $message }}</p>
</div>
