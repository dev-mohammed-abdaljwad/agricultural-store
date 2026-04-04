@extends('layouts.customer')

@section('title', 'الرئيسية - حصاد')

@section('content')
<!-- Header -->

<!-- Main Content -->
<main class="flex-grow pt-20 sm:pt-24 md:pt-28 pb-16 sm:pb-20 px-4 sm:px-6 md:px-8 max-w-7xl mx-auto w-full">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-start">
        <!-- Left Column: Benefits -->
        <section class="lg:col-span-5 space-y-8 md:space-y-12 order-2 lg:order-1">
            <div class="relative rounded-2xl overflow-hidden aspect-[4/5] editorial-shadow">
                <img 
                    alt="Egyptian Farmer" 
                    class="absolute inset-0 w-full h-full object-cover" 
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBziq3D1sZGKJx66b167Jr1EkDS6yv_O4I_g93nW4DAnaDkeUp5sg3TVhaMVQCdj9oJmCuJlU2UnNKPUTkIf2cu30zdm5XBThnSTuVh15f49hUIaJ5BTiJk1MK7S5euGtENVsy_E9bMNw6-1vfI8GGtEsMDmzdYsRwPewnIJXyahd3jO8NDAdFlf2voNFECVeXk0krDIIyGUvoA_35Vxt0Is4Ct1U9v0kcRrilhMrczjjsC5RLzzeUH03VKCipsQ-tUbCX1hq396QxM"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent flex flex-col justify-end p-6 sm:p-8 text-on-primary">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-headline font-black mb-2 sm:mb-4 leading-tight">انضم إلى مجتمع مزارعي النيل</h2>
                    <p class="text-sm sm:text-base md:text-lg font-body opacity-90">نحن لا نوفر البذور فقط، بل نزرع مستقبلاً مستداماً معك.</p>
                </div>
            </div>
            
            <div class="space-y-6 md:space-y-8 px-2 sm:px-4">
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="bg-primary-fixed p-2 sm:p-3 rounded-full text-primary flex-shrink-0">
                        <span class="material-symbols-outlined text-xl sm:text-2xl" style="font-variation-settings: 'FILL' 1;">local_offer</span>
                    </div>
                    <div>
                        <h3 class="font-headline font-bold text-lg sm:text-xl text-primary mb-1">خصومات حصرية</h3>
                        <p class="text-on-surface-variant text-sm sm:text-base leading-relaxed">احصل على أسعار تفضيلية للأسمدة والمعدات الزراعية كشريك في شبكتنا.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="bg-primary-fixed p-2 sm:p-3 rounded-full text-primary flex-shrink-0">
                        <span class="material-symbols-outlined text-xl sm:text-2xl" style="font-variation-settings: 'FILL' 1;">support_agent</span>
                    </div>
                    <div>
                        <h3 class="font-headline font-bold text-lg sm:text-xl text-primary mb-1">دعم زراعي مباشر</h3>
                        <p class="text-on-surface-variant text-sm sm:text-base leading-relaxed">استشارات مجانية من خبراء زراعيين لتحسين جودة وإنتاجية أرضك.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="bg-primary-fixed p-2 sm:p-3 rounded-full text-primary flex-shrink-0">
                        <span class="material-symbols-outlined text-xl sm:text-2xl" style="font-variation-settings: 'FILL' 1;">track_changes</span>
                    </div>
                    <div>
                        <h3 class="font-headline font-bold text-lg sm:text-xl text-primary mb-1">تتبع الطلبات</h3>
                        <p class="text-on-surface-variant text-sm sm:text-base leading-relaxed">نظام متطور لمتابعة شحناتك من البذور حتى تصل إلى باب مزرعتك.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Right Column: Call to Action -->
        <section class="lg:col-span-7">
            <div class="bg-surface-container-lowest rounded-xl md:rounded-3xl p-6 sm:p-8 md:p-12 editorial-shadow border border-outline-variant/15">
                <header class="mb-8 sm:mb-10">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-headline font-black text-primary mb-3 sm:mb-4">اكتشف منصة حصاد</h1>
                    <p class="text-sm sm:text-base md:text-lg text-on-surface-variant font-body leading-relaxed mb-6 sm:mb-8">
                        منصة متكاملة توفر للمزارعين المصريين أفضل المنتجات الزراعية والخدمات الاستشارية المتخصصة، مع ضمان أسعار تنافسية وتوصيل سريع إلى جميع أنحاء الجمهورية.
                    </p>
                </header>
                
                <div class="space-y-3 sm:space-y-4 mb-8 sm:mb-10">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <span class="w-7 sm:w-8 h-7 sm:h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs sm:text-sm flex-shrink-0">✓</span>
                        <p class="text-on-surface text-sm sm:text-base">آلاف المنتجات من أفضل الموردين المعتمدين</p>
                    </div>
                    <div class="flex items-center gap-3 sm:gap-4">
                        <span class="w-7 sm:w-8 h-7 sm:h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs sm:text-sm flex-shrink-0">✓</span>
                        <p class="text-on-surface text-sm sm:text-base">أسعار خاصة للمزارعين والتجار</p>
                    </div>
                    <div class="flex items-center gap-3 sm:gap-4">
                        <span class="w-7 sm:w-8 h-7 sm:h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs sm:text-sm flex-shrink-0">✓</span>
                        <p class="text-on-surface text-sm sm:text-base">استشارات مجانية من خبراء زراعيين</p>
                    </div>
                    <div class="flex items-center gap-3 sm:gap-4">
                        <span class="w-7 sm:w-8 h-7 sm:h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs sm:text-sm flex-shrink-0">✓</span>
                        <p class="text-on-surface text-sm sm:text-base">نظام تتبع متقدم للطلبات</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row-reverse gap-3 sm:gap-4 pt-6 sm:pt-8">
                    @auth
                        <a 
                            href="{{ route('dashboard') }}"
                            class="flex-1 bg-primary text-on-primary h-12 sm:h-14 rounded-xl font-headline font-extrabold text-base sm:text-lg hover:opacity-90 transition-all flex items-center justify-center gap-2 shadow-lg"
                        >
                            اذهب إلى لوحة التحكم
                            <span class="material-symbols-outlined text-lg sm:text-xl">arrow_forward</span>
                        </a>
                    @else
                        <a 
                            href="{{ route('register') }}"
                            class="flex-1 bg-primary text-on-primary h-12 sm:h-14 rounded-xl font-headline font-extrabold text-base sm:text-lg hover:opacity-90 transition-all flex items-center justify-center gap-2 shadow-lg"
                        >
                            سجل الآن مجاناً
                            <span class="material-symbols-outlined text-lg sm:text-xl">arrow_forward</span>
                        </a>
                        <a 
                            href="{{ route('login') }}"
                            class="px-6 sm:px-8 h-12 sm:h-14 text-on-primary font-headline font-bold hover:text-primary transition-colors border border-primary rounded-xl text-center flex items-center justify-center text-sm sm:text-base"
                        >
                            تسجيل الدخول
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </div>
</main>

<!-- Footer -->
<x-footer />
@endsection
