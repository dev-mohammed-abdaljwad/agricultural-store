{{-- Order Metadata / Status Badges Component --}}
@props(['orderNumber' => '', 'status' => 'قيد المراجعة', 'statusIcon' => 'pending'])

<div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
    {{-- Order Number Card --}}
    <div class="bg-surface-container-low p-4 sm:p-6 rounded-xl flex flex-col items-center justify-center border-0">
        <span class="text-xs text-on-surface-variant font-label mb-2">رقم الطلب</span>
        <span class="font-headline font-bold text-lg sm:text-xl md:text-2xl text-primary tracking-wider">{{ $orderNumber }}</span>
    </div>
    
    {{-- Status Card --}}
    <div class="bg-surface-container-low p-4 sm:p-6 rounded-xl flex flex-col items-center justify-center border-0">
        <span class="text-xs text-on-surface-variant font-label mb-2">حالة الطلب</span>
        <div class="flex items-center gap-2 bg-tertiary-fixed text-on-tertiary-fixed px-3 sm:px-4 py-1 rounded-full">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">{{ $statusIcon }}</span>
            <span class="font-label font-bold text-xs sm:text-sm">{{ $status }}</span>
        </div>
    </div>
</div>
