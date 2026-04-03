{{-- Order Status Timeline Component --}}
@props(['currentStep' => 4])

<section class="mb-12 bg-surface-container-low p-4 md:p-8 rounded-xl">
    <h3 class="text-lg md:text-xl font-bold mb-6 md:mb-8 flex items-center gap-2 font-headline">
        <span class="material-symbols-outlined text-primary text-xl md:text-2xl">analytics</span>
        <span class="hidden sm:inline">مراحل الطلب</span>
    </h3>
    
    {{-- Desktop Timeline (5 items) --}}
    <div class="hidden lg:block">
        <div class="relative flex items-center justify-between">
            {{-- Progress Line Background --}}
            <div class="absolute top-1/2 left-0 w-full h-1 bg-surface-container-highest -translate-y-1/2 z-0"></div>
            
            {{-- Progress Line Active --}}
            <div 
                class="absolute top-1/2 right-0 h-1 bg-primary -translate-y-1/2 z-0 origin-right transition-all duration-1000"
                style="width: {{ (($currentStep - 1) / 4) * 100 }}%"
            ></div>
            
            {{-- Step 1: تقديم الطلب --}}
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center border-4 border-background transition-all">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                </div>
                <span class="text-xs font-bold font-headline text-center whitespace-nowrap">تقديم الطلب</span>
            </div>
            
            {{-- Step 2: مراجعة العروض --}}
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center border-4 border-background transition-all">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                </div>
                <span class="text-xs font-bold font-headline text-center whitespace-nowrap">مراجعة العروض</span>
            </div>
            
            {{-- Step 3: تأكيد القبول --}}
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center border-4 border-background transition-all">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                </div>
                <span class="text-xs font-bold font-headline text-center whitespace-nowrap">تأكيد القبول</span>
            </div>
            
            {{-- Step 4 (Current): تم الدفع والتأكيد --}}
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-full bg-primary text-on-primary flex items-center justify-center border-4 border-primary-fixed shadow-lg shadow-primary/20 scale-110 transition-all">
                    <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                </div>
                <span class="text-xs font-bold text-primary font-headline text-center whitespace-nowrap">تم الدفع والتأكيد</span>
            </div>
            
            {{-- Step 5: جاري الشحن --}}
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center border-4 border-background transition-all">
                    <span class="material-symbols-outlined text-[18px]">local_shipping</span>
                </div>
                <span class="text-xs font-bold text-on-surface-variant opacity-60 font-headline text-center whitespace-nowrap">جاري الشحن</span>
            </div>
        </div>
    </div>
    
    {{-- Mobile/Tablet Timeline (Vertical Stepper) --}}
    <div class="lg:hidden space-y-4">
        <div class="relative pl-8 pb-2">
            <div class="absolute right-0 top-0 w-6 h-6 rounded-full bg-primary text-on-primary flex items-center justify-center border-2 border-background shadow-sm">
                <span class="material-symbols-outlined text-sm">check</span>
            </div>
            <p class="text-xs md:text-sm font-bold text-on-surface-variant">تقديم الطلب</p>
        </div>
        
        <div class="border-r-2 border-primary/30 ml-2 h-4"></div>
        
        <div class="relative pl-8 pb-2">
            <div class="absolute right-0 top-0 w-6 h-6 rounded-full bg-primary text-on-primary flex items-center justify-center border-2 border-background shadow-sm">
                <span class="material-symbols-outlined text-sm">check</span>
            </div>
            <p class="text-xs md:text-sm font-bold text-on-surface-variant">مراجعة العروض</p>
        </div>
        
        <div class="border-r-2 border-primary/30 ml-2 h-4"></div>
        
        <div class="relative pl-8 pb-2">
            <div class="absolute right-0 top-0 w-6 h-6 rounded-full bg-primary text-on-primary flex items-center justify-center border-2 border-background shadow-sm">
                <span class="material-symbols-outlined text-sm">check</span>
            </div>
            <p class="text-xs md:text-sm font-bold text-on-surface-variant">تأكيد القبول</p>
        </div>
        
        <div class="border-r-2 border-primary h-4"></div>
        
        <div class="relative pl-8 pb-2">
            <div class="absolute right-0 top-0 w-7 h-7 rounded-full bg-primary text-on-primary flex items-center justify-center border-2 border-primary-fixed shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">verified_user</span>
            </div>
            <p class="text-xs md:text-sm font-bold text-primary">تم الدفع والتأكيد</p>
        </div>
        
        <div class="border-r-2 border-surface-container-highest/50 ml-2 h-4"></div>
        
        <div class="relative pl-8">
            <div class="absolute right-0 top-0 w-6 h-6 rounded-full bg-surface-container-highest text-on-surface-variant flex items-center justify-center border-2 border-background">
                <span class="material-symbols-outlined text-sm">local_shipping</span>
            </div>
            <p class="text-xs md:text-sm font-bold text-on-surface-variant/60">جاري الشحن</p>
        </div>
    </div>
</section>
