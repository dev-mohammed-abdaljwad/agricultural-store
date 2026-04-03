<?php

namespace App\Http\Controllers;

use App\Models\DeliveryAgent;
use App\Models\DeliveryAssignment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminDeliveryAgentController extends Controller
{
    /**
     * Display all delivery agents
     */
    public function index(Request $request): View
    {
        $query = DeliveryAgent::query();

        // Filter by status
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by governorate
        if ($request->governorate) {
            $query->where('governorate', $request->governorate);
        }

        // Search by name, phone, or email
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $agents = $query->latest()->paginate(15);

        // Get unique governorates for filter
        $existingGovernorates = DeliveryAgent::distinct('governorate')->pluck('governorate');
        
        // Get all available governorates for modals
        $governorates = $this->getGovernoratesList();

        // Add stats to each agent
        $agents->each(function ($agent) {
            $agent->stats = $agent->getStatistics();
            $agent->available = $agent->isAvailable();
        });

        return view('admin.delivery-agents.index', compact('agents', 'governorates', 'existingGovernorates'));
    }

    /**
     * Show form to create new delivery agent
     */
    public function create(): View
    {
        $governorates = $this->getGovernoratesList();
        return view('admin.delivery-agents.create', compact('governorates'));
    }

    /**
     * Store new delivery agent
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'phone' => 'required|unique:delivery_agents,phone|min:10',
            'email' => 'required|email|unique:delivery_agents,email',
            'governorate' => 'required|string',
            'address' => 'required|string|min:10',
            'vehicle_type' => 'required|string|in:car,motorcycle,bicycle,van',
            'license_plate' => 'nullable|string|unique:delivery_agents,license_plate',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'salary_type' => 'required|in:fixed,commission,hybrid',
            'hire_date' => 'required|date|before_or_equal:today',
            'id_number' => 'nullable|string|unique:delivery_agents,id_number',
            'bank_account' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        DeliveryAgent::create($validated);

        return redirect()->route('admin.delivery-agents.index')
            ->with('success', 'تم إضافة عامل التوصيل بنجاح');
    }

    /**
     * Show delivery agent details
     */
    public function show(DeliveryAgent $agent): View
    {
        $agent->load(['assignments' => function ($q) {
            $q->latest()->limit(20);
        }]);

        $agent->stats = $agent->getStatistics();

        return view('admin.delivery-agents.show', compact('agent'));
    }

    /**
     * Show form to edit delivery agent
     */
    public function edit(DeliveryAgent $agent): View
    {
        $governorates = $this->getGovernoratesList();
        return view('admin.delivery-agents.edit', compact('agent', 'governorates'));
    }

    /**
     * Update delivery agent
     */
    public function update(Request $request, DeliveryAgent $agent): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'phone' => 'required|min:10|unique:delivery_agents,phone,' . $agent->id,
            'email' => 'required|email|unique:delivery_agents,email,' . $agent->id,
            'governorate' => 'required|string',
            'address' => 'required|string|min:10',
            'vehicle_type' => 'required|string|in:car,motorcycle,bicycle,van',
            'license_plate' => 'nullable|string|unique:delivery_agents,license_plate,' . $agent->id,
            'commission_rate' => 'required|numeric|min:0|max:100',
            'salary_type' => 'required|in:fixed,commission,hybrid',
            'status' => 'required|in:active,inactive,on_leave',
            'id_number' => 'nullable|string|unique:delivery_agents,id_number,' . $agent->id,
            'bank_account' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        $agent->update($validated);

        return redirect()->route('admin.delivery-agents.show', $agent)
            ->with('success', 'تم تحديث بيانات عامل التوصيل بنجاح');
    }

    /**
     * Delete delivery agent
     */
    public function destroy(DeliveryAgent $agent): RedirectResponse
    {
        // Check if agent has pending deliveries
        $pendingDeliveries = $agent->assignments()
            ->whereIn('delivery_status', ['assigned', 'in_transit', 'arrived'])
            ->count();

        if ($pendingDeliveries > 0) {
            return back()->with('error', 'لا يمكن حذف عامل التوصيل لديه توصيلات قيد الانتظار');
        }

        $agent->delete();

        return redirect()->route('admin.delivery-agents.index')
            ->with('success', 'تم حذف عامل التوصيل بنجاح');
    }

    /**
     * Assign order to delivery agent
     */
    public function assignOrder(Request $request, Order $order): RedirectResponse
    {
        // Verify order is ready for delivery
        if (!in_array($order->status, ['paid', 'preparing'])) {
            return back()->with('error', 'الطلب غير جاهز للتوصيل');
        }

        // Check if order already has pending assignment
        if ($order->deliveryAssignment()?->first()?->delivery_status === 'assigned') {
            return back()->with('error', 'الطلب مُسَنَّد بالفعل لعامل توصيل');
        }

        $validated = $request->validate([
            'agent_id' => 'required|exists:delivery_agents,id',
        ]);

        $agent = DeliveryAgent::findOrFail($validated['agent_id']);

        // Check if agent is available
        if (!$agent->isAvailable()) {
            return back()->with('error', 'عامل التوصيل غير متاح حالياً');
        }

        // Create delivery assignment
        DeliveryAssignment::create([
            'order_id' => $order->id,
            'agent_id' => $agent->id,
            'assigned_at' => now(),
            'delivery_fee' => $order->delivery_fee ?? 0,
        ]);

        // Update order status
        $order->update(['status' => 'out_for_delivery']);

        // Log to order tracking
        \App\Models\OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'out_for_delivery',
            'title' => 'تم إسناد التوصيل',
            'description' => "تم إسناد الطلب لعامل التوصيل: {$agent->name}",
            'occurred_at' => now(),
        ]);

        return back()->with('success', "تم إسناد الطلب لـ {$agent->name} بنجاح");
    }

    /**
     * Unassign order from delivery agent
     */
    public function unassignOrder(Order $order): RedirectResponse
    {
        $assignment = $order->deliveryAssignment;

        if (!$assignment) {
            return back()->with('error', 'الطلب لا يوجد له عامل توصيل مُسَنَّد');
        }

        if (!in_array($assignment->delivery_status, ['assigned', 'rescheduled'])) {
            return back()->with('error', 'لا يمكن إلغاء إسناد الطلب في هذه الحالة');
        }

        $assignment->delete();

        // Revert order status
        $order->update(['status' => 'preparing']);

        return back()->with('success', 'تم إلغاء إسناد الطلب بنجاح');
    }

    /**
     * Get list of Egyptian governorates
     */
    private function getGovernoratesList(): array
    {
        return [
            'القاهرة' => 'القاهرة',
            'الجيزة' => 'الجيزة',
            'الإسكندرية' => 'الإسكندرية',
            'بور سعيد' => 'بور سعيد',
            'السويس' => 'السويس',
            'البحر الأحمر' => 'البحر الأحمر',
            'الدقهلية' => 'الدقهلية',
            'كفر الشيخ' => 'كفر الشيخ',
            'الشرقية' => 'الشرقية',
            'المنوفية' => 'المنوفية',
            'القليوبية' => 'القليوبية',
            'الغربية' => 'الغربية',
            'المنيا' => 'المنيا',
            'بني سويف' => 'بني سويف',
            'الفيوم' => 'الفيوم',
            'أسيوط' => 'أسيوط',
            'سوهاج' => 'سوهاج',
            'قنا' => 'قنا',
            'أسوان' => 'أسوان',
            'الأقصر' => 'الأقصر',
            'مطروح' => 'مطروح',
            'الوادي الجديد' => 'الوادي الجديد',
            'جنوب سيناء' => 'جنوب سيناء',
            'شمال سيناء' => 'شمال سيناء',
        ];
    }
}
