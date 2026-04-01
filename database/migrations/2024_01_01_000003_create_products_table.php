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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit')->nullable();
            $table->integer('min_order_qty')->default(1);
            $table->boolean('is_certified')->default(false);

            // Tabs — displayed to customer
            $table->string('data_sheet_url')->nullable();
            $table->text('usage_instructions')->nullable();
            $table->text('safety_instructions')->nullable();
            $table->text('manufacturer_info')->nullable();

            // Expert tip — displayed to customer
            $table->text('expert_tip')->nullable();
            $table->string('expert_name')->nullable();
            $table->string('expert_title')->nullable();
            $table->string('expert_image_url')->nullable();

            // Supplier info — hidden from customers
            $table->string('supplier_name')->nullable();
            $table->string('supplier_code')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('category_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
