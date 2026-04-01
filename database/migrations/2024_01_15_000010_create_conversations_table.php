<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('customer_id');
            $table->unique(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
