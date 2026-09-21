<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    /**
     * Table name following Laraxot philosophy.
     */
    protected ?string $table_name = 'ai_messages';

    /**
     * Run the migration following AI module naming standards.
     */
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();

            $table->foreignId('ai_thread_id')->constrained('ai_threads')->cascadeOnDelete();

            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->string('role');

            $table->longText('content')->nullable();

            $table->json('payload')->nullable();

            $this->updateTimestamps($table);
        });
    }
};
