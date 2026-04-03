{{-- Shipping Form Card Component (Right Column - Sticky) --}}
@props(['action' => '', 'method' => 'POST'])

<div class="bg-surface-container-low p-6 md:p-8 editorial-shadow space-y-6 md:space-y-8 rounded-2xl" style="box-shadow: 0 24px 48px -12px rgba(21, 66, 18, 0.08);">
    {{-- Form Header --}}
    <h2 class="text-xl md:text-2xl font-bold text-primary flex items-center gap-2 font-headline">
        <span class="material-symbols-outlined text-xl md:text-2xl">local_shipping</span>
        بيانات الشحن
    </h2>
    
    <form action="{{ $action }}" method="{{ $method }}" class="space-y-4 md:space-y-6">
        @csrf
        
        {{-- Governorate Select --}}
        <div class="space-y-1 md:space-y-2">
            <label class="block text-xs md:text-sm font-bold text-on-surface-variant font-headline">المحافظة</label>
            <select 
                name="delivery_governorate"
                required
                class="w-full bg-surface-container-highest border-none focus:ring-0 focus:border-b-2 focus:border-primary py-3 px-4 font-body rounded-lg"
            >
                <option value="" disabled selected>اختر المحافظة</option>
                <option value="القاهرة">القاهرة</option>
                <option value="الجيزة">الجيزة</option>
                <option value="الأسكندرية">الأسكندرية</option>
                <option value="الدقهلية">الدقهلية</option>
                <option value="الشرقية">الشرقية</option>
                <option value="المنوفية">المنوفية</option>
                <option value="القليوبية">القليوبية</option>
                <option value="البحيرة">البحيرة</option>
                <option value="الغربية">الغربية</option>
                <option value="بورسعيد">بورسعيد</option>
                <option value="دمياط">دمياط</option>
                <option value="الإسماعيلية">الإسماعيلية</option>
                <option value="السويس">السويس</option>
                <option value="كفر الشيخ">كفر الشيخ</option>
                <option value="الفيوم">الفيوم</option>
                <option value="بني سويف">بني سويف</option>
                <option value="المنيا">المنيا</option>
                <option value="أسيوط">أسيوط</option>
                <option value="سوهاج">سوهاج</option>
                <option value="قنا">قنا</option>
                <option value="الأقصر">الأقصر</option>
                <option value="أسوان">أسوان</option>
            </select>
            @error('delivery_governorate')
                <span class="text-error text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>
        
        {{-- Detailed Address --}}
        <div class="space-y-1 md:space-y-2">
            <label class="block text-xs md:text-sm font-bold text-on-surface-variant font-headline">العنوان بالتفصيل</label>
            <textarea 
                name="delivery_address"
                required
                placeholder="اسم القرية / الشارع / علامة مميزة"
                rows="3"
                class="w-full bg-surface-container-highest border-none focus:ring-0 focus:border-b-2 focus:border-primary py-2 md:py-3 px-3 md:px-4 font-body resize-none rounded-lg text-sm"
            ></textarea>
            @error('delivery_address')
                <span class="text-error text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Phone Number --}}
        <div class="space-y-1 md:space-y-2">
            <label class="block text-xs md:text-sm font-bold text-on-surface-variant font-headline">رقم الهاتف</label>
            <input 
                type="tel" 
                name="phone"
                placeholder="01XXXXXXXXX"
                maxlength="11"
                required
                class="w-full bg-surface-container-highest border-none focus:ring-0 focus:border-b-2 focus:border-primary py-2 md:py-3 px-3 md:px-4 font-body rounded-lg text-sm"
            />
            @error('phone')
                <span class="text-error text-xs mt-1 block">{{ $message }}</span>
            @enderror
        </div>
        
        {{-- Payment Method --}}
        <div class="space-y-3 md:space-y-4">
            <label class="block text-xs md:text-sm font-bold text-on-surface-variant font-headline">طريقة الدفع</label>
            
            {{-- COD (Active) --}}
            <label class="flex items-center gap-3 md:gap-4 p-3 md:p-4 bg-surface-container-lowest cursor-pointer border-2 border-primary rounded-lg group hover:bg-surface transition-colors">
                <input 
                    type="radio" 
                    name="payment_method" 
                    value="cod" 
                    checked
                    class="text-primary focus:ring-primary h-4 md:h-5 w-4 md:w-5"
                />
                <div class="flex flex-col flex-grow">
                    <span class="font-bold text-primary text-sm md:text-base">الدفع عند الاستلام</span>
                    <span class="text-xs text-on-surface-variant">ادفع نقداً للمندوب عند وصول الشحنة</span>
                </div>
                <span class="material-symbols-outlined text-primary shrink-0 text-base md:text-lg">payments</span>
            </label>
            
            {{-- Online (Disabled) --}}
            <label class="flex items-center gap-3 md:gap-4 p-3 md:p-4 bg-surface-container-highest/50 cursor-not-allowed opacity-50 rounded-lg">
                <input 
                    type="radio" 
                    name="payment_method" 
                    value="online" 
                    disabled
                    class="text-outline focus:ring-0 h-4 md:h-5 w-4 md:w-5"
                />
                <div class="flex flex-col flex-grow">
                    <span class="font-bold text-on-surface-variant text-sm md:text-base">بطاقة ائتمان / ميزة</span>
                    <span class="text-xs text-on-surface-variant">غير متاح حالياً لهذا النوع من الطلبات</span>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant shrink-0 text-base md:text-lg">credit_card</span>
            </label>
        </div>
        
        {{-- Additional Notes --}}
        <div class="space-y-1 md:space-y-2">
            <label class="block text-xs md:text-sm font-bold text-on-surface-variant font-headline">ملاحظات إضافية (اختياري)</label>
            <textarea 
                name="notes"
                placeholder="أي تعليمات خاصة بالتسليم..."
                rows="2"
                class="w-full bg-surface-container-highest border-none focus:ring-0 focus:border-b-2 focus:border-primary py-2 md:py-3 px-3 md:px-4 font-body resize-none rounded-lg text-sm"
            ></textarea>
        </div>
        
        {{-- Submit Button --}}
        <button 
            type="submit"
            class="w-full bg-primary text-on-primary py-3 md:py-4 rounded-lg font-bold md:font-black text-base md:text-lg hover:shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2 md:gap-3 mt-4 md:mt-6"
        >
            <span>إرسال الطلب</span>
            <span class="material-symbols-outlined text-base md:text-lg">send</span>
        </button>
    </form>
</div>
