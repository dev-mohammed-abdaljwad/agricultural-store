<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
            
            $table->index('pricing_quote_id');
            $table->index('order_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_quote_items');
    }
};
