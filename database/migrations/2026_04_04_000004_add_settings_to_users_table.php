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
            // Add language preference
            $table->string('language')->default('ar')->after('governorate');
            
            // Add notification preferences
            $table->boolean('notify_orders')->default(true)->after('language');
            $table->boolean('notify_messages')->default(true)->after('notify_orders');
            $table->boolean('notify_price_changes')->default(false)->after('notify_messages');
            $table->boolean('notify_promotions')->default(false)->after('notify_price_changes');
            
            // Add avatar for profile
            $table->string('avatar')->nullable()->after('notify_promotions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'language',
                'notify_orders',
                'notify_messages',
                'notify_price_changes',
                'notify_promotions',
                'avatar',
            ]);
        });
    }
};
