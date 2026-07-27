<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\Intervention\Models\Intervention;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WhatsAppMessage::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(WhatsAppConversation::class, 'conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(WorkOrder::class, 'work_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Intervention::class, 'intervention_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(AiActionProposal::class, 'ai_action_proposal_id')->nullable()->constrained()->nullOnDelete();
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

            $table->index(['direction']);
            $table->index(['processing_status']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
            $table->index(['conversation_id', 'created_at']);
        });

        // Checkpoint circolare: whatsapp_conversations.approved_up_to_message_id -> whatsapp_messages.
        // Aggiunta qui (non nella migration di WhatsAppConversation) perché questa tabella
        // non esiste ancora quando whatsapp_conversations viene creata (ordine timestamp).
        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->getConn()->hasColumn('whatsapp_conversations', 'approved_up_to_message_id')) {
                $table->foreignIdFor(WhatsAppMessage::class, 'approved_up_to_message_id')
                    ->nullable()
                    ->after('active_proposal_id')
                    ->constrained()
                    ->nullOnDelete();
            }
        }, 'whatsapp_conversations');
    }
};
