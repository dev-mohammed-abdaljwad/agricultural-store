<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PricingQuote;
use App\Models\PricingQuoteItem;
use App\Models\Product;
use App\Models\OrderTracking;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class AdminQuoteController extends Controller
{
    /**
     * Show all quotes (pending, accepted, rejected)
     */
    public function index(Request $request): View
    {
        $query = PricingQuote::with(['order.customer', 'items'])
            ->latest();
        
        // Filter by status
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by quote source
        if ($request->source && $request->source === 'pending_orders') {
            $query->whereHas('order', function ($q) {
                $q->where('status', 'placed');
            })->where('status', 'draft');
        }
        
        // Search by order number or customer
        if ($request->search) {
            $search = $request->search;
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%$search%")
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        }
        
        $quotes = $query->paginate(15);
        
        $pendingOrders = Order::where('status', 'placed')
            ->whereDoesntHave('quotes', function ($q) {
                $q->where('status', '!=', 'rejected');
            })
            ->count();
        
        $statuses = [
            'draft' => 'مسودة',
            'pending' => 'قيد الانتظار',
            'accepted' => 'مقبول',
            'rejected' => 'مرفوض'
        ];
        
        return view('admin.quotes.index', compact('quotes', 'pendingOrders', 'statuses'));
    }

    /**
     * Show form to create a quote for an order
     */
    public function create(Order $order): View|RedirectResponse
    {
        // Verify order has no pending quote
        if ($order->quotes()->where('status', 'pending')->exists()) {
            return back()->with('error', 'هناك عرض سعر قيد الانتظار لهذا الطلب بالفعل');
        }

        // Get products from order items
        $orderItems = $order->items()->with('product')->get();
        $order->load('customer');
        
        return view('admin.quotes.create', [
            'order' => $order,
            'orderItems' => $orderItems,
        ]);
    }

    /**
     * Store a new pricing quote
     */
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.order_item_id' => 'required|exists:order_items,id',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'valid_until' => 'required|date|after:today',
        ]);

        // Get order items for quantity lookup
        $orderItems = $order->items()->pluck('quantity', 'id')->toArray();

        // Calculate total amount first
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $quantity = $orderItems[$item['order_item_id']] ?? 0;
            $totalAmount += ($item['unit_price'] * $quantity);
        }

        // Create quote with total_amount
        $quote = $order->quotes()->create([
            'quoted_by' => auth()->id(),
            'expires_at' => Carbon::parse($validated['valid_until'])->endOfDay()->toDateTimeString(),
            'notes' => $validated['notes'],
            'status' => 'pending',
            'total_amount' => $totalAmount,
        ]);

        // Add quote items
        foreach ($validated['items'] as $item) {
            $quantity = $orderItems[$item['order_item_id']] ?? 0;
            $totalPrice = $item['unit_price'] * $quantity;

            PricingQuoteItem::create([
                'pricing_quote_id' => $quote->id,
                'order_item_id' => $item['order_item_id'],
                'unit_price' => $item['unit_price'],
                'total_price' => $totalPrice,
            ]);
        }

        // Update order status
        $order->update(['status' => 'quote_sent']);

        return redirect()
            ->route('admin.quotes.show', $quote)
            ->with('success', 'تم إرسال عرض السعر بنجاح');
    }

    /**
     * Show quote details
     */
    public function show(PricingQuote $quote): View
    {
        $quote->load(['order.customer', 'items.orderItem.product']);

        return view('admin.quotes.show', compact('quote'));
    }

    /**
     * Show edit form for quote
     */
    public function edit(PricingQuote $quote): View
    {
        // Only allow editing pending quotes
        if ($quote->status !== 'pending') {
            return back()->with('error', 'لا يمكن تعديل عرض سعر تم الرد عليه');
        }

        $quote->load(['order.items.product', 'items.orderItem.product']);
        
        return view('admin.quotes.edit', compact('quote'));
    }

    /**
     * Update a quote
     */
    public function update(Request $request, PricingQuote $quote)
    {
        if ($quote->status !== 'pending') {
            return back()->with('error', 'لا يمكن تعديل عرض سعر تم الرد عليه');
        }

        $order = $quote->order;

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.order_item_id' => 'required|exists:order_items,id',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'valid_until' => 'required|date|after:today',
        ]);

        // Get order items for quantity lookup
        $orderItems = $order->items()->pluck('quantity', 'id')->toArray();

        // Calculate total amount first
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $quantity = $orderItems[$item['order_item_id']] ?? 0;
            $totalAmount += ($item['unit_price'] * $quantity);
        }

        // Delete old items
        $quote->items()->delete();

        // Add updated items
        foreach ($validated['items'] as $item) {
            $quantity = $orderItems[$item['order_item_id']] ?? 0;
            $totalPrice = $item['unit_price'] * $quantity;

            PricingQuoteItem::create([
                'pricing_quote_id' => $quote->id,
                'order_item_id' => $item['order_item_id'],
                'unit_price' => $item['unit_price'],
                'total_price' => $totalPrice,
            ]);
        }

        // Update quote with new values
        $quote->update([
            'total_amount' => $totalAmount,
            'notes' => $validated['notes'],
            'expires_at' => Carbon::parse($validated['valid_until'])->endOfDay()->toDateTimeString(),
        ]);

        return redirect()
            ->route('admin.quotes.show', $quote)
            ->with('success', 'تم تحديث عرض السعر بنجاح');
    }

    /**
     * Send quote to customer
     */
    public function send(PricingQuote $quote): RedirectResponse
    {
        if ($quote->status !== 'draft' && $quote->status !== 'pending') {
            return back()->with('error', 'تم الرد على عرض السعر بالفعل');
        }

        // TODO: Send email notification
        // event(new QuoteSent($quote));

        $quote->update([
            'sent_at' => now(),
            'status' => 'pending',
        ]);

        // Update order status to quote_sent
        $quote->order->update(['status' => 'quote_sent']);

        // Log to order tracking
        OrderTracking::create([
            'order_id' => $quote->order_id,
            'status' => 'quote_sent',
            'title' => 'تم إرسال عرض السعر',
            'description' => "عرض السعر #{$quote->id} - المبلغ: {$quote->total_amount} جنيه",
            'occurred_at' => now()
        ]);

        return back()->with('success', 'تم إرسال عرض السعر للعميل');
    }

    /**
     * Handle quote acceptance (auto-confirm order)
     */
    public function handleAcceptance(PricingQuote $quote): RedirectResponse
    {
        if ($quote->status !== 'accepted') {
            return back()->with('error', 'هذا العرض لم يتم قبوله من العميل');
        }

        $order = $quote->order;

        // Auto-confirm the order
        $order->update([
            'status' => 'quote_accepted',
            'total_amount' => $quote->total_amount,
            'payment_method' => $quote->payment_method ?? 'transfer',
        ]);

        // Log to order tracking
        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'quote_accepted',
            'title' => 'تم تأكيد الطلب',
            'description' => "العميل قبل عرض السعر #{$quote->id}",
            'occurred_at' => now()
        ]);

        return back()->with('success', 'تم تأكيد الطلب تلقائياً');
    }

    /**
     * Request new quote after rejection
     */
    public function requestNewQuote(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        // Mark current pending quote as rejected
        $currentQuote = $order->quotes()->where('status', 'pending')->first();
        if ($currentQuote) {
            $currentQuote->update(['status' => 'rejected']);
        }

        // Reset order to placed status for new quote
        $order->update([
            'status' => 'placed',
            'admin_notes' => $validated['reason'] ?? null
        ]);

        // Log to order tracking
        OrderTracking::create([
            'order_id' => $order->id,
            'status' => 'placed',
            'title' => 'طلب عرض سعر جديد',
            'description' => $validated['reason'] ?? 'طلب عرض سعر جديد من العميل',
            'occurred_at' => now()
        ]);

        return back()->with('success', 'تم طلب عرض سعر جديد. يمكنك الآن إنشاء عرض جديد');
    }

    /**
     * Delete a quote
     */
    public function destroy(PricingQuote $quote)
    {
        if ($quote->status !== 'pending') {
            return back()->with('error', 'لا يمكن حذف عرض سعر تم الرد عليه');
        }

        $order = $quote->order;
        $quote->delete();

        // Reset order status if this was the only quote
        if (!$order->quotes()->exists()) {
            $order->update(['status' => 'placed']);
        }

        return back()->with('success', 'تم حذف عرض السعر');
    }
}
