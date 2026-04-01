@extends('layouts.vendor')

@section('title', 'التحليلات - نيل هارفست')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-7xl mx-auto w-full space-y-6 md:space-y-12 pb-20">
    <section>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary mb-2">التحليلات</h2>
        <p class="text-on-surface-variant text-sm">إحصائيات وتقارير أداء متجرك</p>
    </section>

    <!-- Analytics Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Trend -->
        <div class="bg-surface-container-lowest rounded-lg md:rounded-2xl p-6 border border-outline-variant/10">
            <h3 class="text-lg md:text-xl font-bold text-primary mb-6 font-headline">اتجاه المبيعات</h3>
            <div class="h-64 flex items-end gap-3 justify-between">
                @for($i = 1; $i <= 7; $i++)
                    <div class="flex-1 bg-primary/{{ 10 + $i * 5 }} rounded-t-lg hover:bg-primary/30 transition-all h-[{{ 30 + $i * 8 }}%]"></div>
                @endfor
            </div>
            <div class="flex justify-between text-xs text-on-surface-variant mt-4 border-t border-outline-variant/10 pt-4">
                <span>السبت</span>
                <span>الأحد</span>
                <span>الاثنين</span>
                <span>الثلاثاء</span>
                <span>الأربعاء</span>
                <span>الخميس</span>
                <span>الجمعة</span>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-surface-container-lowest rounded-lg md:rounded-2xl p-6 border border-outline-variant/10">
            <h3 class="text-lg md:text-xl font-bold text-primary mb-6 font-headline">أفضل المنتجات</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-4 border-b border-outline-variant/10">
                    <span class="text-sm text-on-surface">بصل أحمر</span>
                    <div class="flex items-center gap-3">
                        <div class="w-24 h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full w-4/5 bg-primary rounded-full"></div>
                        </div>
                        <span class="text-xs font-bold text-primary">80%</span>
                    </div>
                </div>
                <div class="flex items-center justify-between pb-4 border-b border-outline-variant/10">
                    <span class="text-sm text-on-surface">بطاطس</span>
                    <div class="flex items-center gap-3">
                        <div class="w-24 h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full w-3/5 bg-primary rounded-full"></div>
                        </div>
                        <span class="text-xs font-bold text-primary">65%</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-on-surface">طماطم</span>
                    <div class="flex items-center gap-3">
                        <div class="w-24 h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full w-2/5 bg-primary rounded-full"></div>
                        </div>
                        <span class="text-xs font-bold text-primary">45%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-surface-container-lowest p-4 sm:p-6 rounded-lg border border-outline-variant/10">
            <p class="text-on-surface-variant text-xs sm:text-sm mb-2">متوسط قيمة الطلب</p>
            <p class="text-2xl sm:text-3xl font-bold text-primary">1,250 EGP</p>
        </div>
        <div class="bg-surface-container-lowest p-4 sm:p-6 rounded-lg border border-outline-variant/10">
            <p class="text-on-surface-variant text-xs sm:text-sm mb-2">إجمالي الطلبات</p>
            <p class="text-2xl sm:text-3xl font-bold text-primary">24</p>
        </div>
        <div class="bg-surface-container-lowest p-4 sm:p-6 rounded-lg border border-outline-variant/10">
            <p class="text-on-surface-variant text-xs sm:text-sm mb-2">معدل التحويل</p>
            <p class="text-2xl sm:text-3xl font-bold text-primary">4.2%</p>
        </div>
        <div class="bg-surface-container-lowest p-4 sm:p-6 rounded-lg border border-outline-variant/10">
            <p class="text-on-surface-variant text-xs sm:text-sm mb-2">رضا العملاء</p>
            <p class="text-2xl sm:text-3xl font-bold text-primary">4.8/5</p>
        </div>
    </div>
</main>
@endsection
