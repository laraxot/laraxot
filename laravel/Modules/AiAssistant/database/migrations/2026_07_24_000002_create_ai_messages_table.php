<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $table_name = 'ai_messages';

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $user_class = XotData::make()->getUserClass();

            $table->id();
            $table->foreignId('ai_thread_id')->constrained('ai_threads')->cascadeOnDelete();
            $table->foreignIdFor($user_class, 'user_id')->nullable()->index();
            $table->string('role'); // user|assistant|tool|system
            $table->longText('content')->nullable();
            $table->json('payload')->nullable(); // tool args/results, structured output, etc.
            $this->updateTimestamps($table);
        });
    }
};
