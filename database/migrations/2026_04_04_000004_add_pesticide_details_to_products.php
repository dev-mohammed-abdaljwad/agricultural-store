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
        // Create crops table
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // اسم المحصول
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // Pivot table for product-crop relationship
        Schema::create('crop_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('crop_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['product_id', 'crop_id']);
        });

        // Add new columns to products table
        Schema::table('products', function (Blueprint $table) {
            // Product Details
            $table->text('chemical_composition')->nullable()->after('description'); // التركيبة الكيميائية
            $table->text('package_sizes')->nullable()->after('chemical_composition'); // أحجام العبوة
            $table->text('how_it_works')->nullable()->after('package_sizes'); // آلية العمل
            $table->text('extended_description')->nullable()->after('how_it_works'); // وصف موسع
            $table->string('frac_group')->nullable()->after('extended_description'); // مجموعة FRAC
            $table->text('benefits')->nullable()->after('frac_group'); // الفوائد
            $table->text('usage_recommendations')->nullable()->after('benefits'); // التوصيات المحلية
            $table->text('safety_notice')->nullable()->after('usage_recommendations'); // تحذيرات الاستخدام
            $table->string('registration_number')->nullable()->after('safety_notice'); // رقم التسجيل
            
            // Gallery support is already handled by images table (products has many images)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'chemical_composition',
                'package_sizes',
                'how_it_works',
                'extended_description',
                'frac_group',
                'benefits',
                'usage_recommendations',
                'safety_notice',
                'registration_number',
            ]);
        });

        Schema::dropIfExists('crop_product');
        Schema::dropIfExists('crops');
    }
};
