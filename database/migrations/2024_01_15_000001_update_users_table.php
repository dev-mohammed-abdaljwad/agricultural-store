<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Migration is handled in the base users table (0001_01_01_000000)
        // No changes needed - this is a no-op migration kept for history
    }

    public function down(): void
    {
        // No rollback needed
    }
};
