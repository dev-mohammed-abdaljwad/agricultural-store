<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();
        });

        // Create products table
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit')->default('kg');
            $table->integer('min_order_qty')->default(1);
            $table->boolean('is_certified')->default(false);
            $table->string('data_sheet_url')->nullable();
            $table->text('usage_instructions')->nullable();
            $table->text('safety_instructions')->nullable();
            $table->text('manufacturer_info')->nullable();
            $table->text('expert_tip')->nullable();
            $table->string('expert_name')->nullable();
            $table->string('expert_title')->nullable();
            $table->string('expert_image_url')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_code')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('category_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
