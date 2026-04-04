@extends('layouts.admin')

@section('title', 'إضافة فئة جديدة - حصاد')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.categories.index') }}" class="text-primary hover:underline flex items-center gap-1 flex-row-reverse mb-4">
            <span class="material-symbols-outlined">arrow_back</span>
            العودة للفئات
        </a>
        <h1 class="text-3xl font-black text-primary">إضافة فئة جديدة</h1>
        <p class="text-on-surface-variant mt-2">أنشئ فئة جديدة لتنظيم المنتجات</p>
    </div>

    <!-- Form -->
    <div class="bg-white border border-outline-variant rounded-xl p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Category Name -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-on-surface">اسم الفئة <span class="text-error">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}"
                    required
                    class="w-full border border-outline-variant rounded-lg px-4 py-3 text-right focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('name') border-error @enderror"
                    placeholder="مثال: حشري، فطري، مغذيات..."
                />
                @error('name')
                    <p class="text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Parent Category (Optional) -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-on-surface">فئة رئيسية (اختياري)</label>
                <select 
                    name="parent_id"
                    class="w-full border border-outline-variant rounded-lg px-4 py-3 text-right focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('parent_id') border-error @enderror"
                >
                    <option value="">-- بدون فئة رئيسية --</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-on-surface-variant">اجعل هذه الفئة فرعية من فئة أخرى</p>
                @error('parent_id')
                    <p class="text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Icon/Emoji -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-on-surface">أيقونة الفئة (اختياري)</label>
                <input 
                    type="text" 
                    name="icon" 
                    value="{{ old('icon') }}"
                    maxlength="10"
                    class="w-full border border-outline-variant rounded-lg px-4 py-3 text-right text-2xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('icon') border-error @enderror"
                    placeholder="🐛 أو 🌾 أو 🍃"
                />
                <p class="text-xs text-on-surface-variant">أيقونة أو emoji لتمثيل الفئة (اختياري)</p>
                @error('icon')
                    <p class="text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-3 bg-primary-fixed/10 p-4 rounded-lg border border-primary-fixed/30">
                <input 
                    type="checkbox" 
                    id="is_active"
                    name="is_active" 
                    value="1"
                    {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-5 h-5 accent-primary rounded"
                />
                <label for="is_active" class="font-bold text-primary text-sm cursor-pointer">
                    ✓ تفعيل الفئة على الفور
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 flex-row-reverse border-t border-outline-variant/20 pt-6">
                <button 
                    type="submit" 
                    class="flex-1 bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-primary/90 transition-all flex items-center justify-center gap-2"
                >
                    <span class="material-symbols-outlined">add</span>
                    إضافة الفئة
                </button>
                <a 
                    href="{{ route('admin.categories.index') }}" 
                    class="flex-1 border border-outline-variant px-6 py-3 rounded-lg font-bold text-on-surface hover:bg-stone-50 transition-all flex items-center justify-center gap-2 text-center"
                >
                    <span class="material-symbols-outlined">close</span>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
