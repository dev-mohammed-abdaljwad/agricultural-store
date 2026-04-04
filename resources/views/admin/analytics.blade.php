@extends('layouts.admin')

@section('title', 'التحليلات - حصاد')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6 pb-20">
    <section>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary mb-2">التحليلات</h2>
        <p class="text-on-surface-variant text-sm">إحصائيات وتقارير الأداء</p>
    </section>

    <!-- Sales Chart -->
    <section class="bg-surface-container-lowest p-6 rounded-lg border border-outline-variant/10">
        <h3 class="text-lg font-black text-primary mb-6">مبيعات الشهر الحالي</h3>
        <div class="h-64 flex items-end gap-2">
            @for($i = 0; $i < 7; $i++)
                <div class="flex-1 bg-primary/{{ rand(20, 80) }} rounded-t-lg hover:bg-primary/40 transition-all" style="height: {{ rand(20, 100) }}%"></div>
            @endfor
        </div>
    </section>

    <!-- Top Products -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-surface-container-lowest p-6 rounded-lg border border-outline-variant/10">
            <h3 class="text-lg font-black text-primary mb-4">أفضل المنتجات</h3>
            <div class="space-y-3">
                @foreach($topProducts as $product)
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <p class="font-bold text-sm">{{ $product->name }}</p>
                            <div class="w-full bg-surface-container h-2 rounded-full mt-1">
                                <div class="bg-primary h-full rounded-full" style="width: {{ ($product->order_items_count / ($topProducts->first()?->order_items_count ?? 1)) * 100 }}%"></div>
                            </div>
                        </div>
                        <p class="text-sm font-bold text-on-surface-variant">{{ $product->order_items_count }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Vendor Performance -->
        <div class="bg-surface-container-lowest p-6 rounded-lg border border-outline-variant/10">
            <h3 class="text-lg font-black text-primary mb-4">أفضل التجار</h3>
            <div class="space-y-3">
                @foreach($vendorStats as $vendor)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-sm">
                            {{ substr($vendor->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-sm">{{ $vendor->name }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $vendor->orders_count }} طلب</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
