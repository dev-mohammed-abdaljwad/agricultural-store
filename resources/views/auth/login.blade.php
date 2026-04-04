@extends('layouts.app')

@section('title', 'تسجيل الدخول - حصاد')

@section('content')
<main class="min-h-screen flex flex-col-reverse lg:flex-row-reverse">
    <!-- Left Section: Visual Branding -->
    <x-auth-left-section 
        class="hidden lg:block"
        :features="['آلاف المنتجات الزراعية', 'توصيل لجميع المحافظات', 'دعم فني متخصص']"
    />
    
    <!-- Right Section: Login Form -->
    <section class="w-full lg:w-1/2 flex items-center justify-center bg-surface-container-low px-4 sm:px-6 py-8 sm:py-12">
        <div class="w-full max-w-[480px] bg-surface-container-lowest rounded-xl p-6 sm:p-8 md:p-12 shadow-sm editorial-shadow">
            <!-- Header -->
            <header class="mb-8 sm:mb-10 text-center lg:text-right">
                <h2 class="font-headline text-2xl sm:text-3xl font-extrabold text-primary mb-2">مرحباً بعودتك</h2>
                <p class="text-on-surface-variant text-sm sm:text-base">سجل دخولك للمتابعة</p>
            </header>
            
            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <x-form-input 
                    name="email"
                    type="email"
                    label="البريد الإلكتروني"
                    placeholder="example@email.com"
                    required
                />
                
                <!-- Password Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-on-surface-variant pr-1" for="password">
                        كلمة المرور
                    </label>
                    <div class="relative flex items-center">
                        <input 
                            id="password"
                            type="password"
                            name="password"
                            class="w-full h-14 bg-surface-container-highest border-none rounded-lg px-4 pl-12 text-on-surface placeholder:text-outline focus:ring-0 transition-all @error('password') border-error @enderror"
                            placeholder="••••••••"
                            required
                        />
                        <button 
                            type="button"
                            class="absolute left-4 flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors"
                            onclick="togglePasswordVisibility()"
                        >
                            <span class="material-symbols-outlined" id="visibility-toggle">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input 
                            type="checkbox" 
                            name="remember"
                            class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary/20"
                        />
                        <span class="text-on-surface-variant group-hover:text-on-surface">تذكرني</span>
                    </label>
                    <a class="text-primary font-bold hover:underline" href="#">
                        نسيت كلمة المرور؟
                    </a>
                </div>
                
                <!-- Login Button -->
                <button 
                    type="submit"
                    class="w-full h-12 sm:h-14 bg-primary text-on-primary font-bold text-base sm:text-lg rounded-xl flex items-center justify-center gap-2 hover:opacity-90 active:scale-[0.98] transition-all duration-200"
                >
                    تسجيل الدخول
                </button>
                
                <!-- Divider -->
                <div class="relative py-4 flex items-center">
                    <div class="flex-grow border-t border-outline-variant opacity-30"></div>
                    <span class="flex-shrink mx-4 text-on-surface-variant text-sm">أو</span>
                    <div class="flex-grow border-t border-outline-variant opacity-30"></div>
                </div>
                
                <!-- Social Login -->
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <button 
                        type="button"
                        class="h-11 sm:h-12 flex items-center justify-center gap-2 rounded-lg border border-outline-variant/30 bg-surface hover:bg-surface-container-low transition-colors"
                    >
                        <img alt="Google" class="w-5 h-5" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA_-ouIUMMtoDt5JfXn33FeaDZQoQ2v1wx1hW8hxROXtW3icLOMyEzoXn05HPtkVpUwW1SIyICEjfA-aUaQeWl5PdInixcIzpT-YJgNawx-Xzs2qS1Nuqts57uVWQ8rF4NC0WMbWNVm_p5o_WhdaonUU2IdKEEhgjb3ERBIAB7djtfKXKqWkt_W8hbOz0UenkaRBvsUsp31WW9lZMYYhKrOwiAPzHQhcK08TmLSyIbfCVpBJ1ZLRZbhEb2cwzaEClDGJYXQ30wFt1r7"/>
                        <span class="text-sm font-medium">جوجل</span>
                    </button>
                    <button 
                        type="button"
                        class="h-12 flex items-center justify-center gap-2 rounded-lg border border-outline-variant/30 bg-surface hover:bg-surface-container-low transition-colors"
                    >
                        <img alt="Facebook" class="w-5 h-5" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC7eP1614OA1M7qW42i-N1G7A1CV5oX7jczdgdh1lS0VrNiNYa9QXWEAiQnGxYtBoo1qIyNrwdOsfU2fRW5f4lYeKdiW_gOpd8jXTBrUWzo1f6_f-ahkcr_ZXUEyYWtZaOadDK6Crvznrs1fB4HM4IN_ABHKF5D6H1vQw6-2Va9cLTwB0KxhB6wzpUxW-8YWhTEkXY7r5v6LBzWaefQGGuT_Mhy37E53skG_9OFyq3kIYXe4mfGPPuJYgDDZUo_ejepcjguqJwDLRE6"/>
                        <span class="text-sm font-medium">فيسبوك</span>
                    </button>
                </div>
                
                <!-- Register Link -->
                <div class="text-center pt-4">
                    <p class="text-on-surface-variant text-sm">
                        ليس لديك حساب؟  
                        <a class="text-primary font-extrabold mr-1 hover:underline" href="{{ route('register') }}">
                            سجل الآن
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </section>
</main>

@push('scripts')
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('visibility-toggle');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleBtn.textContent = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            toggleBtn.textContent = 'visibility';
        }
    }
</script>
@endpush
@endsection
