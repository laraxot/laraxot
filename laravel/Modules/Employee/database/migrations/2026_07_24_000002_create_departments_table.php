<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    /**
     * Table name following Laraxot philosophy.
     */
    protected ?string $table_name = 'departments';

    /**
     * Run the migration following Employee module naming standards.
     */
    public function up(): void
    {
        // Nota: 'manager_id' punta a un utente sulla connessione 'user' (tabella users),
        // mentre 'departments' vive sulla connessione 'xot'. MySQL non permette FK cross-database:
        // la relazione e' gestita a livello applicativo (Eloquent).
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();

            $table->string('name');

            $table->text('description')->nullable();

            $table->unsignedBigInteger('manager_id')->nullable()->index();

            $table->boolean('is_active')->default(true);

            $table->string('status')->default('attivo');

            $table->index(['is_active'], 'departments_is_active_idx');
            $table->index(['status'], 'departments_status_idx');
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
