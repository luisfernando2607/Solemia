<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('description', 255)->nullable();
            $table->unsignedSmallInteger('total_capacity')->nullable();
            $table->tinyInteger('sort_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained()->restrictOnDelete();
            $table->string('number', 10);
            $table->tinyInteger('capacity')->unsigned()->default(4);
            $table->enum('shape', ['square', 'round', 'rectangle'])->default('square');
            $table->smallInteger('pos_x')->default(0);
            $table->smallInteger('pos_y')->default(0);
            $table->unsignedSmallInteger('width')->default(80);
            $table->unsignedSmallInteger('height')->default(80);
            $table->enum('status', ['available', 'occupied', 'reserved', 'billing', 'blocked'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['zone_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables');
        Schema::dropIfExists('zones');
    }
};
