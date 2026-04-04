@extends('layouts.app')

@section('title', 'تسجيل مزارع - حصاد')

@section('content')
<main class="flex-grow pt-20 sm:pt-24 md:pt-28 pb-16 sm:pb-20 px-4 sm:px-6 md:px-8 max-w-7xl mx-auto w-full">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12 items-start">
        <!-- Left Column: Benefits (Editorial Style) -->
        <section class="lg:col-span-5 space-y-8 md:space-y-12 order-2 lg:order-1">
            <div class="relative rounded-2xl overflow-hidden editorial-shadow">
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
        
        <!-- Right Column: Multi-step Form -->
        <section class="lg:col-span-7 bg-surface-container-lowest rounded-xl md:rounded-3xl p-6 sm:p-8 md:p-12 editorial-shadow border border-outline-variant/15 order-1 lg:order-2">
            <header class="mb-8 sm:mb-10">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-headline font-black text-primary mb-1 sm:mb-2">طلب تسجيل مزارع</h1>
                <p class="text-on-surface-variant font-body text-sm sm:text-base">خطوات بسيطة لنبدأ رحلة النمو معاً.</p>
            </header>
            
            <!-- Stepper -->
            <div class="flex items-center gap-2 sm:gap-4 mb-8 sm:mb-12 overflow-x-auto">
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <span class="w-8 sm:w-10 h-8 sm:h-10 rounded-full step-active flex items-center justify-center font-bold font-headline text-xs sm:text-sm" id="step1-badge">١</span>
                    <span class="font-headline font-bold text-primary text-xs sm:text-base whitespace-nowrap" id="step1-label">البيانات الشخصية</span>
                </div>
                <div class="flex-grow h-px bg-outline-variant/30 min-w-4 sm:min-w-8"></div>
                <div class="flex items-center gap-2 sm:gap-3 opacity-40 flex-shrink-0" id="step2-container">
                    <span class="w-8 sm:w-10 h-8 sm:h-10 rounded-full bg-surface-container-highest flex items-center justify-center font-bold font-headline text-xs sm:text-sm text-on-surface-variant" id="step2-badge">٢</span>
                    <span class="font-headline font-bold text-on-surface-variant text-xs sm:text-base whitespace-nowrap" id="step2-label">تفاصيل المزرعة</span>
                </div>
            </div>
            
            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-8" id="registerForm">
                @csrf
                
                <!-- Step 1: Personal Information -->
                <div id="step1-form" class="space-y-6">
                    <x-form-input 
                        name="name"
                        type="text"
                        label="الاسم الكامل"
                        placeholder="مثال: أحمد محمد علي"
                        required
                    />
                    
                    <x-form-input 
                        name="email"
                        type="email"
                        label="البريد الإلكتروني"
                        placeholder="example@email.com"
                        required
                    />
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <x-form-input 
                            name="phone"
                            type="tel"
                            label="رقم الهاتف المحمول"
                            placeholder="01XXXXXXXXX"
                            required
                        />
                        
                        <x-form-input 
                            name="governorate"
                            type="select"
                            label="المحافظة"
                            :options="[
                                '' => 'اختر المحافظة...',
                                'giza' => 'الجيزة',
                                'cairo' => 'القاهرة',
                                'dakahlia' => 'الدقهلية',
                                'monufia' => 'المنوفية',
                                'beheira' => 'البحيرة',
                                'gharbia' => 'الغربية',
                                'kafr-el-sheikh' => 'كفر الشيخ',
                            ]"
                            required
                        />
                    </div>
                    
                    <x-form-input 
                        name="password"
                        type="password"
                        label="كلمة المرور"
                        placeholder="أدخل كلمة مرور قوية"
                        required
                    />
                    
                    <x-form-input 
                        name="password_confirmation"
                        type="password"
                        label="تأكيد كلمة المرور"
                        placeholder="أدخل كلمة المرور مرة أخرى"
                        required
                    />
                </div>
                
                <!-- Step 2: Farm Details (Hidden initially) -->
                <div id="step2-form" class="space-y-6 hidden">
                    <x-form-input 
                        name="address"
                        type="text"
                        label="موقع المزرعة (المركز / القرية)"
                        placeholder="اكتب العنوان بالتفصيل..."
                        required
                    />
                    
                    <x-form-input 
                        name="customer_type"
                        type="select"
                        label="نوع المزارع"
                        :options="[
                            '' => 'اختر الفئة...',
                            'farmer' => 'مزارع محترف',
                            'trader' => 'تاجر محاصيل',
                        ]"
                        required
                    />
                    
                    <div class="flex items-start gap-2 sm:gap-3">
                        <input 
                            type="checkbox" 
                            id="terms"
                            name="terms"
                            class="w-4 h-4 mt-1 rounded border-outline-variant text-primary focus:ring-primary/20 flex-shrink-0"
                            required
                        />
                        <label for="terms" class="text-xs sm:text-sm text-outline font-body leading-relaxed">
                            من خلال النقر على "إنشاء الحساب"، فإنك توافق على 
                            <a class="underline text-primary" href="#">شروط الخدمة</a> و 
                            <a class="underline text-primary" href="#">سياسة الخصوصية</a> الخاصة بحصاد.
                        </label>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="pt-6 sm:pt-8 flex flex-col sm:flex-row-reverse gap-3 sm:gap-4">
                    <button 
                        type="button"
                        id="nextBtn"
                        class="flex-1 bg-primary text-on-primary h-12 sm:h-14 rounded-xl font-headline font-extrabold text-base sm:text-lg hover:bg-primary-container transition-all flex items-center justify-center gap-2 group shadow-lg"
                    >
                        الخطوة التالية
                        <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    </button>
                    
                    <button 
                        type="button"
                        class="px-6 sm:px-8 py-3 sm:py-4 text-on-surface-variant font-headline font-bold text-sm sm:text-base hover:text-primary transition-colors"
                        onclick="window.history.back()"
                    >
                        إلغاء الطلب
                    </button>
                </div>
            </form>
        </section>
    </div>
</main>

<x-footer />

@push('scripts')
<script>
    let currentStep = 1;
    const form = document.getElementById('registerForm');
    const step1Form = document.getElementById('step1-form');
    const step2Form = document.getElementById('step2-form');
    const nextBtn = document.getElementById('nextBtn');
    const step2Container = document.getElementById('step2-container');
    
    nextBtn.addEventListener('click', function() {
        if (currentStep === 1) {
            // Validate step 1
            const step1Inputs = step1Form.querySelectorAll('input[required], select[required]');
            let isValid = true;
            
            step1Inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.focus();
                }
            });
            
            if (!isValid) {
                showError('يرجى ملء جميع الحقول المطلوبة');
                return;
            }
            
            // Move to step 2
            currentStep = 2;
            step1Form.classList.add('hidden');
            step2Form.classList.remove('hidden');
            
            // Update stepper UI
            document.getElementById('step1-badge').classList.remove('step-active');
            document.getElementById('step1-label').classList.remove('text-primary');
            document.getElementById('step1-label').classList.add('text-on-surface-variant');
            
            step2Container.classList.remove('opacity-40');
            document.getElementById('step2-badge').classList.add('step-active');
            document.getElementById('step2-label').classList.add('text-primary');
            document.getElementById('step2-label').classList.remove('text-on-surface-variant');
            
            nextBtn.innerHTML = `
                إنشاء الحساب
                <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">check_circle</span>
            `;
            nextBtn.type = 'submit';
        }
    });
</script>
@endpush
@endsection
