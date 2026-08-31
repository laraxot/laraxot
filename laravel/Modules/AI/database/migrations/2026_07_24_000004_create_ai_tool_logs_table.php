<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    /**
     * Table name following Laraxot philosophy.
     */
    protected ?string $table_name = 'ai_tool_logs';

    /**
     * Run the migration following AI module naming standards.
     */
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();

            $table->foreignId('ai_thread_id')->constrained('ai_threads')->cascadeOnDelete();

            $table->foreignId('ai_action_proposal_id')->nullable()->constrained('ai_action_proposals')->nullOnDelete();

            // L'id utente e' un UUID di 36 caratteri: una colonna intera lo troncherebbe
            // a 0, legando la riga all'utente sbagliato o a nessuno.
            $table->string('user_id', 36)->nullable()->index();

            $table->string('tool_name');

            $table->json('arguments')->nullable();

            $table->json('response')->nullable();

            $table->string('status')->default('ok');

            $table->longText('error')->nullable();

            $this->updateTimestamps($table);
        });
    }
};
