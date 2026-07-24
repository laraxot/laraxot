<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('type');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->text('notes')->nullable();

            $table->string('status')->default('pending');
            $table->foreignUuid('decided_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('decided_at')->nullable();

            $table->index(['user_id', 'starts_at']);
            $table->index(['status']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
