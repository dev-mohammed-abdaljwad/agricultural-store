<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\PlaceOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Services\QuoteService;
use App\Models\Order;
use App\Models\PricingQuote;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private OrderService $orderService,
        private QuoteService $quoteService,
    ) {}

    /**
     * Place a new order.
     */
    public function store(PlaceOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->placeOrder(
            $request->user(),
            $request->validated()
        );

        return $this->successResponse(
            OrderResource::make($order),
            'Order placed successfully.',
            201
        );
    }

    /**
     * Get customer's orders.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->getCustomerOrders(
            $request->user(),
            $request->get('per_page', 15)
        );

        return $this->successResponse(OrderResource::collection($orders));
    }

    /**
     * Get specific order.
     */
    public function show(Order $order, Request $request): JsonResponse
    {
        if ($order->customer_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized.', 403);
        }

        return $this->successResponse(OrderResource::make($this->orderService->getOrder($order)));
    }

    /**
     * Accept quote.
     */
    public function acceptQuote(Order $order, PricingQuote $quote, Request $request): JsonResponse
    {
        if ($order->customer_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized.', 403);
        }

        if ($quote->order_id !== $order->id || $quote->status !== 'pending') {
            return $this->errorResponse('Quote not found or invalid.', 404);
        }

        $order = $this->quoteService->acceptQuote($order, $quote);

        return $this->successResponse(
            OrderResource::make($order),
            'Quote accepted successfully.'
        );
    }

    /**
     * Reject quote.
     */
    public function rejectQuote(Order $order, PricingQuote $quote, Request $request): JsonResponse
    {
        if ($order->customer_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized.', 403);
        }

        if ($quote->order_id !== $order->id || $quote->status !== 'pending') {
            return $this->errorResponse('Quote not found or invalid.', 404);
        }

        $order = $this->quoteService->rejectQuote($order, $quote);

        return $this->successResponse(
            OrderResource::make($order),
            'Quote rejected. Admin will send a new quote.'
        );
    }
}
