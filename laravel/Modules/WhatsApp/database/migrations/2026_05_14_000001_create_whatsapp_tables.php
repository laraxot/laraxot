<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_contact_identities', static function (Blueprint $table): void {
            $table->id();
            $table->string('phone_e164')->unique();
            $table->string('wa_id')->nullable()->index();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('whatsapp_conversations', static function (Blueprint $table): void {
            $table->id();
            $table->string('phone_e164')->index();
            $table->string('wa_id')->nullable()->index();
            $table->string('display_name')->nullable();
            $table->foreignId('identity_id')->nullable()->constrained('whatsapp_contact_identities')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('open');
            $table->timestamp('last_message_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['phone_e164']);
        });

        Schema::create('whatsapp_messages', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')->constrained('whatsapp_conversations')->cascadeOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->foreignId('intervention_id')->nullable()->constrained('interventions')->nullOnDelete();
            $table->foreignId('ai_action_proposal_id')->nullable()->constrained('ai_action_proposals')->nullOnDelete();
            $table->string('meta_message_id')->nullable()->unique();
            $table->string('direction');
            $table->string('from_number')->nullable();
            $table->string('to_number')->nullable();
            $table->string('message_type')->default('text');
            $table->longText('body')->nullable();
            $table->string('status')->default('received');
            $table->string('processing_status')->default('pending');
            $table->json('payload')->nullable();
            $table->json('ai_result')->nullable();
            $table->longText('error')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
            $table->index(['direction']);
            $table->index(['processing_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
        Schema::dropIfExists('whatsapp_conversations');
        Schema::dropIfExists('whatsapp_contact_identities');
    }
};
