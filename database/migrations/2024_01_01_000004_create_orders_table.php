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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->restrictOnDelete();
            $table->enum('status', [
                'placed',
                'quote_pending',
                'quote_sent',
                'quote_accepted',
                'quote_rejected',
                'paid',
                'preparing',
                'out_for_delivery',
                'delivered',
                'cancelled',
                'returned',
            ])->default('placed');
            
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->enum('payment_method', ['cod', 'online'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->string('supplier_ref')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('delivery_address');
            $table->string('delivery_governorate');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('customer_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
