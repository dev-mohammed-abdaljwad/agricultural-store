@extends('layouts.admin')

@section('title', 'تعديل الفئة: ' . $category->name . ' - حصاد')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.categories.index') }}" class="text-primary hover:underline flex items-center gap-1 flex-row-reverse mb-4">
            <span class="material-symbols-outlined">arrow_back</span>
            العودة للفئات
        </a>
        <h1 class="text-3xl font-black text-primary">تعديل الفئة: {{ $category->name }}</h1>
        <p class="text-on-surface-variant mt-2">قم بتحديث بيانات الفئة</p>
    </div>

    <!-- Form -->
    <div class="bg-white border border-outline-variant rounded-xl p-8">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Category Name -->
            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-on-surface">اسم الفئة <span class="text-error">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name', $category->name) }}"
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
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
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
                <div class="flex gap-3">
                    @if($category->icon)
                        <div class="w-16 h-16 bg-primary-fixed/20 rounded-lg flex items-center justify-center text-3xl border-2 border-primary-fixed">
                            {{ $category->icon }}
                        </div>
                    @endif
                    <input 
                        type="text" 
                        name="icon" 
                        value="{{ old('icon', $category->icon) }}"
                        maxlength="10"
                        class="flex-1 border border-outline-variant rounded-lg px-4 py-3 text-right text-2xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all @error('icon') border-error @enderror"
                        placeholder="🐛 أو 🌾 أو 🍃"
                    />
                </div>
                <p class="text-xs text-on-surface-variant">أيقونة أو emoji لتمثيل الفئة (اختياري)</p>
                @error('icon')
                    <p class="text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-3 {{ $category->is_active ? 'bg-primary-fixed/10 border border-primary-fixed/30' : 'bg-error-fixed/10 border border-error-fixed/30' }} p-4 rounded-lg">
                <input 
                    type="checkbox" 
                    id="is_active"
                    name="is_active" 
                    value="1"
                    {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                    class="w-5 h-5 accent-primary rounded"
                />
                <label for="is_active" class="font-bold {{ $category->is_active ? 'text-primary' : 'text-error' }} text-sm cursor-pointer">
                    {{ $category->is_active ? '✓ الفئة مفعلة' : '✗ الفئة معطلة' }}
                </label>
            </div>

            <!-- Category Stats -->
            <div class="bg-stone-50 p-4 rounded-lg border border-outline-variant/20 space-y-2">
                <p class="text-sm">
                    <span class="font-bold text-primary">{{ $category->products()->count() }}</span>
                    <span class="text-on-surface-variant">منتج مرتبط بهذه الفئة</span>
                </p>
                @if($category->subcategories()->count() > 0)
                    <p class="text-sm">
                        <span class="font-bold text-tertiary">{{ $category->subcategories()->count() }}</span>
                        <span class="text-on-surface-variant">فئة فرعية</span>
                    </p>
                @endif
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 flex-row-reverse border-t border-outline-variant/20 pt-6">
                <button 
                    type="submit" 
                    class="flex-1 bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-primary/90 transition-all flex items-center justify-center gap-2"
                >
                    <span class="material-symbols-outlined">save</span>
                    حفظ التعديلات
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
