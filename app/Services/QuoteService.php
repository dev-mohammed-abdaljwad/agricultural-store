<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\PricingQuote;
use App\Models\PricingQuoteItem;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;

class QuoteService
{
    /**
     * Send a quote to customer.
     */
    public function sendQuote(Order $order, User $admin, array $data): PricingQuote
    {
        // Create pricing quote
        $quote = PricingQuote::create([
            'order_id' => $order->id,
            'quoted_by' => $admin->id,
            'delivery_fee' => $data['delivery_fee'] ?? 0,
            'total_amount' => 0, // Will be calculated below
            'notes' => $data['notes'] ?? null,
            'expires_at' => isset($data['expires_in_hours']) 
                ? Carbon::now()->addHours($data['expires_in_hours'])
                : Carbon::now()->addHours(48),
            'status' => 'pending',
        ]);

        // Create quote items and calculate total
        $total = $quote->delivery_fee;
        $quoteItems = $data['items'] ?? [];

        foreach ($quoteItems as $item) {
            $orderItem = OrderItem::findOrFail($item['order_item_id']);
            $unitPrice = $item['unit_price'];
            $totalPrice = $unitPrice * $orderItem->quantity;

            PricingQuoteItem::create([
                'pricing_quote_id' => $quote->id,
                'order_item_id' => $orderItem->id,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ]);

            $total += $totalPrice;
        }

        // Update quote total
        $quote->update(['total_amount' => $total]);

        // Update order status
        $order->update(['status' => 'quote_sent']);

        // Add tracking entry
        OrderTrackingService::record($order, 'quote_sent');

        // Dispatch notification job
        \App\Jobs\NotifyCustomerOfQuote::dispatch($order, $quote);

        return $quote->load('items');
    }

    /**
     * Accept quote and update order items with prices.
     */
    public function acceptQuote(Order $order, PricingQuote $quote): Order
    {
        // Update order items with prices from quote
        foreach ($quote->items as $quoteItem) {
            $quoteItem->orderItem->update([
                'unit_price' => $quoteItem->unit_price,
                'total_price' => $quoteItem->total_price,
            ]);
        }

        // Update order
        $order->update([
            'total_amount' => $quote->total_amount,
            'delivery_fee' => $quote->delivery_fee,
            'status' => 'quote_accepted',
        ]);

        // Update quote status
        $quote->update(['status' => 'accepted']);

        // Add tracking entry
        OrderTrackingService::record($order, 'quote_accepted', 'تم قبول عرض السعر');

        return $order->refresh()->load('items', 'activeQuote', 'tracking');
    }

    /**
     * Reject quote and revert order to quote_pending.
     */
    public function rejectQuote(Order $order, PricingQuote $quote): Order
    {
        // Update quote status
        $quote->update(['status' => 'rejected']);

        // Reset order to quote_pending
        $order->update(['status' => 'quote_pending']);

        return $order->refresh()->load('items', 'activeQuote');
    }
}
