<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryAgent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'governorate',
        'address',
        'vehicle_type',
        'license_plate',
        'commission_rate',
        'salary_type',
        'status',
        'hire_date',
        'id_number',
        'bank_account',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'commission_rate' => 'decimal:2',
    ];

    /**
     * Get all delivery assignments for this agent
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(DeliveryAssignment::class, 'agent_id');
    }

    /**
     * Get active assignments (not yet completed)
     */
    public function activeAssignments(): HasMany
    {
        return $this->assignments()
            ->whereIn('delivery_status', ['assigned', 'in_transit', 'arrived'])
            ->latest();
    }

    /**
     * Get completed assignments
     */
    public function completedAssignments(): HasMany
    {
        return $this->assignments()
            ->where('delivery_status', 'delivered')
            ->latest();
    }

    /**
     * Get agent statistics
     */
    public function getStatistics(): array
    {
        $total = $this->assignments()->count();
        $delivered = $this->assignments()->where('delivery_status', 'delivered')->count();
        $failed = $this->assignments()->where('delivery_status', 'failed')->count();
        $active = $this->activeAssignments()->count();

        return [
            'total_deliveries' => $total,
            'successful_deliveries' => $delivered,
            'failed_deliveries' => $failed,
            'active_assignments' => $active,
            'success_rate' => $total > 0 ? round(($delivered / $total) * 100, 2) : 0,
            'average_rating' => 0, // For future feedback system
        ];
    }

    /**
     * Check if agent is available for assignments
     */
    public function isAvailable(): bool
    {
        return $this->status === 'active' && $this->activeAssignments()->count() < 5;
    }

    /**
     * Mutator for formatting phone number
     */
    public function setPhoneAttribute($value): void
    {
        // Remove any spaces or dashes
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }
}
