<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    /**
     * Table name following Laraxot philosophy.
     */
    protected ?string $table_name = 'ai_action_proposals';

    /**
     * Run the migration following AI module naming standards.
     *
     * Stato: pending|cancelled|confirmed|executed|failed — pattern
     * "AI propone, umano conferma, sistema esegue".
     */
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();

            $table->uuid('public_id')->unique();

            $table->foreignId('ai_thread_id')->constrained('ai_threads')->cascadeOnDelete();

            $table->unsignedBigInteger('proposed_by_user_id')->index();

            $table->string('type');

            $table->json('payload');

            $table->longText('preview')->nullable();

            $table->string('status')->default('pending');

            $table->unsignedBigInteger('confirmed_by_user_id')->nullable();

            $table->dateTime('confirmed_at')->nullable();

            $table->dateTime('executed_at')->nullable();

            $table->json('result')->nullable();

            $table->longText('error')->nullable();

            $this->updateTimestamps($table);
        });
    }
};
