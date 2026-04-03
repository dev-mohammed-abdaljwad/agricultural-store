@extends('layouts.admin')

@section('title', 'تفاصيل الطلب - نيل هارفست')

@section('content')
<main class="p-4 sm:p-6 md:p-8 max-w-6xl mx-auto w-full space-y-6 pb-20">
    <!-- Header with Back Button -->
    <section class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-primary hover:underline text-sm font-medium">← العودة</a>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-headline text-primary">{{ $order->order_number }}</h2>
    </section>

    <!-- Success Messages -->
    @if ($message = Session::get('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg text-sm">
            {{ $message }}
        </div>
    @endif

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Summary Card -->
            <section class="bg-surface-container-lowest rounded-lg p-4 sm:p-6 border border-outline-variant/10">
                <h3 class="text-lg font-bold text-on-surface mb-4">ملخص الطلب</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant/15">
                        <span class="text-on-surface-variant">حالة الطلب:</span>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold 
                            @if($order->status === 'placed') bg-blue-100 text-blue-800
                            @elseif($order->status === 'quote_sent') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'quote_accepted') bg-green-100 text-green-800
                            @elseif($order->status === 'rejected') bg-red-100 text-red-800
                            @elseif($order->status === 'cancelled') bg-gray-100 text-gray-800
                            @else bg-surface-container text-on-surface-variant
                            @endif">
                            {{ $statuses[$order->status] ?? $order->status }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant/15">
                        <span class="text-on-surface-variant">التاريخ:</span>
                        <span class="font-medium text-on-surface">{{ $order->created_at->format('d M Y - H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant/15">
                        <span class="text-on-surface-variant">إجمالي المبلغ:</span>
                        <span class="font-bold text-primary text-lg">{{ number_format($order->total_amount ?? 0, 2) }} EGP</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">طريقة الدفع:</span>
                        <span class="font-medium text-on-surface capitalize">{{ $order->payment_method ?? 'غير محدد' }}</span>
                    </div>
                </div>
            </section>

            <!-- Customer Information -->
            <section class="bg-surface-container-lowest rounded-lg p-4 sm:p-6 border border-outline-variant/10">
                <h3 class="text-lg font-bold text-on-surface mb-4">معلومات العميل</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant/15">
                        <span class="text-on-surface-variant">الاسم:</span>
                        <span class="font-medium text-on-surface">{{ $order->customer->name }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant/15">
                        <span class="text-on-surface-variant">البريد الإلكتروني:</span>
                        <span class="font-medium text-on-surface">{{ $order->customer->email }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">الهاتف:</span>
                        <span class="font-medium text-on-surface">{{ $order->customer->phone ?? 'غير محدد' }}</span>
                    </div>
                </div>
            </section>

            <!-- Order Items -->
            <section class="bg-surface-container-lowest rounded-lg p-4 sm:p-6 border border-outline-variant/10">
                <h3 class="text-lg font-bold text-on-surface mb-4">المنتجات</h3>
                
                <div class="space-y-2">
                    @forelse($order->items as $item)
                        <div class="flex justify-between items-center py-3 border-b border-outline-variant/15 last:border-b-0">
                            <div class="flex-1">
                                <p class="font-medium text-on-surface">{{ $item->product->name }}</p>
                                <p class="text-xs text-on-surface-variant">الكمية: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-bold text-primary">{{ number_format($item->unit_price * $item->quantity, 2) }} EGP</span>
                        </div>
                    @empty
                        <p class="text-center text-on-surface-variant py-4">لا توجد منتجات</p>
                    @endforelse
                </div>
            </section>

            <!-- Delivery Information -->
            <section class="bg-surface-container-lowest rounded-lg p-4 sm:p-6 border border-outline-variant/10">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-on-surface">معلومات التوصيل</h3>
                    <button 
                        onclick="document.getElementById('deliveryModal').showModal()"
                        class="text-primary hover:underline text-sm font-medium">
                        تعديل
                    </button>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-start pb-3 border-b border-outline-variant/15">
                        <span class="text-on-surface-variant">العنوان:</span>
                        <span class="font-medium text-on-surface text-left">{{ $order->delivery_address ?? 'غير محدد' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-3 border-b border-outline-variant/15">
                        <span class="text-on-surface-variant">المحافظة:</span>
                        <span class="font-medium text-on-surface">{{ $order->delivery_governorate ?? 'غير محدد' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant">رقم التعصيل:</span>
                        <span class="font-medium text-on-surface">{{ $order->delivery_phone ?? 'غير محدد' }}</span>
                    </div>
                </div>
            </section>

            <!-- Order Tracking Timeline -->
            <section class="bg-surface-container-lowest rounded-lg p-4 sm:p-6 border border-outline-variant/10">
                <h3 class="text-lg font-bold text-on-surface mb-4">سجل المتابعة</h3>
                
                <div class="space-y-4">
                    @forelse($tracking as $event)
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 bg-primary rounded-full"></div>
                                <div class="w-0.5 h-12 bg-outline-variant/30 my-2"></div>
                            </div>
                            <div class="pb-4 last:pb-0">
                                <p class="font-bold text-on-surface">{{ $event->title }}</p>
                                <p class="text-sm text-on-surface-variant">{{ $event->description }}</p>
                                <p class="text-xs text-on-surface-variant/70 mt-1">{{ $event->occurred_at->format('d M Y - H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-on-surface-variant py-4">لا توجد تحديثات</p>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- Right Column - Actions & Status Management -->
        <div class="space-y-6">
            <!-- Status Management -->
            <section class="bg-surface-container-lowest rounded-lg p-4 sm:p-6 border border-outline-variant/10">
                <h3 class="text-lg font-bold text-on-surface mb-4">تحديث الحالة</h3>
                
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-2">الحالة الجديدة:</label>
                        <select name="status" required class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-on-surface mb-2">ملاحظات:</label>
                        <textarea 
                            name="notes" 
                            rows="3" 
                            placeholder="أضف ملاحظات إضافية"
                            class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright">
                        </textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-on-primary py-2 rounded-lg font-medium hover:bg-primary/90 transition-colors text-sm">
                        تحديث الحالة
                    </button>
                </form>
            </section>

            <!-- Quick Actions -->
            <section class="bg-surface-container-lowest rounded-lg p-4 sm:p-6 border border-outline-variant/10">
                <h3 class="text-lg font-bold text-on-surface mb-4">إجراءات سريعة</h3>
                
                <div class="space-y-2">
                    @if($order->status === 'placed' && !$order->quotes()->where('status', 'pending')->exists())
                        <a 
                            href="{{ route('admin.quotes.create', $order) }}"
                            class="block w-full bg-secondary text-on-secondary py-2 rounded-lg font-medium hover:bg-secondary/90 transition-colors text-sm text-center">
                            إنشاء عرض سعر
                        </a>
                    @endif

                    @if($order->activeQuote)
                        <a 
                            href="{{ route('admin.quotes.show', $order->activeQuote) }}"
                            class="block w-full bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors text-sm text-center">
                            عرض العرض الحالي
                        </a>
                    @endif
                    
                    <button 
                        onclick="document.getElementById('messageModal').showModal()"
                        class="w-full bg-outline text-on-surface py-2 rounded-lg font-medium hover:bg-outline/90 transition-colors text-sm">
                        إرسال رسالة
                    </button>
                    
                    <button 
                        onclick="document.getElementById('cancelModal').showModal()"
                        class="w-full bg-error/10 text-error py-2 rounded-lg font-medium hover:bg-error/20 transition-colors text-sm">
                        إلغاء الطلب
                    </button>
                </div>
            </section>

            <!-- Admin Notes -->
            <section class="bg-surface-container-lowest rounded-lg p-4 sm:p-6 border border-outline-variant/10">
                <h3 class="text-lg font-bold text-on-surface mb-3">ملاحظات المسؤول</h3>
                <div class="p-3 bg-on-primary/5 rounded border-r-4 border-primary text-sm text-on-surface min-h-20">
                    {{ $order->admin_notes ?? 'لا توجد ملاحظات' }}
                </div>
            </section>
        </div>
    </div>

    <!-- Messages Section -->
    @if($messages->count() > 0)
        <section class="bg-surface-container-lowest rounded-lg p-4 sm:p-6 border border-outline-variant/10">
            <h3 class="text-lg font-bold text-on-surface mb-4">الرسائل</h3>
            
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($messages as $message)
                    <div class="p-3 bg-surface-bright rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-medium text-sm text-primary">
                                @if($message->sender_type === 'admin')
                                    إدارة
                                @else
                                    {{ $message->sender->name ?? 'عميل' }}
                                @endif
                            </span>
                            <span class="text-xs text-on-surface-variant">{{ $message->created_at->format('d M - H:i') }}</span>
                        </div>
                        <p class="text-sm text-on-surface">{{ $message->content }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</main>

<!-- Modals -->

<!-- Delivery Update Modal -->
<dialog id="deliveryModal" class="rounded-lg backdrop:bg-black/50 p-0 w-full max-w-md max-h-screen overflow-y-auto">
    <form method="POST" action="{{ route('admin.orders.updateDelivery', $order) }}" class="p-6 space-y-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-on-surface">تحديث معلومات التوصيل</h3>
            <button type="button" onclick="document.getElementById('deliveryModal').close()" class="text-on-surface-variant hover:text-on-surface">✕</button>
        </div>
        
        @csrf
        @method('PATCH')
        
        <div>
            <label class="block text-sm font-medium text-on-surface mb-2">العنوان:</label>
            <textarea 
                name="delivery_address" 
                required 
                class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright"
                rows="3">{{ $order->delivery_address }}</textarea>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-on-surface mb-2">المحافظة:</label>
            <input 
                type="text" 
                name="delivery_governorate" 
                required 
                value="{{ $order->delivery_governorate }}"
                class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-on-surface mb-2">رقم الهاتف:</label>
            <input 
                type="tel" 
                name="delivery_phone" 
                required 
                pattern="^(01|002)[0-9]{9}$"
                value="{{ $order->delivery_phone }}"
                placeholder="20101234567 أو 01001234567"
                class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright">
        </div>
        
        <div class="flex gap-2 mt-6">
            <button type="submit" class="flex-1 bg-primary text-on-primary py-2 rounded-lg font-medium hover:bg-primary/90 transition-colors text-sm">
                تحديث
            </button>
            <button type="button" onclick="document.getElementById('deliveryModal').close()" class="flex-1 bg-outline text-on-surface py-2 rounded-lg font-medium hover:bg-outline/90 transition-colors text-sm">
                إلغاء
            </button>
        </div>
    </form>
</dialog>

<!-- Message Modal -->
<dialog id="messageModal" class="rounded-lg backdrop:bg-black/50 p-0 w-full max-w-md max-h-screen overflow-y-auto">
    <form method="POST" action="{{ route('admin.orders.notify', $order) }}" class="p-6 space-y-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-on-surface">إرسال رسالة للعميل</h3>
            <button type="button" onclick="document.getElementById('messageModal').close()" class="text-on-surface-variant hover:text-on-surface">✕</button>
        </div>
        
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-on-surface mb-2">الرسالة:</label>
            <textarea 
                name="message" 
                required 
                minlength="10"
                placeholder="اكتب رسالة للعميل..."
                class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright"
                rows="4"></textarea>
        </div>
        
        <div class="flex gap-2 mt-6">
            <button type="submit" class="flex-1 bg-primary text-on-primary py-2 rounded-lg font-medium hover:bg-primary/90 transition-colors text-sm">
                إرسال
            </button>
            <button type="button" onclick="document.getElementById('messageModal').close()" class="flex-1 bg-outline text-on-surface py-2 rounded-lg font-medium hover:bg-outline/90 transition-colors text-sm">
                إلغاء
            </button>
        </div>
    </form>
</dialog>

<!-- Cancel Modal -->
<dialog id="cancelModal" class="rounded-lg backdrop:bg-black/50 p-0 w-full max-w-md max-h-screen overflow-y-auto">
    <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" class="p-6 space-y-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-on-surface">إلغاء الطلب</h3>
            <button type="button" onclick="document.getElementById('cancelModal').close()" class="text-on-surface-variant hover:text-on-surface">✕</button>
        </div>
        
        @csrf
        
        <div class="p-3 bg-error/10 rounded text-sm text-error">
            ⚠️ هذا الإجراء سيؤدي إلى إلغاء الطلب بشكل نهائي
        </div>
        
        <div>
            <label class="block text-sm font-medium text-on-surface mb-2">السبب:</label>
            <textarea 
                name="reason" 
                required 
                minlength="10"
                placeholder="أدخل سبب الإلغاء..."
                class="w-full px-3 py-2 border border-outline-variant rounded-lg focus:outline-none focus:border-primary text-sm text-on-surface bg-surface-bright"
                rows="3"></textarea>
        </div>
        
        <div class="flex gap-2 mt-6">
            <button type="submit" class="flex-1 bg-error text-on-error py-2 rounded-lg font-medium hover:bg-error/90 transition-colors text-sm">
                تأكيد الإلغاء
            </button>
            <button type="button" onclick="document.getElementById('cancelModal').close()" class="flex-1 bg-outline text-on-surface py-2 rounded-lg font-medium hover:bg-outline/90 transition-colors text-sm">
                إلغاء
            </button>
        </div>
    </form>
</dialog>

@endsection
