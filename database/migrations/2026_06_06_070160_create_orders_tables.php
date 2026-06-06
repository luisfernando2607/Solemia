<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cash_register_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['dine_in', 'takeaway', 'delivery'])->default('dine_in');
            $table->enum('status', ['open', 'sent', 'partial', 'complete', 'cancelled'])->default('open');
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('tip', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('customer_name', 100)->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->text('customer_address')->nullable();
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('table_id');
            $table->index('status');
            $table->index('created_at');
            $table->index('cash_register_id');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->tinyInteger('quantity')->unsigned()->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('modifiers_total', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->string('notes', 255)->nullable();
            $table->enum('kitchen_status', ['pending', 'preparing', 'ready', 'cancelled'])->default('pending');
            $table->string('kitchen_area', 60)->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('cancel_reason', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('kitchen_status');
            $table->index('order_id');
        });

        Schema::create('order_item_modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modifier_option_id')->constrained()->restrictOnDelete();
            $table->string('option_name', 100);
            $table->decimal('extra_price', 10, 2)->default(0);
        });

        Schema::create('order_send_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->json('item_ids');
            $table->timestamp('sent_at')->useCurrent();
        });

        Schema::create('order_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('promotion_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['percent', 'fixed', 'voucher']);
            $table->string('description', 150)->nullable();
            $table->string('voucher_code', 50)->nullable();
            $table->decimal('discount_value', 10, 2);
            $table->foreignId('applied_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->foreignId('cash_register_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('method', ['cash', 'credit_card', 'debit_card', 'bank_transfer', 'qr_wallet', 'internal_credit']);
            $table->decimal('amount', 10, 2);
            $table->decimal('cash_tendered', 10, 2)->nullable();
            $table->decimal('cash_change', 10, 2)->nullable();
            $table->string('reference_number', 100)->nullable();
            $table->string('gateway', 50)->nullable();
            $table->string('gateway_tx_id', 150)->nullable();
            $table->enum('status', ['pending', 'approved', 'failed', 'refunded'])->default('approved');
            $table->foreignId('processed_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('processed_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->enum('type', ['factura', 'nota_venta', 'nota_credito'])->default('factura');
            $table->string('sequential', 20);
            $table->string('access_key', 49)->nullable();
            $table->timestamp('authorization_date')->nullable();
            $table->string('xml_path', 255)->nullable();
            $table->string('ride_path', 255)->nullable();
            $table->enum('sri_status', ['draft', 'sent', 'authorized', 'rejected', 'cancelled'])->default('draft');
            $table->string('customer_name', 150)->nullable();
            $table->string('customer_ruc', 13)->nullable();
            $table->string('customer_email', 150)->nullable();
            $table->string('customer_address', 255)->nullable();
            $table->json('sri_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_discounts');
        Schema::dropIfExists('order_send_logs');
        Schema::dropIfExists('order_item_modifiers');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
