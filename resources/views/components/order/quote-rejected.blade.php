@props([
    'orderNumber' => '',
    'orderNumberDisplay' => '',
    'statusBadge' => 'تم رفض عرض السعر — بانتظار عرض جديد',
    'rejectionMessage' => 'تم رفض العرض',
    'rejectionDescription' => '',
    'products' => [],
    'rejectionNotes' => '',
    'trackingSteps' => [],
    'helpTitle' => 'هل تحتاج للمساعدة؟',
    'helpDescription' => '',
    'helpButtonText' => 'تحدث مع المستشار',
    'helpButtonUrl' => '#',
])

<main class="pt-28 pb-24 lg:pr-72 px-4 md:px-8 max-w-7xl mx-auto">
    {{-- Header Section with Status Badge --}}
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div>
            <nav class="flex gap-2 text-sm text-on-surface-variant mb-4 items-center">
                <span>عروض الأسعار</span>
                <span class="material-symbols-outlined text-xs">arrow_back_ios</span>
                <span class="text-primary font-bold">{{ $orderNumberDisplay }}</span>
            </nav>
            <h1 class="text-4xl font-headline font-black text-on-surface tracking-tight">تفاصيل عرض السعر</h1>
        </div>
        
        {{-- Status Badge --}}
        <div class="flex items-center gap-3 bg-error-container text-on-error-container px-4 py-2 rounded-full font-bold text-sm">
            <span class="material-symbols-outlined text-lg">error_outline</span>
            <span>{{ $statusBadge }}</span>
        </div>
    </header>

    {{-- Bento Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Rejection Alert & Products --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Rejection Alert Card --}}
            <x-order.rejection-alert-card 
                :message="$rejectionMessage"
                :description="$rejectionDescription"
                :showProgress="true"
            />

            {{-- Product Preview Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($products as $product)
                    <x-order.rejection-product-card
                        :name="$product['name'] ?? ''"
                        :quantity="$product['quantity'] ?? ''"
                        :image="$product['image'] ?? null"
                        :badge="$product['badge'] ?? 'جودة ممتازة'"
                        :oldPrice="$product['old_price'] ?? null"
                        :currency="$product['currency'] ?? 'ج.م'"
                    />
                @empty
                    {{-- Empty State --}}
                    <div class="bg-surface-container-low p-6 rounded-xl text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl text-on-surface-variant/40">inventory_2</span>
                        <p class="mt-2 text-sm">لا توجد منتجات</p>
                    </div>
                @endforelse

                {{-- Rejection Notes --}}
                @if($rejectionNotes)
                <x-order.rejection-notes 
                    :notes="$rejectionNotes"
                />
                @endif
            </div>
        </div>

        {{-- Right Column: Timeline & Help --}}
        <div class="space-y-8">
            {{-- Order Timeline --}}
            @if(!empty($trackingSteps))
            <x-order.order-tracking-timeline :steps="$trackingSteps" />
            @endif

            {{-- Help Contact Card --}}
            <x-order.help-contact-card
                :title="$helpTitle"
                :description="$helpDescription"
                :buttonText="$helpButtonText"
                :buttonUrl="$helpButtonUrl"
                :variant="'secondary'"
            />
        </div>
    </div>
</main>

{{ $slot }}
