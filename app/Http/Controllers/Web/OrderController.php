<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PricingQuote;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class OrderController extends Controller
{
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
            'quote',
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
        
        // Update order status to confirmed
        $order->update(['status' => 'confirmed']);

        return back()->with('success', 'تم قبول العرض بنجاح');
    }

    /**
     * Reject a quote
     */
    public function rejectQuote(Order $order, PricingQuote $quote)
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

        // Update quote status
        $quote->update(['status' => 'rejected']);
        
        // Update order status back to pending
        $order->update(['status' => 'pending']);

        return back()->with('success', 'تم رفض العرض');
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
            'content' => $validated['message'],
        ]);

        return back()->with('success', 'تم إرسال الرسالة');
    }
}
