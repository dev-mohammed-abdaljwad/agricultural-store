{{-- resources/views/admin/delivery-agents/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'عمال التوصيل')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-on-surface mb-2">عمال التوصيل</h1>
                <p class="text-on-surface-variant">إدارة فريق التوصيل والتسليم</p>
            </div>
            <button onclick="openCreateModal()" class="bg-primary text-on-primary px-6 py-3 rounded-lg font-bold hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">add</span>
                إضافة عامل توصيل
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid md:grid-cols-4 gap-4 mb-8">
        <div class="bg-surface-bright p-4 rounded-lg border border-outline">
            <p class="text-on-surface-variant text-sm mb-2">إجمالي العمال</p>
            <p class="text-2xl font-bold text-primary">{{ $agents->total() }}</p>
        </div>
        <div class="bg-surface-bright p-4 rounded-lg border border-outline">
            <p class="text-on-surface-variant text-sm mb-2">نشطين</p>
            <p class="text-2xl font-bold text-success">{{ $agents->where('status', 'active')->count() }}</p>
        </div>
        <div class="bg-surface-bright p-4 rounded-lg border border-outline">
            <p class="text-on-surface-variant text-sm mb-2">غير نشطين</p>
            <p class="text-2xl font-bold text-error">{{ $agents->where('status', 'inactive')->count() }}</p>
        </div>
        <div class="bg-surface-bright p-4 rounded-lg border border-outline">
            <p class="text-on-surface-variant text-sm mb-2">في إجازة</p>
            <p class="text-2xl font-bold text-warning">{{ $agents->where('status', 'on_leave')->count() }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-surface-bright p-6 rounded-lg border border-outline mb-6">
        <form method="GET" action="{{ route('admin.delivery-agents.index') }}" class="flex gap-4 flex-wrap items-end">
            {{-- Search --}}
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-bold text-on-surface mb-2">بحث...</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم أو هاتف أو بريد" class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface">
            </div>

            {{-- Status Filter --}}
            <div class="w-48">
                <label class="block text-sm font-bold text-on-surface mb-2">الحالة</label>
                <select name="status" class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface">
                    <option value="all">الكل</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                </select>
            </div>

            {{-- Governorate Filter --}}
            <div class="w-48">
                <label class="block text-sm font-bold text-on-surface mb-2">المحافظة</label>
                <select name="governorate" class="w-full px-4 py-2 border border-outline rounded-lg bg-surface text-on-surface">
                    <option value="">الكل</option>
                    @foreach($existingGovernorates as $gov)
                        <option value="{{ $gov }}" {{ request('governorate') === $gov ? 'selected' : '' }}>{{ $gov }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Submit --}}
            <button type="submit" class="bg-primary text-on-primary px-6 py-2 rounded-lg font-bold hover:opacity-90 transition-all">
                بحث
            </button>
        </form>
    </div>

    {{-- Agents Table --}}
    <div class="bg-surface-bright rounded-lg border border-outline overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-surface-container-high border-b border-outline">
                    <tr>
                        <th class="px-6 py-4 text-right font-bold text-on-surface">الاسم</th>
                        <th class="px-6 py-4 text-right font-bold text-on-surface">الهاتف</th>
                        <th class="px-6 py-4 text-right font-bold text-on-surface">المحافظة</th>
                        <th class="px-6 py-4 text-center font-bold text-on-surface">الحالة</th>
                        <th class="px-6 py-4 text-center font-bold text-on-surface">التوصيلات</th>
                        <th class="px-6 py-4 text-center font-bold text-on-surface">نسبة النجاح</th>
                        <th class="px-6 py-4 text-center font-bold text-on-surface">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline">
                    @forelse($agents as $agent)
                        <tr class="hover:bg-surface-container-low transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-bold text-on-surface">{{ $agent->name }}</p>
                                    <p class="text-sm text-on-surface-variant">{{ $agent->email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-on-surface">{{ $agent->phone }}</td>
                            <td class="px-6 py-4 text-on-surface">{{ $agent->governorate }}</td>
                            <td class="px-6 py-4 text-center">
                                @switch($agent->status)
                                    @case('active')
                                        <span class="px-3 py-1 bg-success/20 text-success rounded-full text-xs font-bold">نشط</span>
                                        @break
                                    @case('inactive')
                                        <span class="px-3 py-1 bg-error/20 text-error rounded-full text-xs font-bold">غير نشط</span>
                                        @break
                                    @case('on_leave')
                                        <span class="px-3 py-1 bg-warning/20 text-warning rounded-full text-xs font-bold">في إجازة</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-on-surface">{{ $agent->stats['total_deliveries'] ?? 0 }}</span>
                                <p class="text-xs text-on-surface-variant">{{ $agent->stats['active_assignments'] ?? 0 }} قيد الانتظار</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <span class="text-lg font-bold text-primary">{{ $agent->stats['success_rate'] ?? 0 }}%</span>
                                    @if($agent->available)
                                        <span class="material-symbols-outlined text-sm text-success">check_circle</span>
                                    @else
                                        <span class="material-symbols-outlined text-sm text-warning">person_busy</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('admin.delivery-agents.show', $agent) }}" class="text-primary hover:text-primary/80 transition" title="عرض التفاصيل">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </a>
                                    <button onclick="openEditModal({{ $agent->id }}, {{ json_encode(['name' => $agent->name, 'phone' => $agent->phone, 'email' => $agent->email, 'id_number' => $agent->id_number, 'governorate' => $agent->governorate, 'address' => $agent->address, 'vehicle_type' => $agent->vehicle_type, 'license_plate' => $agent->license_plate, 'salary_type' => $agent->salary_type, 'commission_rate' => $agent->commission_rate, 'bank_account' => $agent->bank_account, 'status' => $agent->status, 'notes' => $agent->notes]) }})" class="text-primary hover:text-primary/80 transition" title="تعديل">
                                        <span class="material-symbols-outlined">edit</span>
                                    </button>
                                    <form method="POST" action="{{ route('admin.delivery-agents.destroy', $agent) }}" style="display:inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-error hover:text-error/80 transition" title="حذف">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-on-surface-variant">
                                لا توجد عمال توصيل
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($agents->hasPages())
            <div class="px-6 py-4 border-t border-outline">
                {{ $agents->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Create Agent Modal --}}
@include('admin.delivery-agents.modals.create-modal')

{{-- Edit Agent Modal --}}
@include('admin.delivery-agents.modals.edit-modal')
@endsection
