<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $table_name = 'ai_tool_logs';

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ai_thread_id')->constrained('ai_threads')->cascadeOnDelete();
            $table->foreignId('ai_action_proposal_id')->nullable()->constrained('ai_action_proposals')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('tool_name');
            $table->json('arguments')->nullable();
            $table->json('response')->nullable();
            $table->string('status')->default('ok'); // ok|error
            $table->longText('error')->nullable();
            $this->updateTimestamps($table);
        });
    }
};
