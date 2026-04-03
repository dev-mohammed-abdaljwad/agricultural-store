{{-- Quote Sent: Main Page Component (Screen 4) --}}
@props(['orderNumber' => '', 'orderDate' => '', 'items' => [], 'subtotal' => 0, 'shipping' => 0, 'tax' => 0, 'total' => 0, 'acceptUrl' => '', 'rejectUrl' => '', 'trackingSteps' => [], 'messages' => []])

<main class="pt-28 pb-24 px-4 md:px-8 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 rtl">
    {{-- Left Sidebar --}}
    <x-order.quote-sidebar 
        :acceptUrl="$acceptUrl"
        :rejectUrl="$rejectUrl"
        :trackingSteps="$trackingSteps"
    />
    
    {{-- Main Content --}}
    <article class="lg:col-span-8 order-1 lg:order-2 space-y-6">
        {{-- Header & Badge --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="font-headline font-black text-3xl text-primary">تفاصيل الطلب #{{ $orderNumber }}</h1>
                <p class="text-on-surface-variant mt-1 font-body">تاريخ الطلب: {{ $orderDate }}</p>
            </div>
            
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-full font-bold text-sm border border-blue-100 animate-pulse-custom">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                عرض السعر بانتظار ردك
            </div>
        </div>
        
        {{-- Items Table --}}
        <x-order.quote-items-table 
            :items="$items"
            :subtotal="$subtotal"
            :shipping="$shipping"
            :tax="$tax"
            :total="$total"
        />
        
        {{-- Support Chat --}}
        <x-order.support-chat 
            :messages="$messages"
            supportAgentName="الدعم الفني واللوجستي"
        />
    </article>
</main>
