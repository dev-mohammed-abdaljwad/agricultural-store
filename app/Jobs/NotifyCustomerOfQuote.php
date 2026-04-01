<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Order;
use App\Models\PricingQuote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyCustomerOfQuote implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public PricingQuote $quote,
    ) {}

    public function handle(): void
    {
        // TODO: Send email/SMS to customer about new quote
        // Implement notification logic here
        // Example: Mail::send(new QuoteNotificationMail($this->order, $this->quote));
    }
}
