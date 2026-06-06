<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->unique();
            $table->string('name', 100)->nullable();
            $table->json('tags')->nullable();
            $table->boolean('opt_out')->default(false);
            $table->timestamp('opt_out_at')->nullable();
            $table->timestamp('consent_at')->nullable();
            $table->enum('status', ['active', 'blocked', 'opt_out'])->default('active');
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
            $table->index('status');
        });

        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->id();
            $table->string('meta_template_id', 100)->nullable();
            $table->string('name', 100);
            $table->enum('type', ['TEXT', 'IMAGE_TEXT', 'VIDEO_TEXT', 'DOCUMENT', 'CAROUSEL', 'CATALOG'])->default('TEXT');
            $table->string('category', 50)->nullable();
            $table->string('language', 10)->default('es');
            $table->text('body_text');
            $table->string('header_media_url', 255)->nullable();
            $table->json('buttons')->nullable();
            $table->enum('meta_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('whatsapp_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_template_id')->constrained()->restrictOnDelete();
            $table->string('name', 150);
            $table->enum('audience_type', ['all', 'segment', 'manual_list'])->default('all');
            $table->json('audience_criteria')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->enum('recurrence', ['none', 'daily', 'weekly'])->default('none');
            $table->time('recurrence_time')->nullable();
            $table->tinyInteger('recurrence_day')->unsigned()->nullable();
            $table->json('variables')->nullable();
            $table->unsignedSmallInteger('daily_send_limit')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'cancelled'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('whatsapp_campaign_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('whatsapp_campaigns')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('whatsapp_contacts')->cascadeOnDelete();
            $table->string('meta_message_id', 100)->nullable();
            $table->enum('status', ['queued', 'sent', 'delivered', 'read', 'failed', 'opt_out'])->default('queued');
            $table->string('error_code', 20)->nullable();
            $table->string('error_message', 255)->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            $table->index('campaign_id');
            $table->index('status');
        });

        Schema::create('whatsapp_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('whatsapp_contacts')->cascadeOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('direction', ['inbound', 'outbound']);
            $table->enum('message_type', ['text', 'image', 'video', 'document', 'template', 'catalog', 'button', 'location', 'audio'])->default('text');
            $table->text('content')->nullable();
            $table->string('media_url', 255)->nullable();
            $table->string('meta_message_id', 100)->nullable();
            $table->boolean('bot_active')->default(true);
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
            $table->index('contact_id');
            $table->index('sent_at');
        });

        Schema::create('whatsapp_chatbot_flows', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('trigger_expr', 255);
            $table->enum('type', ['keyword', 'button', 'fallback'])->default('keyword');
            $table->json('response');
            $table->foreignId('next_flow_id')->nullable()->constrained('whatsapp_chatbot_flows')->nullOnDelete();
            $table->tinyInteger('sort_order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('whatsapp_catalog_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('meta_item_id', 100)->nullable();
            $table->enum('meta_status', ['active', 'out_of_stock', 'not_synced'])->default('not_synced');
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });

        Schema::create('whatsapp_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('whatsapp_contacts')->restrictOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained('whatsapp_campaigns')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_orders');
        Schema::dropIfExists('whatsapp_catalog_items');
        Schema::dropIfExists('whatsapp_chatbot_flows');
        Schema::dropIfExists('whatsapp_conversations');
        Schema::dropIfExists('whatsapp_campaign_logs');
        Schema::dropIfExists('whatsapp_campaigns');
        Schema::dropIfExists('whatsapp_templates');
        Schema::dropIfExists('whatsapp_contacts');
    }
};
