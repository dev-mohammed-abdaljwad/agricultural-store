@extends('layouts.customer')

@section('title', 'السوق الزراعي - حصاد')

@section('content')
<!-- Main Content -->
<main class="flex-1 p-8 lg:p-12 overflow-x-hidden">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-sm text-on-surface-variant mb-6 font-headline flex-row-reverse">
            <a class="hover:text-primary transition-colors" href="{{ route('home') }}">الرئيسية</a>
            <span class="material-symbols-outlined text-xs">chevron_left</span>
            <span class="text-primary font-medium">المتجر</span>
        </nav>
        
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-black text-primary font-headline mb-2">نتائج البحث</h1>
                <p class="text-on-surface-variant font-headline">اكتشف أجود المستلزمات الزراعية المصرية</p>
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="relative group">
                    <select class="appearance-none bg-surface-container-low border-none rounded-xl py-3 px-10 pr-4 focus:ring-2 focus:ring-primary text-sm font-headline cursor-pointer">
                        <option>الأكثر شيوعاً</option>
                        <option>وصل حديثاً</option>
                    </select>
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">swap_vert</span>
                </div>
            </div>
        </div>
        
        <!-- Filter Chips -->
        <div class="flex flex-wrap gap-3 mb-10">
            <a href="{{ route('products.index') }}"
               class="@if(!request('category')) bg-primary text-on-primary @else bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest @endif px-6 py-2 rounded-full font-headline font-medium text-sm transition-all">
                كل المنتجات
            </a>
            
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}"
                   class="@if(request('category') == $category->id) bg-primary text-on-primary @else bg-surface-container-high text-on-surface-variant hover:bg-surface-container-highest @endif px-6 py-2 rounded-full font-headline font-medium text-sm transition-all">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
        
        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-8">
            @forelse($products as $product)
                <div class="group bg-surface-container-lowest rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 p-4 flex flex-col">
                    <div class="relative aspect-square rounded-xl overflow-hidden mb-6">
                        @if($product->images->first())
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                                 alt="{{ $product->name }}"
                                 src="{{ $product->images->first()->asset_url }}" />
                        @else
                            <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
                                <span class="material-symbols-outlined text-6xl text-on-surface-variant">image</span>
                            </div>
                        @endif
                        
                        @if($product->is_certified)
                            <div class="absolute top-3 left-3 bg-primary-fixed text-on-primary-fixed-variant px-3 py-1 rounded-full text-xs font-bold font-headline shadow-sm">
                                منتج معتمد
                            </div>
                        @endif
                    </div>
                    <span class="text-on-surface-variant text-xs font-headline mb-1">{{ $product->category->name }}</span>
                    <h3 class="text-lg font-black text-primary font-headline mb-4 leading-tight">{{ $product->name }}</h3>
                    <div class="mt-auto">
                        <a href="{{ route('products.show', $product) }}"
                           class="w-full py-3 bg-primary-fixed text-on-primary-fixed font-bold rounded-xl font-headline transition-all active:scale-95 hover:bg-primary-fixed/90 text-center block">
                            اطلب عرض سعر
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <span class="material-symbols-outlined text-6xl text-on-surface-variant opacity-20 block mb-4">
                        packages
                    </span>
                    <p class="text-on-surface-variant text-lg font-medium">
                        عذراً، لم نجد منتجات تطابق بحثك
                    </p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-16 flex justify-center items-center gap-3">
                @if($products->onFirstPage())
                    <button disabled class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-container-high text-on-surface-variant opacity-50 cursor-not-allowed">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-container-high text-on-surface-variant hover:bg-primary hover:text-on-primary transition-all">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                @endif
                
                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if($page == $products->currentPage())
                        <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-primary text-on-primary font-bold">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container-high transition-all">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-container-high text-on-surface-variant hover:bg-primary hover:text-on-primary transition-all">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                @else
                    <button disabled class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-container-high text-on-surface-variant opacity-50 cursor-not-allowed">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                @endif
            </div>
        @endif
    </main>
@endsection
