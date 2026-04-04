@extends('layouts.vendor')

@section('title', 'إدارة المنتجات - حصاد')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6 md:space-y-12 pb-20">
    <section class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary">منتجاتي</h2>
            <p class="text-on-surface-variant text-sm">إدارة وتحديث منتجاتك</p>
        </div>
        <a href="#" class="px-4 sm:px-6 py-2 sm:py-3 bg-primary text-on-primary rounded-lg font-bold flex items-center gap-2 hover:opacity-90 transition-opacity w-fit">
            <span class="material-symbols-outlined">add</span>
            <span>إضافة منتج جديد</span>
        </a>
    </section>

    <!-- Products Grid -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($products as $product)
            <div class="bg-surface-container-lowest rounded-lg overflow-hidden border border-outline-variant/10 hover:shadow-lg transition-shadow">
                <div class="aspect-video bg-surface-container overflow-hidden relative">
                    @if($product->images->first())
                        <img src="{{ $product->images->first()->asset_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant">image</span>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-base text-on-surface truncate">{{ $product->name }}</h3>
                    <p class="text-xs text-on-surface-variant mb-3">{{ $product->category->name ?? 'غير مصنف' }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-primary">{{ number_format($product->price) }} EGP</span>
                        <div class="flex gap-2">
                            <a href="#" class="p-2 hover:bg-surface-container rounded transition-colors">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                            <a href="#" class="p-2 hover:bg-surface-container rounded transition-colors">
                                <span class="material-symbols-outlined text-sm text-error">delete</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-on-surface-variant">لا توجد منتجات حتى الآن</p>
            </div>
        @endforelse
    </section>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="flex justify-center">
            {{ $products->links() }}
        </div>
    @endif
</main>
@endsection
