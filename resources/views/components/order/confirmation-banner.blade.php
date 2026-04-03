{{-- Confirmation Banner Component --}}
@props(['orderNumber' => '', 'totalAmount' => 0, 'status' => 'confirmed'])

@php
    $statusMessages = [
        'pending' => ['icon' => 'schedule', 'title' => 'تحت المعالجة', 'desc' => 'جاري معالجة طلبك. سيتم التحديث قريباً.'],
        'quote_sent' => ['icon' => 'receipt_long', 'title' => 'تم إرسال العرض', 'desc' => 'تمت معالجة طلبك وإرسال عرض السعر. يرجى مراجعة العرض.'],
        'quote_accepted' => ['icon' => 'check_circle', 'title' => 'تم قبول العرض', 'desc' => 'تم تأكيد عرض السعر. جاري التحضير للشحن.'],
        'confirmed' => ['icon' => 'verified_user', 'title' => 'تم تأكيد طلبك ✓', 'desc' => 'تمت معالجة الدفعة بنجاح. سنقوم بالبدء في التجهيز والشحن.'],
        'in_transit' => ['icon' => 'local_shipping', 'title' => 'جاري الشحن', 'desc' => 'طلبك في الطريق إليك. سيصل قريباً.'],
        'delivered' => ['icon' => 'task_alt', 'title' => 'تم التسليم', 'desc' => 'تم تسليم طلبك بنجاح. شكراً لتعاملك معنا.'],
    ];
    $currentMsg = $statusMessages[$status] ?? $statusMessages['confirmed'];
@endphp

<section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
    {{-- Left: Confirmation Message --}}
    <div class="lg:col-span-2 bg-primary-container text-on-primary-container p-10 rounded-xl relative overflow-hidden flex flex-col justify-center">
        {{-- Subtle pattern overlay --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAfku23GeWtBNsvzLlYeCdA1nvpYnMYWn_Zm_2tsVF34H5xG4vrR4eeYpkDZyba-HNCehvm_5k-sS1M_2USLH6R_TdyUDFEuX87JbF-dNIWceoPf-2jyTKava9JAOXqtZ636jDovhfsUaw_gm-t76uDuvj6WoWKlvE38Hh0W50xp7pFwGWq_Z-vxoUrTmMZeD-vVOlOUN-lQu4l8HBSrO3wTJ8SWL0d0UjghutVhcxhuKncXi1FkPbDjRrSr9luMVZKjXuLwKvEINTz');"></div>
        
        <div class="relative z-10">
            {{-- Status Badge --}}
            <div class="inline-flex items-center gap-2 bg-primary-fixed text-primary px-4 py-2 rounded-full mb-6 font-bold">
                <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">{{ $currentMsg['icon'] }}</span>
                <span>{{ $currentMsg['title'] }}</span>
            </div>
            
            {{-- Title --}}
            <h2 class="text-4xl font-black mb-4 leading-tight font-headline">
                {{ $currentMsg['title'] }}
            </h2>
            
            {{-- Description --}}
            <p class="text-lg opacity-90 max-w-lg font-body">
                {{ $currentMsg['desc'] }}
            </p>
        </div>
    </div>
    
    {{-- Right: Total Amount Card --}}
    <div class="bg-surface-container-lowest p-8 rounded-xl flex flex-col items-center justify-center text-center shadow-sm">
        <div class="w-20 h-20 bg-primary-fixed rounded-full flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-primary text-4xl">payments</span>
        </div>
        
        <p class="text-on-surface-variant text-sm mb-1 font-body">إجمالي المبلغ المؤكد</p>
        <p class="text-3xl font-black text-primary mb-6 font-headline">
            {{ number_format($totalAmount) }} ج.م
        </p>
        
        <button type="button" class="w-full py-3 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2 font-headline">
            <span class="material-symbols-outlined text-sm">download</span>
            تحميل الفاتورة الضريبية
        </button>
    </div>
</section>
