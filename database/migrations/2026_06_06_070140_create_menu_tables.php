<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name', 100);
            $table->string('image_path', 255)->nullable();
            $table->tinyInteger('sort_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('available_shifts')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('sku', 50)->nullable()->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('image_path', 255)->nullable();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('takeaway_price', 10, 2)->nullable();
            $table->decimal('happy_hour_price', 10, 2)->nullable();
            $table->json('tags')->nullable();
            $table->tinyInteger('prep_time_minutes')->unsigned()->default(10);
            $table->string('kitchen_area', 60)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);
            $table->boolean('auto_disable_on_stock')->default(false);
            $table->boolean('available_dine_in')->default(true);
            $table->boolean('available_takeaway')->default(true);
            $table->boolean('available_delivery')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('category_id');
            $table->index('is_active');
        });

        Schema::create('modifier_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('type', ['single', 'multiple', 'required'])->default('single');
            $table->tinyInteger('min_options')->unsigned()->default(0);
            $table->tinyInteger('max_options')->unsigned()->default(1);
            $table->tinyInteger('sort_order')->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create('product_modifier_group', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modifier_group_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('sort_order')->unsigned()->default(0);
            $table->primary(['product_id', 'modifier_group_id']);
        });

        Schema::create('modifier_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modifier_group_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->decimal('extra_price', 10, 2)->default(0);
            $table->tinyInteger('sort_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->enum('type', ['combo', 'percent_discount', 'fixed_discount']);
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->time('active_from_time')->nullable();
            $table->time('active_to_time')->nullable();
            $table->string('channel', 100)->default('dine_in,takeaway,delivery');
            $table->boolean('is_automatic')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('promotion_products', function (Blueprint $table) {
            $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('quantity')->unsigned()->default(1);
            $table->primary(['promotion_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_products');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('modifier_options');
        Schema::dropIfExists('product_modifier_group');
        Schema::dropIfExists('modifier_groups');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
