@extends('layouts.admin')

@section('title', 'إدارة المنتجات - نيل هارفست')

@section('content')
<div class="flex flex-col gap-6 lg:gap-8">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row-reverse lg:justify-between lg:items-end gap-4 lg:gap-0">
        <div class="text-right">
            <h2 class="text-2xl lg:text-3xl font-bold text-primary mb-1">إدارة المنتجات</h2>
            <p class="text-sm lg:text-base text-on-surface-variant">إدارة وتحديث المخزون والمنتجات المتاحة في المتجر</p>
        </div>
        <button onclick="openProductModal()" class="bg-primary text-white px-4 lg:px-6 py-2 lg:py-3 rounded-xl font-bold flex flex-row-reverse items-center gap-2 shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all w-full lg:w-auto justify-center lg:justify-start">
            <span class="material-symbols-outlined">add</span>
            إضافة منتج جديد
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
        <div class="bg-surface-container-lowest p-4 lg:p-6 rounded-2xl border-r-4 border-primary shadow-sm flex flex-row-reverse items-center justify-between">
            <div class="text-right flex-1">
                <p class="text-xs lg:text-sm text-on-surface-variant mb-1">إجمالي المنتجات</p>
                <h3 class="text-2xl lg:text-3xl font-bold text-on-surface">{{ count($products) }}</h3>
            </div>
            <div class="w-10 lg:w-12 h-10 lg:h-12 bg-primary-fixed rounded-full flex items-center justify-center text-primary flex-shrink-0">
                <span class="material-symbols-outlined text-lg lg:text-2xl">inventory</span>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-4 lg:p-6 rounded-2xl border-r-4 border-primary shadow-sm flex flex-row-reverse items-center justify-between">
            <div class="text-right flex-1">
                <p class="text-xs lg:text-sm text-on-surface-variant mb-1">المنتجات النشطة</p>
                <h3 class="text-2xl lg:text-3xl font-bold text-on-surface">
                    @php
                        $activeCount = 0;
                        foreach($products as $p) {
                            if($p->status === 'active') $activeCount++;
                        }
                        echo $activeCount;
                    @endphp
                </h3>
            </div>
            <div class="w-10 lg:w-12 h-10 lg:h-12 bg-primary-fixed rounded-full flex items-center justify-center text-primary flex-shrink-0">
                <span class="material-symbols-outlined text-lg lg:text-2xl">check_circle</span>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-4 lg:p-6 rounded-2xl border-r-4 border-primary shadow-sm flex flex-row-reverse items-center justify-between">
            <div class="text-right flex-1">
                <p class="text-xs lg:text-sm text-on-surface-variant mb-1">المنتجات المعتمدة</p>
                <h3 class="text-2xl lg:text-3xl font-bold text-primary">
                    @php
                        $certifiedCount = 0;
                        foreach($products as $p) {
                            if($p->is_certified) $certifiedCount++;
                        }
                        echo $certifiedCount;
                    @endphp
                </h3>
            </div>
            <div class="w-10 lg:w-12 h-10 lg:h-12 bg-primary-fixed rounded-full flex items-center justify-center text-primary flex-shrink-0">
                <span class="material-symbols-outlined text-lg lg:text-2xl" style="font-variation-settings: 'FILL' 1;">verified</span>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm border border-outline-variant/10">
        <div class="p-4 lg:p-6 border-b border-outline-variant/10">
            <h3 class="text-lg lg:text-xl font-bold text-primary">قائمة المنتجات</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right text-sm lg:text-base">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/10">
                        <th class="px-3 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant">اسم المنتج</th>
                        <th class="px-3 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant hidden sm:table-cell">الفئة</th>
                        <th class="px-3 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant hidden lg:table-cell">الوحدة</th>
                        <th class="px-3 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant hidden md:table-cell">الحد الأدنى</th>
                        <th class="px-3 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant hidden lg:table-cell">معتمد</th>
                        <th class="px-3 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant">الحالة</th>
                        <th class="px-3 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm font-bold text-on-surface-variant">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($products as $product)
                        <tr class="hover:bg-surface-container/50 transition-colors">
                            <td class="px-3 lg:px-6 py-3 lg:py-4">
                                <div class="flex items-center gap-2 lg:gap-3 flex-row-reverse">
                                    @if($product->images && $product->images->first())
                                        @php
                                            $imageUrl = $product->images->first()->asset_url;
                                        @endphp
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-8 lg:w-10 h-8 lg:h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-8 lg:w-10 h-8 lg:h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined text-base lg:text-lg">image</span>
                                        </div>
                                    @endif
                                    <div class="text-right">
                                        <p class="font-bold text-xs lg:text-sm text-on-surface">{{ substr($product->name, 0, 20) }}</p>
                                        <p class="text-[10px] lg:text-xs text-on-surface-variant">{{ $product->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm hidden sm:table-cell">
                                {{ substr($product->category?->name ?? 'بدون', 0, 15) }}
                            </td>
                            <td class="px-3 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm font-bold hidden lg:table-cell">
                                {{ $product->unit ?? '-' }}
                            </td>
                            <td class="px-3 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm font-bold hidden md:table-cell">
                                {{ $product->min_order_qty ?? 1 }}
                            </td>
                            <td class="px-3 lg:px-6 py-3 lg:py-4 hidden lg:table-cell">
                                @if($product->is_certified)
                                    <span class="inline-flex items-center gap-1 px-2 lg:px-3 py-1 bg-primary-fixed text-primary rounded-full text-[10px] lg:text-xs font-bold">
                                        <span class="material-symbols-outlined text-xs lg:text-sm" style="font-variation-settings: 'FILL' 1">verified</span>
                                        <span class="hidden lg:inline">معتمد</span>
                                    </span>
                                @else
                                    <span class="text-xs text-on-surface-variant">-</span>
                                @endif
                            </td>
                            <td class="px-3 lg:px-6 py-3 lg:py-4">
                                <span class="inline-block px-2 lg:px-3 py-1 rounded-full text-[10px] lg:text-xs font-bold
                                    @if($product->status === 'active') bg-primary-fixed text-primary
                                    @else bg-surface-container text-on-surface-variant
                                    @endif
                                ">
                                    @if($product->status === 'active')
                                        نشط
                                    @else
                                        غير نشط
                                    @endif
                                </span>
                            </td>
                            <td class="px-3 lg:px-6 py-3 lg:py-4 flex gap-1 lg:gap-2 flex-row-reverse">
                                <button type="button" onclick="editProduct({{ $product->id }}, {{ json_encode($product) }})" class="p-1.5 lg:p-2 text-on-surface-variant hover:text-primary hover:bg-surface-container rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-base lg:text-lg">edit</span>
                                </button>
                                <button type="button" onclick="handleDeleteProduct(event, {{ $product->id }})" class="p-1.5 lg:p-2 text-on-surface-variant hover:text-error hover:bg-error-container/20 rounded-lg transition-colors">
                                    <span class="material-symbols-outlined text-base lg:text-lg">delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 lg:px-6 py-8 lg:py-12 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="material-symbols-outlined text-4xl lg:text-5xl opacity-30">inbox</span>
                                    <p class="text-sm lg:text-base">لا توجد منتجات حتى الآن</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($products instanceof \Illuminate\Pagination\Paginator)
        <div class="flex justify-center">
            {{ $products->links() }}
        </div>
    @endif
</div>

<script>
    function handleDeleteProduct(event, productId) {
        event.preventDefault();
        event.stopPropagation();
        
        console.log('handleDeleteProduct called with ID:', productId, 'Type:', typeof productId);
        
        if (!productId) {
            alert('خطأ: لم يتم العثور على معرف المنتج.');
            return;
        }
        
        if (!confirm('هل أنت متأكد من حذف هذا المنتج؟ لا يمكن التراجع عن هذا الإجراء.')) {
            return;
        }
        
        // Create and submit a hidden form for DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/products/${productId}`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken.content;
            form.appendChild(tokenInput);
        }
        
        // Add method spoofing for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        console.log('Submitting form to:', form.action);
        
        document.body.appendChild(form);
        form.submit();
    }
</script>

<x-product-modal />

@endsection
