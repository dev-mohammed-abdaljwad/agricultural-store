@extends('layouts.customer')

@section('title', 'الإعدادات - حصاد')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary mb-2">الإعدادات</h1>
        <p class="text-on-surface-variant">إدارة حسابك والتفضيلات الخاصة بك</p>
    </div>

    <!-- Flash Messages -->
    @if ($message = Session::get('success'))
    <div class="mb-6 p-4 rounded-lg bg-primary-fixed text-primary border border-primary flex items-center gap-3 animate-in fade-in slide-in-from-top">
        <span class="material-symbols-outlined">check_circle</span>
        <span>{{ $message }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto text-lg hover:opacity-70">×</button>
    </div>
    @endif

    @if ($message = Session::get('error'))
    <div class="mb-6 p-4 rounded-lg bg-error-container text-error border border-error flex items-center gap-3 animate-in fade-in slide-in-from-top">
        <span class="material-symbols-outlined">error</span>
        <span>{{ $message }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto text-lg hover:opacity-70">×</button>
    </div>
    @endif

    <!-- Settings Tabs -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/20 overflow-hidden editorial-shadow">
        <!-- Tab Navigation -->
        <div class="flex flex-col sm:flex-row border-b border-outline-variant/20 bg-surface-container-low">
            <button onclick="showTab('profile')" class="tab-btn flex-1 px-6 py-4 text-left font-bold text-on-surface-variant hover:bg-surface-container transition-colors border-b-2 border-transparent active" data-tab="profile">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined">person</span>
                    <span>الملف الشخصي</span>
                </span>
            </button>
            <button onclick="showTab('security')" class="tab-btn flex-1 px-6 py-4 text-left font-bold text-on-surface-variant hover:bg-surface-container transition-colors border-b-2 border-transparent" data-tab="security">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined">security</span>
                    <span>الأمان</span>
                </span>
            </button>
            <button onclick="showTab('notifications')" class="tab-btn flex-1 px-6 py-4 text-left font-bold text-on-surface-variant hover:bg-surface-container transition-colors border-b-2 border-transparent" data-tab="notifications">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined">notifications</span>
                    <span>التنبيهات</span>
                </span>
            </button>
            <button onclick="showTab('preferences')" class="tab-btn flex-1 px-6 py-4 text-left font-bold text-on-surface-variant hover:bg-surface-container transition-colors border-b-2 border-transparent" data-tab="preferences">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-outlined">tune</span>
                    <span>التفضيلات</span>
                </span>
            </button>
        </div>

        <!-- Tab Contents -->
        <div class="p-6 sm:p-8">
            <!-- Profile Tab -->
            <div id="profile" class="tab-content">
                <h2 class="text-2xl font-bold text-primary mb-6">معلومات الملف الشخصي</h2>
                
                <form method="POST" action="{{ route('settings.updateProfile') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface-variant mb-2">الاسم الكامل</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full px-4 py-3 border @error('name') border-error @else border-outline-variant @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أدخل اسمك">
                        @error('name') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface-variant mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full px-4 py-3 border @error('email') border-error @else border-outline-variant @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أدخل بريدك الإلكتروني">
                        @error('email') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface-variant mb-2">رقم الهاتف</label>
                        <input type="tel" name="phone" value="{{ Auth::user()->phone ?? '' }}" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أدخل رقم هاتفك">
                    </div>

                    <!-- Governorate -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface-variant mb-2">المحافظة</label>
                        <select name="governorate" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">اختر المحافظة</option>
                            <option value="Cairo" {{ Auth::user()->governorate === 'Cairo' ? 'selected' : '' }}>القاهرة</option>
                            <option value="Giza" {{ Auth::user()->governorate === 'Giza' ? 'selected' : '' }}>الجيزة</option>
                            <option value="Alexandria" {{ Auth::user()->governorate === 'Alexandria' ? 'selected' : '' }}>الإسكندرية</option>
                            <option value="Qalyubia" {{ Auth::user()->governorate === 'Qalyubia' ? 'selected' : '' }}>القليوبية</option>
                            <option value="Dakahlia" {{ Auth::user()->governorate === 'Dakahlia' ? 'selected' : '' }}>الدقهلية</option>
                            <option value="Damietta" {{ Auth::user()->governorate === 'Damietta' ? 'selected' : '' }}>دمياط</option>
                            <option value="Beheira" {{ Auth::user()->governorate === 'Beheira' ? 'selected' : '' }}>البحيرة</option>
                            <option value="Kafr El-Sheikh" {{ Auth::user()->governorate === 'Kafr El-Sheikh' ? 'selected' : '' }}>كفر الشيخ</option>
                            <option value="Gharbia" {{ Auth::user()->governorate === 'Gharbia' ? 'selected' : '' }}>الغربية</option>
                            <option value="Monufia" {{ Auth::user()->governorate === 'Monufia' ? 'selected' : '' }}>المنوفية</option>
                            <option value="Menouf" {{ Auth::user()->governorate === 'Menouf' ? 'selected' : '' }}>منوف</option>
                            <option value="Aswan" {{ Auth::user()->governorate === 'Aswan' ? 'selected' : '' }}>أسوان</option>
                            <option value="Luxor" {{ Auth::user()->governorate === 'Luxor' ? 'selected' : '' }}>الأقصر</option>
                            <option value="Qena" {{ Auth::user()->governorate === 'Qena' ? 'selected' : '' }}>قنا</option>
                            <option value="Sohag" {{ Auth::user()->governorate === 'Sohag' ? 'selected' : '' }}>سوهاج</option>
                            <option value="Assiut" {{ Auth::user()->governorate === 'Assiut' ? 'selected' : '' }}>أسيوط</option>
                            <option value="Minya" {{ Auth::user()->governorate === 'Minya' ? 'selected' : '' }}>المنيا</option>
                            <option value="Beni Suef" {{ Auth::user()->governorate === 'Beni Suef' ? 'selected' : '' }}>بني سويف</option>
                            <option value="Faiyum" {{ Auth::user()->governorate === 'Faiyum' ? 'selected' : '' }}>الفيوم</option>
                            <option value="Suez" {{ Auth::user()->governorate === 'Suez' ? 'selected' : '' }}>السويس</option>
                            <option value="Ismailia" {{ Auth::user()->governorate === 'Ismailia' ? 'selected' : '' }}>الإسماعيلية</option>
                            <option value="Port Said" {{ Auth::user()->governorate === 'Port Said' ? 'selected' : '' }}>بورسعيد</option>
                            <option value="North Sinai" {{ Auth::user()->governorate === 'North Sinai' ? 'selected' : '' }}>شمال سيناء</option>
                            <option value="South Sinai" {{ Auth::user()->governorate === 'South Sinai' ? 'selected' : '' }}>جنوب سيناء</option>
                            <option value="Red Sea" {{ Auth::user()->governorate === 'Red Sea' ? 'selected' : '' }}>البحر الأحمر</option>
                            <option value="Matrouh" {{ Auth::user()->governorate === 'Matrouh' ? 'selected' : '' }}>مطروح</option>
                        </select>
                    </div>

                    <!-- Customer Type -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface-variant mb-2">نوع الحساب</label>
                        <select name="customer_type" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="farmer" {{ Auth::user()->customer_type === 'farmer' ? 'selected' : '' }}>مزارع</option>
                            <option value="trader" {{ Auth::user()->customer_type === 'trader' ? 'selected' : '' }}>تاجر محاصيل</option>
                        </select>
                    </div>

                    <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-bold hover:bg-primary/90 transition-colors">
                        <span class="material-symbols-outlined">save</span>
                        حفظ التغييرات
                    </button>
                </form>
            </div>

            <!-- Security Tab -->
            <div id="security" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-primary mb-6">الأمان والخصوصية</h2>
                
                <!-- Change Password Section -->
                <div class="mb-8 pb-8 border-b border-outline-variant/20">
                    <h3 class="text-lg font-bold text-on-surface mb-4">تغيير كلمة المرور</h3>
                    <form method="POST" action="{{ route('settings.changePassword') }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-bold text-on-surface-variant mb-2">كلمة المرور الحالية</label>
                            <input type="password" name="current_password" class="w-full px-4 py-3 border @error('current_password') border-error @else border-outline-variant @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أدخل كلمة المرور الحالية">
                            @error('current_password') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-on-surface-variant mb-2">كلمة المرور الجديدة</label>
                            <input type="password" name="password" class="w-full px-4 py-3 border @error('password') border-error @else border-outline-variant @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أدخل كلمة المرور الجديدة">
                            @error('password') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-on-surface-variant mb-2">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="أعد إدخال كلمة المرور">
                        </div>

                        <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-bold hover:bg-primary/90 transition-colors">
                            <span class="material-symbols-outlined">lock</span>
                            تحديث كلمة المرور
                        </button>
                    </form>
                </div>

                <!-- Delete Account Section -->
                <div class="p-6 sm:p-8 bg-error/10 border border-error rounded-xl">
                    <h3 class="text-lg font-bold text-error mb-4">حذف الحساب</h3>
                    <p class="text-on-surface-variant mb-6">حذف حسابك وجميع بيانات - هذا الإجراء لا يمكن التراجع عنه</p>
                    
                    <button type="button" onclick="showDeleteConfirm()" class="px-6 py-3 bg-error text-white rounded-lg font-bold hover:bg-error/90 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined">delete</span>
                        حذف الحساب
                    </button>

                    <!-- Delete Confirmation Modal -->
                    <div id="deleteConfirmModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-xl p-6 sm:p-8 max-w-md mx-4">
                            <h4 class="text-xl font-bold text-error mb-4">تأكيد حذف الحساب</h4>
                            <p class="text-on-surface-variant mb-6">هذا الإجراء لا يمكن التراجع عنه. سيتم حذف جميع بيانات حسابك نهائياً.</p>
                            
                            <form method="POST" action="{{ route('settings.deleteAccount') }}" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-bold text-on-surface-variant mb-2">أدخل كلمة المرور للتأكيد</label>
                                    <input type="password" name="password" required class="w-full px-4 py-3 border @error('password') border-error @else border-outline-variant @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-error" placeholder="كلمة المرور">
                                    @error('password') <span class="text-error text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="confirm_delete" id="confirmDelete" required class="w-4 h-4">
                                    <label for="confirmDelete" class="text-sm text-on-surface-variant">أوافق على حذف حسابي نهائياً</label>
                                </div>

                                <div class="flex gap-3">
                                    <button type="submit" class="flex-1 px-6 py-3 bg-error text-white rounded-lg font-bold hover:bg-error/90 transition-colors">
                                        حذف الحساب
                                    </button>
                                    <button type="button" onclick="hideDeleteConfirm()" class="flex-1 px-6 py-3 bg-surface-container text-on-surface rounded-lg font-bold hover:bg-surface-container-high transition-colors">
                                        إلغاء
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div id="notifications" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-primary mb-6">إعدادات التنبيهات</h2>
                
                <form method="POST" action="{{ route('settings.updateNotifications') }}" class="space-y-4">
                    @csrf
                    
                    <!-- Email Notifications -->
                    <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-lg border border-outline-variant/20">
                        <div class="flex-1">
                            <p class="font-bold text-on-surface">تنبيهات الطلبات عبر البريد</p>
                            <p class="text-sm text-on-surface-variant">استقبل تحديثات حول حالة طلباتك</p>
                        </div>
                        <input type="checkbox" name="notify_orders" value="1" class="w-6 h-6 cursor-pointer">
                    </div>

                    <!-- Message Notifications -->
                    <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-lg border border-outline-variant/20">
                        <div class="flex-1">
                            <p class="font-bold text-on-surface">تنبيهات الرسائل الجديدة</p>
                            <p class="text-sm text-on-surface-variant">احصل على تنبيهات عند وصول رسائل جديدة</p>
                        </div>
                        <input type="checkbox" name="notify_messages" value="1" class="w-6 h-6 cursor-pointer" checked>
                    </div>

                    <!-- Price Changes -->
                    <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-lg border border-outline-variant/20">
                        <div class="flex-1">
                            <p class="font-bold text-on-surface">إخطارات تغير الأسعار</p>
                            <p class="text-sm text-on-surface-variant">تنبيهات عند تغيير أسعار المنتجات</p>
                        </div>
                        <input type="checkbox" name="notify_price_changes" value="1" class="w-6 h-6 cursor-pointer">
                    </div>

                    <!-- Promotional -->
                    <div class="flex items-center justify-between p-4 bg-surface-container-low rounded-lg border border-outline-variant/20">
                        <div class="flex-1">
                            <p class="font-bold text-on-surface">الإعلانات والعروض الخاصة</p>
                            <p class="text-sm text-on-surface-variant">استقبل عروضًا خاصة وتحديثات ترويجية</p>
                        </div>
                        <input type="checkbox" name="notify_promotions" value="1" class="w-6 h-6 cursor-pointer">
                    </div>

                    <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-bold hover:bg-primary/90 transition-colors mt-6">
                        <span class="material-symbols-outlined">save</span>
                        حفظ التنبيهات
                    </button>
                </form>
            </div>

            <!-- Preferences Tab -->
            <div id="preferences" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-primary mb-6">التفضيلات</h2>
                
                <form method="POST" action="{{ route('settings.updateLanguage') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Language -->
                    <div>
                        <label class="block text-sm font-bold text-on-surface-variant mb-4">اللغة</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="language" value="ar" {{ !Auth::user()->language || Auth::user()->language === 'ar' ? 'checked' : '' }} class="w-4 h-4">
                                <span>العربية</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="language" value="en" {{ Auth::user()->language === 'en' ? 'checked' : '' }} class="w-4 h-4">
                                <span>English</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg font-bold hover:bg-primary/90 transition-colors">
                        <span class="material-symbols-outlined">save</span>
                        حفظ التفضيلات
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="mt-8 p-6 sm:p-8 bg-error/10 border border-error rounded-xl">
        <h2 class="text-lg font-bold text-error mb-4">منطقة الخطر</h2>
        <p class="text-on-surface-variant mb-4">حذف حسابك وجميع بيانات - هذا الإجراء لا يمكن التراجع عنه</p>
        <button type="button" class="px-6 py-3 bg-error text-white rounded-lg font-bold hover:bg-error/90 transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined">delete</span>
            حذف الحساب
        </button>
    </div>
</div>

<script>
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Show selected tab
        document.getElementById(tabName).classList.remove('hidden');

        // Update button styles
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'border-b-primary');
            btn.classList.add('border-b-transparent');
        });
        
        event.target.closest('.tab-btn').classList.add('active', 'border-b-primary');
        event.target.closest('.tab-btn').classList.remove('border-b-transparent');
    }

    function showDeleteConfirm() {
        document.getElementById('deleteConfirmModal').classList.remove('hidden');
    }

    function hideDeleteConfirm() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('deleteConfirmModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            hideDeleteConfirm();
        }
    });
</script>
@endsection
