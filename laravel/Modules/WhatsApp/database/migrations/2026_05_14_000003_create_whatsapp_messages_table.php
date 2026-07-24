<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
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

            $table->index(['conversation_id', 'created_at']);
            $table->index(['direction']);
            $table->index(['processing_status']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
