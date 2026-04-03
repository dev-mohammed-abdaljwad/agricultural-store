<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class DeliveryAssignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'agent_id',
        'assigned_at',
        'started_at',
        'completed_at',
        'delivery_status',
        'delivery_fee',
        'notes',
        'retry_count',
        'rescheduled_for',
        'failure_reason',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'rescheduled_for' => 'datetime',
        'delivery_fee' => 'decimal:2',
    ];

    /**
     * Get the order this assignment belongs to
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the delivery agent
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(DeliveryAgent::class, 'agent_id');
    }

    /**
     * Calculate delivery time in minutes
     */
    public function getDeliveryTimeInMinutes(): ?int
    {
        if ($this->started_at && $this->completed_at) {
            return $this->completed_at->diffInMinutes($this->started_at);
        }
        return null;
    }

    /**
     * Mark assignment as started
     */
    public function markAsStarted(): void
    {
        $this->update([
            'started_at' => now(),
            'delivery_status' => 'in_transit',
        ]);
    }

    /**
     * Mark assignment as arrived
     */
    public function markAsArrived(): void
    {
        $this->update([
            'delivery_status' => 'arrived',
        ]);
    }

    /**
     * Mark assignment as delivered
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'completed_at' => now(),
            'delivery_status' => 'delivered',
        ]);

        // Update order status
        $this->order->update(['status' => 'delivered']);
    }

    /**
     * Mark assignment as failed
     */
    public function markAsFailed(string $reason): void
    {
        $retryCount = $this->retry_count + 1;
        $maxRetries = 3;

        if ($retryCount >= $maxRetries) {
            // Final attempt failed
            $this->update([
                'delivery_status' => 'failed',
                'failure_reason' => $reason,
                'retry_count' => $retryCount,
                'completed_at' => now(),
            ]);

            // Update order status
            $this->order->update(['status' => 'delivery_failed']);
        } else {
            // Reschedule for next delivery attempt
            $rescheduleDate = now()->addHours(24); // Retry next day

            $this->update([
                'delivery_status' => 'rescheduled',
                'failure_reason' => $reason,
                'retry_count' => $retryCount,
                'rescheduled_for' => $rescheduleDate,
            ]);

            // Keep order status as 'out_for_delivery' for rescheduled attempts
        }
    }

    /**
     * Get status label in Arabic
     */
    public function getStatusLabel(): string
    {
        return match($this->delivery_status) {
            'assigned' => 'مُسَنَّد',
            'in_transit' => 'جاري التوصيل',
            'arrived' => 'وصل المكان',
            'delivered' => 'تم التسليم',
            'failed' => 'فشل التسليم',
            'rescheduled' => 'إعادة جدولة',
            default => $this->delivery_status,
        };
    }

    /**
     * Check if assignment can be started
     */
    public function canStart(): bool
    {
        return $this->delivery_status === 'assigned' && !$this->started_at;
    }

    /**
     * Scope: Get recent assignments ordered by assigned_at
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderByDesc('assigned_at');
    }

    /**
     * Check if assignment can be marked as delivered
     */
    public function canMarkAsDelivered(): bool
    {
        return in_array($this->delivery_status, ['in_transit', 'arrived']);
    }

    /**
     * Check if assignment can be marked as failed
     */
    public function canMarkAsFailed(): bool
    {
        return in_array($this->delivery_status, ['in_transit', 'arrived']);
    }
}
