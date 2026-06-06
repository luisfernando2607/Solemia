<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_settings', function (Blueprint $table) {
            $table->id();
            $table->string('trade_name', 150);
            $table->string('legal_name', 150)->nullable();
            $table->string('ruc', 13)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('logo_path', 255)->nullable();
            $table->char('currency', 3)->default('USD');
            $table->string('timezone', 60)->default('America/Guayaquil');
            $table->string('date_format', 20)->default('Y-m-d');
            $table->char('decimal_separator', 1)->default('.');
            $table->decimal('tax_rate', 5, 2)->default(15.00);
            $table->boolean('service_charge_active')->default(false);
            $table->decimal('service_charge_rate', 5, 2)->default(0.00);
            $table->json('tip_suggestions')->nullable();
            $table->enum('sri_environment', ['test', 'production'])->default('test');
            $table->string('sri_certificate_path', 255)->nullable();
            $table->string('sri_certificate_pass', 255)->nullable();
            $table->enum('sri_taxpayer_type', ['rimpe', 'contribuyente_especial', 'otro'])->default('otro');
            $table->tinyInteger('kds_alert_minutes')->unsigned()->default(10);
            $table->json('session_timeout_json')->nullable();
            $table->timestamps();
        });

        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('shift_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique(['shift_id', 'user_id']);
        });

        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->enum('type', ['thermal_escpos', 'laser'])->default('thermal_escpos');
            $table->string('ip_address', 45);
            $table->unsignedSmallInteger('port')->default(9100);
            $table->string('model', 80)->nullable();
            $table->string('printer_function', 100)->default('ticket_client');
            $table->string('kitchen_area', 60)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printers');
        Schema::dropIfExists('shift_user');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('restaurant_settings');
    }
};
