<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_conversations', static function (Blueprint $table): void {
            // The single "live" pending proposal for this conversation (dedup).
            $table->foreignId('active_proposal_id')->nullable()->after('status')
                ->constrained('ai_action_proposals')->nullOnDelete();
            // Checkpoint: last message already handled/approved.
            $table->foreignId('approved_up_to_message_id')->nullable()->after('active_proposal_id')
                ->constrained('whatsapp_messages')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_conversations', static function (Blueprint $table): void {
            $table->dropForeign(['active_proposal_id']);
            $table->dropForeign(['approved_up_to_message_id']);
            $table->dropColumn(['active_proposal_id', 'approved_up_to_message_id']);
        });
    }
};
