{{-- resources/views/admin/delivery-agents/show.blade.php --}}
@extends('layouts.admin')

@section('title', $agent->name)

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.delivery-agents.index') }}" class="text-primary hover:text-primary/80 flex items-center gap-1 mb-4">
            <span class="material-symbols-outlined">arrow_back</span>
            عودة للقائمة
        </a>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-on-surface">{{ $agent->name }}</h1>
                <p class="text-on-surface-variant text-sm mt-1">معرّف: #{{ $agent->id }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.delivery-agents.edit', $agent) }}" class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-bold hover:opacity-90 transition">
                    <span class="material-symbols-outlined text-lg">edit</span>
                    تعديل
                </a>
                <form action="{{ route('admin.delivery-agents.destroy', $agent) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-error text-on-error rounded-lg font-bold hover:opacity-90 transition" onclick="return confirm('هل أنت متأكد؟')">
                        <span class="material-symbols-outlined text-lg">delete</span>
                        حذف
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Status Card --}}
    <div class="bg-surface-bright rounded-lg border border-outline p-6 mb-6">
        <div class="grid grid-cols-4 gap-4">
            {{-- Status Badge --}}
            <div>
                <p class="text-on-surface-variant text-xs mb-1">الحالة الحالية</p>
                <div class="flex items-center gap-2">
                    @if($agent->status === 'active')
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-success/10 text-success rounded-full text-sm font-bold">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                            نشط
                        </span>
                    @elseif($agent->status === 'inactive')
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-error/10 text-error rounded-full text-sm font-bold">
                            <span class="material-symbols-outlined text-lg">cancel</span>
                            غير نشط
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-warning/10 text-warning rounded-full text-sm font-bold">
                            <span class="material-symbols-outlined text-lg">schedule</span>
                            في إجازة
                        </span>
                    @endif
                </div>
            </div>

            {{-- Availability --}}
            <div>
                <p class="text-on-surface-variant text-xs mb-2">الحالة</p>
                <div class="flex items-center gap-2">
                    @if($agent->isAvailable())
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-success/10 text-success rounded-full text-sm font-bold">
                            <span class="material-symbols-outlined text-lg">thumb_up</span>
                            متاح
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-warning/10 text-warning rounded-full text-sm font-bold">
                            <span class="material-symbols-outlined text-lg">busy</span>
                            مشغول
                        </span>
                    @endif
                </div>
            </div>

            {{-- Hire Date --}}
            <div>
                <p class="text-on-surface-variant text-xs mb-1">تاريخ التوظيف</p>
                <p class="text-on-surface font-bold">{{ $agent->hire_date->format('Y-m-d') }}</p>
            </div>

            {{-- Vehicle Type --}}
            <div>
                <p class="text-on-surface-variant text-xs mb-1">نوع المركبة</p>
                <p class="text-on-surface font-bold capitalize">
                    @switch($agent->vehicle_type)
                        @case('car')
                            سيارة
                            @break
                        @case('motorcycle')
                            دراجة نارية
                            @break
                        @case('bicycle')
                            دراجة
                            @break
                        @case('van')
                            فان
                            @break
                    @endswitch
                </p>
            </div>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        @php
            $stats = $agent->getStatistics();
        @endphp

        {{-- Total Deliveries --}}
        <div class="bg-surface-bright rounded-lg border border-outline p-4">
            <p class="text-on-surface-variant text-xs mb-2">إجمالي التوصيلات</p>
            <p class="text-3xl font-bold text-primary">{{ $stats['total_deliveries'] }}</p>
        </div>

        {{-- Successful Deliveries --}}
        <div class="bg-surface-bright rounded-lg border border-outline p-4">
            <p class="text-on-surface-variant text-xs mb-2">التوصيلات الناجحة</p>
            <p class="text-3xl font-bold text-success">{{ $stats['successful_deliveries'] }}</p>
        </div>

        {{-- Failed Deliveries --}}
        <div class="bg-surface-bright rounded-lg border border-outline p-4">
            <p class="text-on-surface-variant text-xs mb-2">التوصيلات الفاشلة</p>
            <p class="text-3xl font-bold text-error">{{ $stats['failed_deliveries'] }}</p>
        </div>

        {{-- Success Rate --}}
        <div class="bg-surface-bright rounded-lg border border-outline p-4">
            <p class="text-on-surface-variant text-xs mb-2">معدل النجاح</p>
            <p class="text-3xl font-bold text-primary">{{ $stats['success_rate'] }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        {{-- Personal Information --}}
        <div class="bg-surface-bright rounded-lg border border-outline p-6">
            <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span>
                البيانات الشخصية
            </h2>

            <div class="space-y-3">
                <div>
                    <p class="text-on-surface-variant text-xs">الاسم الكامل</p>
                    <p class="text-on-surface font-bold">{{ $agent->name }}</p>
                </div>
                <div>
                    <p class="text-on-surface-variant text-xs">رقم الهاتف</p>
                    <p class="text-on-surface font-bold" dir="ltr">{{ $agent->phone }}</p>
                </div>
                <div>
                    <p class="text-on-surface-variant text-xs">البريد الإلكتروني</p>
                    <p class="text-on-surface font-bold" dir="ltr">{{ $agent->email }}</p>
                </div>
                <div>
                    <p class="text-on-surface-variant text-xs">رقم الهوية</p>
                    <p class="text-on-surface font-bold">{{ $agent->id_number ?? 'غير محدد' }}</p>
                </div>
            </div>
        </div>

        {{-- Address Information --}}
        <div class="bg-surface-bright rounded-lg border border-outline p-6">
            <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">location_on</span>
                البيانات الجغرافية
            </h2>

            <div class="space-y-3">
                <div>
                    <p class="text-on-surface-variant text-xs">المحافظة</p>
                    <p class="text-on-surface font-bold">{{ $agent->governorate }}</p>
                </div>
                <div>
                    <p class="text-on-surface-variant text-xs">العنوان التفصيلي</p>
                    <p class="text-on-surface font-bold">{{ $agent->address }}</p>
                </div>
            </div>
        </div>

        {{-- Vehicle Information --}}
        <div class="bg-surface-bright rounded-lg border border-outline p-6">
            <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">local_shipping</span>
                بيانات المركبة
            </h2>

            <div class="space-y-3">
                <div>
                    <p class="text-on-surface-variant text-xs">نوع المركبة</p>
                    <p class="text-on-surface font-bold capitalize">
                        @switch($agent->vehicle_type)
                            @case('car')
                                سيارة
                                @break
                            @case('motorcycle')
                                دراجة نارية
                                @break
                            @case('bicycle')
                                دراجة
                                @break
                            @case('van')
                                فان
                                @break
                        @endswitch
                    </p>
                </div>
                <div>
                    <p class="text-on-surface-variant text-xs">رقم اللوحة</p>
                    <p class="text-on-surface font-bold" dir="ltr">{{ $agent->license_plate ?? 'غير محدد' }}</p>
                </div>
            </div>
        </div>

        {{-- Payment Information --}}
        <div class="bg-surface-bright rounded-lg border border-outline p-6">
            <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">payment</span>
                بيانات الدفع
            </h2>

            <div class="space-y-3">
                <div>
                    <p class="text-on-surface-variant text-xs">نوع الراتب</p>
                    <p class="text-on-surface font-bold">
                        @switch($agent->salary_type)
                            @case('fixed')
                                راتب ثابت
                                @break
                            @case('commission')
                                عمولة
                                @break
                            @case('hybrid')
                                راتب + عمولة
                                @break
                        @endswitch
                    </p>
                </div>
                <div>
                    <p class="text-on-surface-variant text-xs">نسبة العمولة</p>
                    <p class="text-on-surface font-bold">{{ $agent->commission_rate }}%</p>
                </div>
                <div>
                    <p class="text-on-surface-variant text-xs">رقم الحساب البنكي</p>
                    <p class="text-on-surface font-bold" dir="ltr">{{ $agent->bank_account ?? 'غير محدد' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    @if($agent->notes)
    <div class="bg-surface-bright rounded-lg border border-outline p-6 mb-6">
        <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">note</span>
            ملاحظات
        </h2>
        <p class="text-on-surface">{{ $agent->notes }}</p>
    </div>
    @endif

    {{-- Active Assignments --}}
    @php
        $activeAssignments = $agent->activeAssignments()->with('order')->recent()->get();
    @endphp
    @if($activeAssignments->isNotEmpty())
    <div class="bg-surface-bright rounded-lg border border-outline p-6 mb-6">
        <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">local_shipping</span>
            التوصيلات النشطة ({{ $activeAssignments->count() }})
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-outline">
                        <th class="text-right py-3 px-4 font-bold text-on-surface">#الطلب</th>
                        <th class="text-right py-3 px-4 font-bold text-on-surface">العميل</th>
                        <th class="text-right py-3 px-4 font-bold text-on-surface">المبلغ</th>
                        <th class="text-right py-3 px-4 font-bold text-on-surface">الحالة</th>
                        <th class="text-right py-3 px-4 font-bold text-on-surface">تاريخ الإسناد</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeAssignments as $assignment)
                    <tr class="border-b border-outline hover:bg-surface-container transition">
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.orders.show', $assignment->order) }}" class="text-primary font-bold hover:underline">
                                #{{ $assignment->order->order_number }}
                            </a>
                        </td>
                        <td class="py-3 px-4">{{ $assignment->order->customer_name }}</td>
                        <td class="py-3 px-4" dir="ltr">{{ number_format($assignment->order->total_amount, 2) }} EGP</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold
                                @switch($assignment->delivery_status)
                                    @case('assigned')
                                        bg-warning/10 text-warning
                                        @break
                                    @case('in_transit')
                                        bg-info/10 text-info
                                        @break
                                    @case('arrived')
                                        bg-primary/10 text-primary
                                        @break
                                    @default
                                        bg-surface-container text-on-surface
                                @endswitch
                            ">
                                {{ $assignment->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="py-3 px-4">{{ $assignment->assigned_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Delivery History --}}
    @php
        $completedAssignments = $agent->deliveryAssignments()
            ->whereIn('delivery_status', ['delivered', 'failed'])
            ->with('order')
            ->orderByDesc('completed_at')
            ->paginate(10);
    @endphp
    <div class="bg-surface-bright rounded-lg border border-outline p-6">
        <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">history</span>
            سجل التوصيلات
        </h2>

        @if($completedAssignments->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-outline">
                            <th class="text-right py-3 px-4 font-bold text-on-surface">#الطلب</th>
                            <th class="text-right py-3 px-4 font-bold text-on-surface">العميل</th>
                            <th class="text-right py-3 px-4 font-bold text-on-surface">المبلغ</th>
                            <th class="text-right py-3 px-4 font-bold text-on-surface">الحالة</th>
                            <th class="text-right py-3 px-4 font-bold text-on-surface">وقت التوصيل</th>
                            <th class="text-right py-3 px-4 font-bold text-on-surface">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedAssignments as $assignment)
                        <tr class="border-b border-outline hover:bg-surface-container transition">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.orders.show', $assignment->order) }}" class="text-primary font-bold hover:underline">
                                    #{{ $assignment->order->order_number }}
                                </a>
                            </td>
                            <td class="py-3 px-4">{{ $assignment->order->customer_name }}</td>
                            <td class="py-3 px-4" dir="ltr">{{ number_format($assignment->order->total_amount, 2) }} EGP</td>
                            <td class="py-3 px-4">
                                @if($assignment->delivery_status === 'delivered')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-success/10 text-success">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                        تم التوصيل
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-error/10 text-error">
                                        <span class="material-symbols-outlined text-sm">cancel</span>
                                        فشل
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($assignment->getDeliveryTimeInMinutes())
                                    {{ $assignment->getDeliveryTimeInMinutes() }} دقيقة
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.orders.show', $assignment->order) }}" class="text-primary hover:underline text-sm font-bold">
                                    عرض
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $completedAssignments->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant opacity-50 block mb-2">history</span>
                <p class="text-on-surface-variant">لا توجد توصيلات مكتملة حتى الآن</p>
            </div>
        @endif
    </div>
</div>
@endsection
