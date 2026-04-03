{{-- Important Notes & Action Buttons Component --}}
@props(['orderNumber' => ''])

<section class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
    {{-- Left: Notes Section --}}
    <div class="bg-tertiary-container/10 p-8 rounded-xl border border-tertiary-container/20">
        <h4 class="text-tertiary font-black mb-4 flex items-center gap-2 font-headline">
            <span class="material-symbols-outlined">info</span>
            ملاحظات هامة
        </h4>
        
        <ul class="space-y-3 text-sm text-on-surface-variant font-body">
            <li class="flex items-start gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-tertiary mt-1.5 shrink-0"></span>
                <span>سيتم إرسال رسالة نصية وبريد إلكتروني عند بدء عملية الشحن.</span>
            </li>
            
            <li class="flex items-start gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-tertiary mt-1.5 shrink-0"></span>
                <span>يرجى الاحتفاظ برقم الطلب {{ $orderNumber }} للمراجعة مع خدمة العملاء.</span>
            </li>
            
            <li class="flex items-start gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-tertiary mt-1.5 shrink-0"></span>
                <span>يمكنك تعديل تفاصيل العنوان خلال الـ 24 ساعة القادمة فقط.</span>
            </li>
        </ul>
    </div>
    
    {{-- Right: Action Buttons --}}
    <div class="flex flex-col gap-4">
        {{-- Track Shipment Button --}}
        <button 
            type="button"
            class="flex items-center justify-between p-5 bg-surface-container-highest hover:bg-surface-container-high transition-all rounded-xl group"
        >
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">location_on</span>
                </div>
                <div class="text-right font-headline">
                    <p class="font-bold">تتبع مسار الشحنة</p>
                    <p class="text-xs text-on-surface-variant font-body">متاح قريباً عند خروج الطلب</p>
                </div>
            </div>
            <span class="material-symbols-outlined opacity-0 group-hover:opacity-100 transition-opacity">chevron_left</span>
        </button>
        
        {{-- Contact Account Manager Button --}}
        <button 
            type="button"
            class="flex items-center justify-between p-5 bg-surface-container-highest hover:bg-surface-container-high transition-all rounded-xl group"
        >
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">support</span>
                </div>
                <div class="text-right font-headline">
                    <p class="font-bold">تواصل مع مدير الحساب</p>
                    <p class="text-xs text-on-surface-variant font-body">لأي استفسارات بخصوص هذا الطلب</p>
                </div>
            </div>
            <span class="material-symbols-outlined opacity-0 group-hover:opacity-100 transition-opacity">chevron_left</span>
        </button>
    </div>
</section>
