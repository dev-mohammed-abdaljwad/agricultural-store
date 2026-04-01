<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quoted_by')->constrained('users'); // Admin
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->text('notes')->nullable();         // "Valid for 48 hours"
            $table->timestamp('expires_at')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired'])->default('pending');
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('quoted_by');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_quotes');
    }
};
