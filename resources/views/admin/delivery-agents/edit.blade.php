{{-- resources/views/admin/delivery-agents/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'تعديل عامل توصيل')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.delivery-agents.show', $agent) }}" class="text-primary hover:text-primary/80 flex items-center gap-1 mb-4">
            <span class="material-symbols-outlined">arrow_back</span>
            عودة
        </a>
        <h1 class="text-3xl font-bold text-on-surface">تعديل: {{ $agent->name }}</h1>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.delivery-agents.update', $agent) }}" class="bg-surface-bright rounded-lg border border-outline p-8">
        @csrf
        @method('PUT')

        {{-- Personal Information Section --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span>
                البيانات الشخصية
            </h2>

            {{-- Name --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">الاسم الكامل <span class="text-error">*</span></label>
                <input type="text" name="name" value="{{ old('name', $agent->name) }}" required class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('name') border-error @enderror">
                @error('name')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">رقم الهاتف <span class="text-error">*</span></label>
                <input type="tel" name="phone" value="{{ old('phone', $agent->phone) }}" required class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('phone') border-error @enderror">
                @error('phone')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">البريد الإلكتروني <span class="text-error">*</span></label>
                <input type="email" name="email" value="{{ old('email', $agent->email) }}" required class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('email') border-error @enderror">
                @error('email')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ID Number --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">رقم الهوية</label>
                <input type="text" name="id_number" value="{{ old('id_number', $agent->id_number) }}" class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('id_number') border-error @enderror">
                @error('id_number')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Address Information --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">location_on</span>
                البيانات الجغرافية
            </h2>

            {{-- Governorate --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">المحافظة <span class="text-error">*</span></label>
                <select name="governorate" required class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('governorate') border-error @enderror">
                    @foreach($governorates as $key => $value)
                        <option value="{{ $key }}" {{ old('governorate', $agent->governorate) === $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                @error('governorate')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Address --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">العنوان التفصيلي <span class="text-error">*</span></label>
                <textarea name="address" required rows="3" class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('address') border-error @enderror">{{ old('address', $agent->address) }}</textarea>
                @error('address')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Vehicle Information --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">local_shipping</span>
                بيانات المركبة
            </h2>

            {{-- Vehicle Type --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">نوع المركبة <span class="text-error">*</span></label>
                <select name="vehicle_type" required class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('vehicle_type') border-error @enderror">
                    <option value="car" {{ old('vehicle_type', $agent->vehicle_type) === 'car' ? 'selected' : '' }}>سيارة</option>
                    <option value="motorcycle" {{ old('vehicle_type', $agent->vehicle_type) === 'motorcycle' ? 'selected' : '' }}>دراجة نارية</option>
                    <option value="bicycle" {{ old('vehicle_type', $agent->vehicle_type) === 'bicycle' ? 'selected' : '' }}>دراجة</option>
                    <option value="van" {{ old('vehicle_type', $agent->vehicle_type) === 'van' ? 'selected' : '' }}>فان</option>
                </select>
                @error('vehicle_type')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- License Plate --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">رقم اللوحة</label>
                <input type="text" name="license_plate" value="{{ old('license_plate', $agent->license_plate) }}" class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('license_plate') border-error @enderror">
                @error('license_plate')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Payment Information --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">payment</span>
                بيانات الدفع
            </h2>

            {{-- Salary Type --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">نوع الراتب <span class="text-error">*</span></label>
                <select name="salary_type" required class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('salary_type') border-error @enderror">
                    <option value="fixed" {{ old('salary_type', $agent->salary_type) === 'fixed' ? 'selected' : '' }}>راتب ثابت</option>
                    <option value="commission" {{ old('salary_type', $agent->salary_type) === 'commission' ? 'selected' : '' }}>عمولة</option>
                    <option value="hybrid" {{ old('salary_type', $agent->salary_type) === 'hybrid' ? 'selected' : '' }}>راتب + عمولة</option>
                </select>
                @error('salary_type')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Commission Rate --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">نسبة العمولة (%) <span class="text-error">*</span></label>
                <input type="number" name="commission_rate" value="{{ old('commission_rate', $agent->commission_rate) }}" step="0.01" min="0" max="100" required class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('commission_rate') border-error @enderror">
                @error('commission_rate')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bank Account --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">رقم الحساب البنكي</label>
                <input type="text" name="bank_account" value="{{ old('bank_account', $agent->bank_account) }}" class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('bank_account') border-error @enderror">
                @error('bank_account')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Status --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">verified_user</span>
                الحالة
            </h2>

            {{-- Status --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">حالة العامل <span class="text-error">*</span></label>
                <select name="status" required class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('status') border-error @enderror">
                    <option value="active" {{ old('status', $agent->status) === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ old('status', $agent->status) === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    <option value="on_leave" {{ old('status', $agent->status) === 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                </select>
                @error('status')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Notes --}}
        <div class="mb-8">
            <h2 class="text-xl font-bold text-on-surface mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">note</span>
                ملاحظات
            </h2>

            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">ملاحظات إضافية</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface @error('notes') border-error @enderror">{{ old('notes', $agent->notes) }}</textarea>
                @error('notes')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-4 justify-end pt-6 border-t border-outline">
            <a href="{{ route('admin.delivery-agents.show', $agent) }}" class="px-6 py-2 border border-outline rounded-lg font-bold text-on-surface hover:bg-surface-container transition">
                إلغاء
            </a>
            <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition">
                حفظ التغييرات
            </button>
        </div>
    </form>
</div>
@endsection
