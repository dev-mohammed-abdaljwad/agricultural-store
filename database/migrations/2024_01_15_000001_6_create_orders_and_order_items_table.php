<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', [
                'placed', 'quote_pending', 'quote_sent', 'quote_accepted',
                'quote_rejected', 'paid', 'preparing', 'out_for_delivery',
                'delivered', 'cancelled', 'returned'
            ])->default('placed');
            $table->decimal('delivery_fee', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->enum('payment_method', ['cash', 'online', 'cod'])->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('supplier_ref')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('delivery_governorate')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('customer_id');
            $table->index('status');
            $table->index('created_at');
        });

        // Create order_items table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->nullable()->default(0);
            $table->decimal('total_price', 10, 2)->nullable()->default(0);
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
