<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status');         // Matches order status
            $table->string('title');          // "تم استلام طلبك"
            $table->text('description')->nullable(); // Additional details
            $table->timestamp('occurred_at');
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_tracking');
    }
};
