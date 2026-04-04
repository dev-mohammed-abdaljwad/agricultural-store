@extends('layouts.admin')

@section('title', 'الإعدادات - حصاد')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-6xl mx-auto w-full space-y-6 pb-20">
    <!-- Header -->
    <section>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary mb-2">إعدادات الحساب</h2>
        <p class="text-on-surface-variant text-sm">إدارة حسابك والتفضيلات الخاصة بك</p>
    </section>

    <!-- Settings Tabs -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 overflow-hidden shadow-sm">
        <!-- Tab Navigation -->
        <div class="flex flex-col sm:flex-row border-b border-outline-variant/20 bg-surface-container-low">
            <button onclick="showTab('profile')" class="tab-btn flex-1 px-4 sm:px-6 py-3 sm:py-4 text-center sm:text-left font-bold text-on-surface-variant hover:bg-surface-container transition-colors border-b-2 border-transparent active" data-tab="profile">
                <span class="flex items-center justify-center sm:justify-start gap-2">
                    <span class="material-symbols-outlined text-lg">person</span>
                    <span class="hidden sm:inline">الملف الشخصي</span>
                </span>
            </button>
            <button onclick="showTab('security')" class="tab-btn flex-1 px-4 sm:px-6 py-3 sm:py-4 text-center sm:text-left font-bold text-on-surface-variant hover:bg-surface-container transition-colors border-b-2 border-transparent" data-tab="security">
                <span class="flex items-center justify-center sm:justify-start gap-2">
                    <span class="material-symbols-outlined text-lg">security</span>
                    <span class="hidden sm:inline">الأمان</span>
                </span>
            </button>
            <button onclick="showTab('notifications')" class="tab-btn flex-1 px-4 sm:px-6 py-3 sm:py-4 text-center sm:text-left font-bold text-on-surface-variant hover:bg-surface-container transition-colors border-b-2 border-transparent" data-tab="notifications">
                <span class="flex items-center justify-center sm:justify-start gap-2">
                    <span class="material-symbols-outlined text-lg">notifications</span>
                    <span class="hidden sm:inline">التنبيهات</span>
                </span>
            </button>
            <button onclick="showTab('preferences')" class="tab-btn flex-1 px-4 sm:px-6 py-3 sm:py-4 text-center sm:text-left font-bold text-on-surface-variant hover:bg-surface-container transition-colors border-b-2 border-transparent" data-tab="preferences">
                <span class="flex items-center justify-center sm:justify-start gap-2">
                    <span class="material-symbols-outlined text-lg">tune</span>
                    <span class="hidden sm:inline">التفضيلات</span>
                </span>
            </button>
        </div>

        <!-- Tab Contents -->
        <div class="p-4 sm:p-8">
            <!-- Profile Tab -->
            <div id="profile" class="tab-content">
                <h2 class="text-2xl font-bold text-primary mb-6">معلومات الملف الشخصي</h2>
                
                <form method="POST" action="{{ route('admin.settings.updateProfile') }}" class="space-y-6 max-w-2xl">
                    @csrf
                    
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-2">الاسم الكامل</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" required class="w-full px-4 py-3 border @error('name') border-error @else border-outline-variant @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أدخل اسمك">
                        @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" required class="w-full px-4 py-3 border @error('email') border-error @else border-outline-variant @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أدخل بريدك الإلكتروني">
                        @error('email') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface mb-2">رقم الهاتف</label>
                        <input type="tel" name="phone" value="{{ Auth::user()->phone ?? '' }}" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أدخل رقم هاتفك">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-3 bg-primary text-on-primary font-bold rounded-lg hover:opacity-90 transition-opacity active:scale-95 flex items-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Tab -->
            <div id="security" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-primary mb-6">الأمان والخصوصية</h2>

                <!-- Change Password -->
                <div class="bg-surface-container-low rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-on-surface mb-4">تغيير كلمة المرور</h3>
                    
                    <form method="POST" action="{{ route('admin.settings.changePassword') }}" class="space-y-4 max-w-2xl">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">كلمة المرور الحالية</label>
                            <input type="password" name="current_password" required class="w-full px-4 py-3 border @error('current_password') border-error @else border-outline-variant @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أدخل كلمة المرور الحالية">
                            @error('current_password') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">كلمة المرور الجديدة</label>
                            <input type="password" name="password" required class="w-full px-4 py-3 border @error('password') border-error @else border-outline-variant @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أدخل كلمة مرور قوية">
                            @error('password') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-on-surface mb-2">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أعد إدخال كلمة المرور">
                        </div>

                        <button type="submit" class="px-6 py-3 bg-primary text-on-primary font-bold rounded-lg hover:opacity-90 transition-opacity active:scale-95 flex items-center gap-2">
                            <span class="material-symbols-outlined">lock</span>
                            تحديث كلمة المرور
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div id="notifications" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-primary mb-6">إعدادات التنبيهات</h2>

                <form method="POST" action="{{ route('admin.settings.updateNotifications') }}" class="space-y-4 max-w-2xl">
                    @csrf

                    <div class="bg-surface-container-low rounded-lg p-4 flex items-center gap-3">
                        <label class="flex items-center gap-3 flex-1 cursor-pointer">
                            <input type="checkbox" name="notify_orders" {{ Auth::user()->notify_orders ? 'checked' : '' }} class="w-5 h-5 rounded accent-primary">
                            <div>
                                <p class="font-bold text-on-surface">تنبيهات الطلبات الجديدة</p>
                                <p class="text-sm text-on-surface-variant">تلقي تنبيهات عند طلب عميل جديد</p>
                            </div>
                        </label>
                    </div>

                    <div class="bg-surface-container-low rounded-lg p-4 flex items-center gap-3">
                        <label class="flex items-center gap-3 flex-1 cursor-pointer">
                            <input type="checkbox" name="notify_messages" {{ Auth::user()->notify_messages ? 'checked' : '' }} class="w-5 h-5 rounded accent-primary">
                            <div>
                                <p class="font-bold text-on-surface">تنبيهات الرسائل</p>
                                <p class="text-sm text-on-surface-variant">تلقي تنبيهات عند وصول رسالة جديدة</p>
                            </div>
                        </label>
                    </div>

                    <div class="bg-surface-container-low rounded-lg p-4 flex items-center gap-3">
                        <label class="flex items-center gap-3 flex-1 cursor-pointer">
                            <input type="checkbox" name="notify_products" {{ Auth::user()->notify_products ? 'checked' : '' }} class="w-5 h-5 rounded accent-primary">
                            <div>
                                <p class="font-bold text-on-surface">تنبيهات المنتجات</p>
                                <p class="text-sm text-on-surface-variant">تلقي تنبيهات بشأن منتجات جديدة أو محدثة</p>
                            </div>
                        </label>
                    </div>

                    <div class="bg-surface-container-low rounded-lg p-4 flex items-center gap-3">
                        <label class="flex items-center gap-3 flex-1 cursor-pointer">
                            <input type="checkbox" name="notify_reports" {{ Auth::user()->notify_reports ? 'checked' : '' }} class="w-5 h-5 rounded accent-primary">
                            <div>
                                <p class="font-bold text-on-surface">التقارير وإحصائيات المنصة</p>
                                <p class="text-sm text-on-surface-variant">تلقي تقارير يومية بإحصائيات المنصة</p>
                            </div>
                        </label>
                    </div>

                    <button type="submit" class="px-6 py-3 bg-primary text-on-primary font-bold rounded-lg hover:opacity-90 transition-opacity active:scale-95 flex items-center gap-2 mt-6">
                        <span class="material-symbols-outlined">save</span>
                        حفظ التفضيلات
                    </button>
                </form>
            </div>

            <!-- Preferences Tab -->
            <div id="preferences" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-primary mb-6">التفضيلات</h2>

                <form method="POST" action="{{ route('admin.settings.updateLanguage') }}" class="space-y-4 max-w-2xl">
                    @csrf

                    <div class="bg-surface-container-low rounded-lg p-6">
                        <h3 class="text-lg font-bold text-on-surface mb-4">اختر اللغة</h3>
                        
                        <label class="flex items-center gap-3 mb-3 cursor-pointer p-3 border-2 border-outline-variant rounded-lg hover:border-primary transition-colors" onclick="this.querySelector('input').checked = true">
                            <input type="radio" name="language" value="ar" {{ Auth::user()->language === 'ar' || Auth::user()->language === null ? 'checked' : '' }} class="w-5 h-5 accent-primary">
                            <div>
                                <p class="font-bold text-on-surface">العربية</p>
                                <p class="text-sm text-on-surface-variant">استخدم الواجهة بالعربية</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer p-3 border-2 border-outline-variant rounded-lg hover:border-primary transition-colors" onclick="this.querySelector('input').checked = true">
                            <input type="radio" name="language" value="en" {{ Auth::user()->language === 'en' ? 'checked' : '' }} class="w-5 h-5 accent-primary">
                            <div>
                                <p class="font-bold text-on-surface">English</p>
                                <p class="text-sm text-on-surface-variant">Use the interface in English</p>
                            </div>
                        </label>

                        <button type="submit" class="px-6 py-3 bg-primary text-on-primary font-bold rounded-lg hover:opacity-90 transition-opacity active:scale-95 flex items-center gap-2 mt-6">
                            <span class="material-symbols-outlined">save</span>
                            حفظ اللغة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
function showTab(tabName) {
    // Hide all tabs
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.add('hidden'));

    // Remove active state from all buttons
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Show selected tab
    document.getElementById(tabName).classList.remove('hidden');

    // Add active state to clicked button
    event.target.closest('.tab-btn').classList.add('active');
}
</script>

<style>
.tab-btn.active {
    @apply !text-primary border-b-2 border-primary;
}
</style>
@endsection
