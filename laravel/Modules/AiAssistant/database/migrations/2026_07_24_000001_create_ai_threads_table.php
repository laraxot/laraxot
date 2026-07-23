<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $table_name = 'ai_threads';

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->string('panel_id')->default('operator');
            $table->timestamp('last_message_at')->nullable();
            $table->json('meta')->nullable();
            $this->updateTimestamps($table);
        });
    }
};
