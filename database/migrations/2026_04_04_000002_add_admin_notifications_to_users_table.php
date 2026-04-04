<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add admin-specific notification preferences if they don't exist
            if (!Schema::hasColumn('users', 'notify_products')) {
                $table->boolean('notify_products')->default(true)->after('notify_messages');
            }
            if (!Schema::hasColumn('users', 'notify_reports')) {
                $table->boolean('notify_reports')->default(true)->after('notify_products');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'notify_products')) {
                $table->dropColumn('notify_products');
            }
            if (Schema::hasColumn('users', 'notify_reports')) {
                $table->dropColumn('notify_reports');
            }
        });
    }
};
