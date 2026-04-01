<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyAdminOfNewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Order $order,
    ) {}

    public function handle(): void
    {
        // TODO: Send email/SMS to admin about new order
        // Implement notification logic here
        // Example: Mail::send(new NewOrderNotificationMail($this->order));
    }
}
