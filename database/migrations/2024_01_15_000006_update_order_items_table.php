<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Order items table schema is handled in 2024_01_01_000005_create_order_items_table
        // No changes needed - this is a no-op migration kept for history
    }

    public function down(): void
    {
        // No rollback needed
    }
};
