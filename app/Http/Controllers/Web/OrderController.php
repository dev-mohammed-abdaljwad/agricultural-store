<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PricingQuote;
use App\Models\Message;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class OrderController extends Controller
{
    /**
     * List customer orders
     */
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['items.product.images', 'activeQuote'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.orders.index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Screen 1: Cart Review
     */
    public function cartReview()
    {
        // Get user's current cart items
        $cartItems = auth()->user()->cartItems()
            ->with('product.images', 'product.category')
            ->get()
            ->map(function ($item) {
                return [
                    'cart_item_id' => $item->id,  // ← Pass the actual cart item ID
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'category' => $item->product->category->name ?? '',
                    'supplier' => $item->product->supplier_name,
                    'image' => $item->product->images->first()?->url,
                    'quantity' => $item->quantity,
                    'unit' => $item->product->unit,
                ];
            })
            ->toArray();

        $totalWeight = auth()->user()->cartItems()
            ->with('product')
            ->get()
            ->sum(fn($item) => $item->quantity * ($item->product->weight ?? 1));

        return view('customer.orders.cart-review', [
            'items' => $cartItems,
            'itemCount' => count($cartItems),
            'totalWeight' => $totalWeight,
        ]);
    }

    /**
     * Screen 2: Delivery Information
     */
    public function deliveryInfo()
    {
        $cartItems = auth()->user()->cartItems()
            ->with('product.images')
            ->get()
            ->map(function ($item) {
                return [
                    'cart_item_id' => $item->id,  // ← Pass the actual cart item ID
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'image' => $item->product->images->first()?->asset_url,
                    'quantity' => $item->quantity,
                    'unit' => $item->product->unit,
                ];
            })
            ->toArray();

        $governorates = [
            'القاهرة', 'الجيزة', 'القليوبية', 'الشرقية', 'الإسكندرية',
            'البحيرة', 'الغربية', 'المنوفية', 'كفر الشيخ', 'الدقهلية',
            'الفيوم', 'بني سويف', 'المنيا', 'أسيوط', 'سوهاج',
            'قنا', 'الأقصر', 'أسوان', 'مرسى مطروح', 'جنوب سيناء',
            'شمال سيناء', 'البحر الأحمر'
        ];

        return view('customer.orders.delivery-info', [
            'items' => $cartItems,
            'governorates' => $governorates,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Create order from cart
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string|max:500',
            'delivery_governorate' => 'required|string',
            'phone' => 'required|string|size:11',
            'payment_method' => 'required|in:cod,online',
        ]);

        $user = auth()->user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'السلة فارغة');
        }

        // Create order
        $order = Order::create([
            'customer_id' => $user->id,
            'order_number' => 'NH-' . date('Y') . '-' . str_pad((string)(Order::count() + 1), 4, '0', STR_PAD_LEFT),
            'status' => 'placed',
            'delivery_address' => $validated['delivery_address'],
            'delivery_governorate' => $validated['delivery_governorate'],
            'delivery_phone' => $validated['phone'],
            'payment_method' => $validated['payment_method'],
        ]);

        // Create order items
        foreach ($cartItems as $cartItem) {
            $order->items()->create([
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->product->base_price,
                'notes' => $cartItem->notes,
            ]);
        }

        // Clear cart
        $user->cartItems()->delete();

        return redirect()->route('cart.index')
            ->with('success', 'تم إرسال الطلب بنجاح. رقم الطلب: ' . $order->order_number);
    }

    /**
     * Screen 3: Order Placed Success
     */
    public function placedSuccess(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        return view('customer.orders.placed-success', [
            'order' => [
                'number' => $order->order_number,
                'id' => $order->id,
                'date' => $order->created_at->format('d F Y'),
                'status' => $order->getStatusLabel(),
            ],
        ]);
    }

    /**
     * Screen 4: Quote Sent
     */
    public function quoteSent(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        // Find any pending or accepted quote for this order
        $quote = $order->quotes()->whereIn('status', ['pending', 'accepted'])->latest()->first();
        
        if (!$quote) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'لم يتم إرسال عرض سعر لهذا الطلب بعد');
        }

        $trackingSteps = [
            ['title' => 'تم إرسال الطلب', 'date' => $order->created_at->format('d F Y، h:i a'), 'status' => 'completed'],
            ['title' => 'قيد المراجعة', 'date' => $order->updated_at->format('d F Y، h:i a'), 'status' => 'completed'],
            ['title' => 'تم إرسال عرض السعر', 'date' => $quote->created_at->format('d F Y، h:i a'), 'status' => 'current'],
            ['title' => 'بانتظار قبول العرض', 'status' => 'future'],
            ['title' => 'تأكيد الدفع', 'status' => 'future'],
            ['title' => 'تجهيز الشحنة', 'status' => 'future'],
            ['title' => 'جاري التوصيل', 'status' => 'future'],
            ['title' => 'تم الاستلام', 'status' => 'future'],
        ];

        $quoteItems = $quote->items->map(function ($item) {
            return [
                'name' => $item->orderItem->product->name,
                'supplier' => $item->orderItem->product->supplier_name,
                'image' => $item->orderItem->product->images->first()?->url,
                'quantity' => $item->orderItem->quantity . ' ' . $item->orderItem->product->unit,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
            ];
        })->toArray();

        // Get messages from first conversation (if any)
        $messages = $order->conversations->first()?->messages->map(function ($msg) {
            return [
                'sender' => $msg->sender_type === 'admin' ? 'agent' : 'customer',
                'text' => $msg->content,
                'time' => $msg->created_at->format('h:i a'),
            ];
        })->toArray() ?? [];

        return view('customer.orders.quote-sent', [
            'order' => [
                'number' => $order->order_number,
                'date' => $order->created_at->format('d F Y'),
                'id' => $order->id,
            ],
            'quoteItems' => $quoteItems,
            'quote' => [
                'id' => $quote->id,
                'subtotal' => $quote->items->sum('total_price'),
                'shipping' => $quote->delivery_fee ?? 0,
                'tax' => round(($quote->items->sum('total_price') + ($quote->delivery_fee ?? 0)) * 0.14),
                'total' => $quote->total_amount,
            ],
            'trackingSteps' => $trackingSteps,
            'messages' => $messages,
        ]);
    }

    /**
     * Screen 5A: Order Confirmed
     */
    public function confirmed(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        // Check if quote was accepted (regardless of current order status)
        // This allows customers to view the confirmed page even if admin changed status
        $quote = $order->quotes()->where('status', 'accepted')->latest()->first();
        
        if (!$quote) {
            // No accepted quote - redirect to order details
            return redirect()->route('orders.show', $order)
                ->with('error', 'لم يتم قبول عرض سعر لهذا الطلب');
        }

        // If order has moved beyond quote_accepted, still show confirmation but note the current status
        $trackingSteps = [
            ['title' => 'تم إرسال الطلب', 'date' => $order->created_at->format('d F Y'), 'status' => 'completed'],
            ['title' => 'قيد المراجعة', 'status' => 'completed'],
            ['title' => 'تم إرسال عرض السعر', 'status' => 'completed'],
            ['title' => 'تأكيد الدفع', 'date' => now()->format('d F Y'), 'status' => 'current'],
            ['title' => 'تجهيز الشحنة', 'status' => 'future'],
            ['title' => 'جاري التوصيل', 'status' => 'future'],
        ];

        return view('customer.orders.confirmed', [
            'order' => [
                'number' => $order->order_number,
                'id' => $order->id,
                'reference_number' => 'ORD-' . $order->id,
                'total' => $quote->total_amount ?? 0,
                'status' => $order->status,
                'current_status' => $order->status,
                'status_label' => $order->getStatusLabel(),
            ],
            'items' => $quote->items->map(function ($quoteItem) {
                return [
                    'name' => $quoteItem->orderItem->product->name,
                    'quantity' => $quoteItem->orderItem->quantity,
                    'unit' => $quoteItem->orderItem->product->unit,
                    'supplier' => $quoteItem->orderItem->product->supplier_name ?? 'المورد',
                    'image' => $quoteItem->orderItem->product->images->first()?->asset_url,
                    'unit_price' => $quoteItem->unit_price,
                    'total_price' => $quoteItem->total_price,
                    'delivery_date' => 'قريباً',
                ];
            })->toArray(),
            'trackingSteps' => $trackingSteps,
        ]);
    }

    /**
     * Screen 5B: Quote Rejected
     */
    public function quoteRejected(Order $order)
    {
        if ($order->customer_id !== auth()->id()) {
            abort(403);
        }

        $quote = $order->quotes()->where('status', 'rejected')->latest()->firstOrFail();

        $trackingSteps = [
            ['title' => 'تم إرسال الطلب', 'date' => $order->created_at->format('d F Y'), 'status' => 'completed'],
            ['title' => 'قيد المراجعة', 'status' => 'completed'],
            ['title' => 'تم إرسال عرض السعر', 'status' => 'completed'],
            ['title' => 'رفض العرض الأول', 'date' => $quote->rejected_at?->format('d F Y') ?? 'لم يتم تحديده', 'status' => 'completed', 'icon' => 'close', 'color' => 'error'],
            ['title' => 'إعداد عرض سعر جديد', 'date' => 'جاري الآن...', 'status' => 'current'],
            ['title' => 'التعاقد والتوريد', 'status' => 'future'],
        ];

        $products = $quote->items->map(function ($item) {
            return [
                'name' => $item->orderItem->product->name,
                'image' => $item->orderItem->product->images->first()?->url,
                'quantity' => $item->orderItem->quantity . ' ' . $item->orderItem->product->unit,
                'old_price' => $item->unit_price,
                'badge' => 'جودة ممتازة',
                'currency' => 'ج.م',
            ];
        })->toArray();

        return view('customer.orders.quote-rejected', [
            'order' => [
                'number' => $order->order_number,
                'id' => $order->id,
                'date' => $order->created_at->format('d F Y'),
            ],
            'products' => $products,
            'rejectionNotes' => $quote->rejection_reason ?? 'لم يتم تقديم ملاحظات محددة',
            'trackingSteps' => $trackingSteps,
        ]);
    }

    /**
     * Display order details
     */
    public function show(Order $order)
    {
        // Verify user owns this order
        if ($order->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Load relationships
        $order->load([
            'items.product.images',
            'items.product.category',
            'quotes',
            'tracking',
            'conversations.messages'
        ]);

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    /**
     * Accept a quote
     */
    public function acceptQuote(Order $order, PricingQuote $quote)
    {
        // Verify user owns this order
        if ($order->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Verify quote belongs to this order
        if ($quote->order_id !== $order->id) {
            abort(404);
        }

        // Only accept pending quotes
        if ($quote->status !== 'pending') {
            return back()->with('error', 'لا يمكن قبول هذا العرض');
        }

        // Update quote status
        $quote->update(['status' => 'accepted']);
        
        // Update order status to quote_accepted
        $order->update(['status' => 'quote_accepted']);

        return redirect()->route('customer.orders.confirmed', ['order' => $order])
            ->with('success', 'تم قبول العرض بنجاح');
    }

    /**
     * Reject a quote
     */
    public function rejectQuote(Request $request, Order $order, PricingQuote $quote)
    {
        // Verify user owns this order
        if ($order->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Verify quote belongs to this order
        if ($quote->order_id !== $order->id) {
            abort(404);
        }

        // Only reject pending quotes
        if ($quote->status !== 'pending') {
            return back()->with('error', 'لا يمكن رفض هذا العرض');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        // Update quote status
        $quote->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['reason'] ?? null,
            'rejected_at' => now(),
        ]);

        return redirect()->route('customer.orders.quote-rejected', ['order' => $order])
            ->with('success', 'تم رفض العرض بنجاح. سيتم إرسال عرض جديد قريباً');
    }

    /**
     * Create a message for an order
     */
    public function createMessage(Request $request, Order $order)
    {
        // Verify user owns this order
        if ($order->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Get or create conversation for this order
        $conversation = $order->conversations->first();
        if (!$conversation) {
            $conversation = $order->conversations()->create([
                'admin_id' => 1, // Admin user ID (could be the assigned admin)
            ]);
        }

        // Create the message
        $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'sender_type' => 'customer',
            'content' => $validated['message'],
        ]);

        return back()->with('success', 'تم إرسال الرسالة');
    }
}

