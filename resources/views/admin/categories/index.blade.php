@extends('layouts.admin')

@section('title', 'إدارة الفئات - حصاد')

@section('content')
<div class="flex justify-between items-start mb-8">
    <div>
        <h1 class="text-3xl lg:text-4xl font-black text-primary mb-2">إدارة فئات المنتجات</h1>
        <p class="text-on-surface-variant">إدارة جميع فئات المنتجات والتحكم بها</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-primary/90 transition-all flex items-center gap-2">
        <span class="material-symbols-outlined">add</span>
        إضافة فئة جديدة
    </a>
</div>

<!-- Messages -->
@if ($errors->any())
    <div class="bg-error-fixed/20 border border-error rounded-xl p-4 mb-6 text-error">
        <div class="font-bold mb-2">خطأ في العملية</div>
        <ul class="space-y-1">
            @foreach ($errors->all() as $error)
                <li class="text-sm">• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="bg-tertiary-fixed/20 border border-tertiary rounded-xl p-4 mb-6 text-tertiary font-bold">
        ✓ {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-error-fixed/20 border border-error rounded-xl p-4 mb-6 text-error font-bold">
        ✗ {{ session('error') }}
    </div>
@endif

<!-- Categories Grid -->
<div class="grid gap-6">
    @forelse($categories as $category)
        <div class="bg-white border border-outline-variant rounded-xl p-6 hover:shadow-lg transition-all">
            <!-- Category Header -->
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-start gap-4 flex-1 flex-row-reverse">
                    @if($category->icon)
                        <div class="text-4xl">{{ $category->icon }}</div>
                    @else
                        <div class="w-12 h-12 bg-primary-fixed rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">category</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-xl font-black text-primary">{{ $category->name }}</h3>
                        <p class="text-sm text-on-surface-variant mt-1">
                            <span class="font-bold text-primary">{{ $category->products_count }}</span>
                            منتج
                            @if($category->subcategories->count() > 0)
                                • <span class="font-bold text-tertiary">{{ $category->subcategories->count() }}</span> فئة فرعية
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $category->is_active ? 'bg-tertiary-fixed/30 text-tertiary' : 'bg-error-fixed/30 text-error' }}">
                        {{ $category->is_active ? '✓ مفعل' : '✗ معطل' }}
                    </span>
                </div>
            </div>

            <!-- Subcategories -->
            @if($category->subcategories->count() > 0)
                <div class="bg-stone-50 rounded-lg p-4 mb-4">
                    <p class="text-xs font-bold text-on-surface-variant mb-3">الفئات الفرعية:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($category->subcategories as $sub)
                            <span class="bg-primary-fixed/20 text-primary px-3 py-1 rounded-full text-sm">
                                {{ $sub->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex gap-3 flex-row-reverse border-t border-outline-variant/20 pt-4">
                <a href="{{ route('admin.categories.edit', $category) }}" class="flex-1 flex items-center justify-center gap-2 bg-primary-fixed/20 text-primary px-4 py-2 rounded-lg hover:bg-primary-fixed/30 transition-colors font-bold text-sm">
                    <span class="material-symbols-outlined text-sm">edit</span>
                    تعديل
                </a>

                <button onclick="toggleCategory({{ $category->id }})" class="flex-1 flex items-center justify-center gap-2 {{ $category->is_active ? 'bg-error-fixed/20 text-error' : 'bg-tertiary-fixed/20 text-tertiary' }} px-4 py-2 rounded-lg hover:opacity-80 transition-opacity font-bold text-sm">
                    <span class="material-symbols-outlined text-sm">{{ $category->is_active ? 'visibility_off' : 'visibility' }}</span>
                    {{ $category->is_active ? 'تعطيل' : 'تفعيل' }}
                </button>

                <button onclick="deleteCategory(event, {{ $category->id }})" class="flex-1 flex items-center justify-center gap-2 bg-error-fixed/20 text-error px-4 py-2 rounded-lg hover:bg-error-fixed/30 transition-colors font-bold text-sm">
                    <span class="material-symbols-outlined text-sm">delete</span>
                    حذف
                </button>
            </div>
        </div>
    @empty
        <div class="text-center py-16 bg-stone-50 rounded-xl">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant mb-4">category</span>
            <p class="text-on-surface-variant mb-4">لا توجد فئات متاحة</p>
            <a href="{{ route('admin.categories.create') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-lg font-bold hover:bg-primary/90 transition-all">
                إضافة فئة جديدة
            </a>
        </div>
    @endforelse
</div>

<script>
    function deleteCategory(event, categoryId) {
        event.preventDefault();
        
        showDeleteConfirm(
            'حذف الفئة',
            'هل أنت متأكد من حذف هذه الفئة؟ لا يمكن التراجع عن هذا الإجراء.',
            () => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                const token = csrfToken ? csrfToken.content : '';
                
                fetch(`/admin/categories/${categoryId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        return response.json().then(data => {
                            showError(data.error || 'خطأ في حذف الفئة');
                        });
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    showError('خطأ في الاتصال');
                });
            },
            'حذف'
        );
    }

    function toggleCategory(categoryId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const token = csrfToken ? csrfToken.content : '';
        
        fetch(`/admin/categories/${categoryId}/toggle-active`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                showError('خطأ في تغيير حالة الفئة');
            }
        }).catch(error => {
            console.error('Error:', error);
            showError('خطأ في الاتصال');
        });
    }
</script>
@endsection
