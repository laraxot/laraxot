<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Wts\Models\WtsAuditLog;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WtsAuditLog::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');
            $table->string('action');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            // Nessuna FK verso users: l'attribuzione deve sopravvivere alla
            // cancellazione dell'utente (le FK nullOnDelete la azzererebbero).
            // L'id utente e' un UUID di 36 caratteri, non un intero: un foreignId qui
            // troncherebbe il valore a 0 e perderebbe silenziosamente l'attribuzione.
            $table->string('performed_by_user_id', 36)->nullable();
            $table->string('performed_by_name')->nullable();
            $table->timestamp('performed_at');

            $table->index(['auditable_type', 'auditable_id'], 'wts_audit_logs_auditable_index');
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
