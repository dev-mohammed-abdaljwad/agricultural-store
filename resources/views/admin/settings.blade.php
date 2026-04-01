@extends('layouts.admin')

@section('title', 'الإعدادات - نيل هارفست')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6 pb-20">
    <section>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary mb-2">إعدادات المنصة</h2>
        <p class="text-on-surface-variant text-sm">إدارة إعدادات النظام والحساب</p>
    </section>

    <!-- System Settings -->
    <section class="bg-surface-container-lowest p-6 rounded-lg border border-outline-variant/10">
        <h3 class="text-lg font-black text-primary mb-6">إعدادات النظام</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">اسم المنصة</label>
                <input type="text" value="نيل هارفست" class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">الوصف</label>
                <textarea rows="3" class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20"></textarea>
            </div>
            <button class="px-6 py-3 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-opacity">حفظ الإعدادات</button>
        </div>
    </section>

    <!-- Admin Profile -->
    <section class="bg-surface-container-lowest p-6 rounded-lg border border-outline-variant/10">
        <h3 class="text-lg font-black text-primary mb-6">ملف المسؤول</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">الاسم</label>
                <input type="text" value="{{ $admin->name }}" class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label class="block text-sm font-bold text-on-surface mb-2">البريد الإلكتروني</label>
                <input type="email" value="{{ $admin->email }}" class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20">
            </div>
            <button class="px-6 py-3 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition-opacity">تحديث البيانات</button>
        </div>
    </section>

    <!-- Security -->
    <section class="bg-surface-container-lowest p-6 rounded-lg border border-outline-variant/10">
        <h3 class="text-lg font-black text-primary mb-6">الأمان</h3>
        <div class="space-y-3">
            <button class="w-full px-4 py-3 border border-primary text-primary rounded-lg font-bold hover:bg-primary hover:text-on-primary transition-colors">تغيير كلمة المرور</button>
            <button class="w-full px-4 py-3 border border-primary text-primary rounded-lg font-bold hover:bg-primary hover:text-on-primary transition-colors">المصادقة الثنائية</button>
            <button class="w-full px-4 py-3 border border-error text-error rounded-lg font-bold hover:bg-error hover:text-on-error transition-colors">تسجيل الخروج من جميع الأجهزة</button>
        </div>
    </section>

    <!-- Danger Zone: Logout -->
    <section class="bg-error/5 border-2 border-error p-6 rounded-lg">
        <h3 class="text-lg font-black text-error mb-2">منطقة الخطورة</h3>
        <p class="text-on-surface-variant text-sm mb-4">هذا الإجراء سيؤدي إلى تسجيل خروجك الفوري من النظام</p>
        
        <form method="POST" action="{{ route('logout') }}" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <button type="submit" class="flex-1 px-6 py-3 bg-error text-on-error rounded-lg font-bold hover:opacity-90 transition-opacity active:scale-95 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">logout</span>
                تسجيل الخروج الآن
            </button>
            <p class="text-on-surface-variant text-xs self-center">سيتم إغلاق جلستك الحالية والعودة إلى صفحة تسجيل الدخول</p>
        </form>
    </section>
</main>
@endsection
