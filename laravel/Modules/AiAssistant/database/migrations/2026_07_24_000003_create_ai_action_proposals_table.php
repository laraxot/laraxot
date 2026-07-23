<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $table_name = 'ai_action_proposals';

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignId('ai_thread_id')->constrained('ai_threads')->cascadeOnDelete();
            $table->foreignId('proposed_by_user_id')->constrained('users');
            $table->string('type'); // e.g. start_time_punch, stop_time_punch, upsert_daily_note, change_work_order_status, finalize_intervention_report
            $table->json('payload');
            $table->longText('preview')->nullable();
            $table->string('status')->default('pending'); // pending|cancelled|confirmed|executed|failed
            $table->foreignId('confirmed_by_user_id')->nullable()->constrained('users');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->json('result')->nullable();
            $table->longText('error')->nullable();
            $this->updateTimestamps($table);
        });
    }
};
