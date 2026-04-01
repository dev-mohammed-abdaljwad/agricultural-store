<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\SendQuoteRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Services\OrderService;
use App\Services\QuoteService;
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
     * Get all orders with filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->getAllOrders(
            $request->get('status'),
            $request->get('per_page', 15)
        );

        return $this->successResponse(OrderResource::collection($orders));
    }

    /**
     * Get specific order.
     */
    public function show(Order $order): JsonResponse
    {
        return $this->successResponse(OrderResource::make($this->orderService->getOrder($order)));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:paid,preparing,out_for_delivery,delivered,cancelled',
            'description' => 'nullable|string',
        ]);

        $order = $this->orderService->updateOrderStatus(
            $order,
            $request->input('status'),
            $request->input('description')
        );

        return $this->successResponse(
            OrderResource::make($order),
            'Order status updated.'
        );
    }

    /**
     * Send quote to customer.
     */
    public function sendQuote(SendQuoteRequest $request, Order $order): JsonResponse
    {
        if (!in_array($order->status, ['placed', 'quote_pending', 'quote_rejected'])) {
            return $this->errorResponse('Cannot send quote for this order status.', 422);
        }

        $quote = $this->quoteService->sendQuote(
            $order,
            $request->user(),
            $request->validated()
        );

        return $this->successResponse(
            OrderResource::make($order->refresh()),
            'Quote sent to customer.',
            201
        );
    }

    /**
     * Get order tracking history.
     */
    public function getTracking(Order $order): JsonResponse
    {
        $tracking = OrderTracking::where('order_id', $order->id)
            ->latest('occurred_at')
            ->get();

        return $this->successResponse($tracking);
    }
}
