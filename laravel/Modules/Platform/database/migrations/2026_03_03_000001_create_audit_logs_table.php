<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $table_name = 'audit_logs';

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();

            $table->timestamp('created_at')->useCurrent();

            // Platform's models live on the 'xot' connection, while User lives on a
            // separate 'user' connection (possibly a different database entirely).
            // A DB-level FK constraint across connections is not enforceable, so we
            // keep this as a plain indexed column instead of ->constrained().
            $table->uuid('actor_user_id')->nullable();
            $table->string('action');

            $table->string('subject_type');
            $table->string('subject_id');

            $table->string('panel_id')->nullable();
            $table->string('route')->nullable();
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();

            $table->json('before')->nullable();
            $table->json('after')->nullable();

            $table->index(['created_at']);
            $table->index(['actor_user_id', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
            $table->index(['action', 'created_at']);
        });
    }
};
