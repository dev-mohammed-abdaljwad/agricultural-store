@extends('layouts.vendor')

@section('title', 'الإعدادات - نيل هارفست')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6 md:space-y-12 pb-20">
    <section>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary mb-2">الإعدادات</h2>
        <p class="text-on-surface-variant text-sm">إدارة بيانات حسابك ومتجرك</p>
    </section>

    <!-- Settings Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar Navigation -->
        <nav class="lg:col-span-1 space-y-2">
            <a href="#profile" class="block px-4 py-3 bg-primary-fixed text-primary rounded-lg font-bold transition-colors">الملف الشخصي</a>
            <a href="#store" class="block px-4 py-3 text-on-surface-variant hover:bg-surface-container rounded-lg font-bold transition-colors">معلومات المتجر</a>
            <a href="#payment" class="block px-4 py-3 text-on-surface-variant hover:bg-surface-container rounded-lg font-bold transition-colors">طرق الدفع</a>
            <a href="#security" class="block px-4 py-3 text-on-surface-variant hover:bg-surface-container rounded-lg font-bold transition-colors">الأمان</a>
        </nav>

        <!-- Settings Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Section -->
            <section id="profile" class="bg-surface-container-lowest rounded-lg border border-outline-variant/10 p-6">
                <h3 class="text-lg sm:text-xl font-bold text-primary mb-6 font-headline">الملف الشخصي</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-on-surface mb-2">الاسم الكامل</label>
                        <input type="text" value="{{ $vendor->name }}" class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-on-surface mb-2">البريد الإلكتروني</label>
                        <input type="email" value="{{ $vendor->email }}" class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-on-surface mb-2">رقم الهاتف</label>
                        <input type="tel" value="{{ $vendor->phone ?? '' }}" class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    <button class="w-full bg-primary text-on-primary py-3 rounded-lg font-bold hover:opacity-90 transition-opacity">حفظ التغييرات</button>
                </div>
            </section>

            <!-- Store Section -->
            <section id="store" class="bg-surface-container-lowest rounded-lg border border-outline-variant/10 p-6">
                <h3 class="text-lg sm:text-xl font-bold text-primary mb-6 font-headline">معلومات المتجر</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-on-surface mb-2">اسم المتجر</label>
                        <input type="text" placeholder="اسم متجرك" class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-on-surface mb-2">الوصف</label>
                        <textarea rows="4" placeholder="وصف متجرك..." class="w-full px-4 py-2 border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 transition-all"></textarea>
                    </div>
                    <button class="w-full bg-primary text-on-primary py-3 rounded-lg font-bold hover:opacity-90 transition-opacity">حفظ التغييرات</button>
                </div>
            </section>

            <!-- Payment Section -->
            <section id="payment" class="bg-surface-container-lowest rounded-lg border border-outline-variant/10 p-6">
                <h3 class="text-lg sm:text-xl font-bold text-primary mb-6 font-headline">طرق الدفع</h3>
                <p class="text-sm text-on-surface-variant mb-6">إدارة حسابات البنك والمحافظ الإلكترونية</p>
                <button class="w-full bg-primary text-on-primary py-3 rounded-lg font-bold hover:opacity-90 transition-opacity">إضافة طريقة دفع</button>
            </section>

            <!-- Security Section -->
            <section id="security" class="bg-surface-container-lowest rounded-lg border border-outline-variant/10 p-6">
                <h3 class="text-lg sm:text-xl font-bold text-primary mb-6 font-headline">الأمان</h3>
                <div class="space-y-4">
                    <button class="w-full px-4 py-3 border border-primary text-primary rounded-lg font-bold hover:bg-primary hover:text-on-primary transition-colors">تغيير كلمة المرور</button>
                    <button class="w-full px-4 py-3 border border-primary text-primary rounded-lg font-bold hover:bg-primary hover:text-on-primary transition-colors">تفعيل المصادقة الثنائية</button>
                    <button class="w-full px-4 py-3 border border-error text-error rounded-lg font-bold hover:bg-error hover:text-on-error transition-colors">تسجيل الخروج من جميع الأجهزة</button>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
