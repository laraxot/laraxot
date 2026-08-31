<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    /**
     * Table name following Laraxot philosophy.
     */
    protected ?string $table_name = 'time_records';

    /**
     * Run the migration following Employee module naming standards.
     */
    public function up(): void
    {
        // Nota: 'user_id', 'created_by' e 'updated_by' puntano alla tabella users (connessione 'user'),
        // mentre 'time_records' vive sulla connessione 'xot'. Nessuna FK cross-database:
        // le relazioni sono gestite a livello applicativo (Eloquent).
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();

            // L'id utente e' un UUID di 36 caratteri: una colonna intera lo troncherebbe
            // a 0, legando la riga all'utente sbagliato o a nessuno.
            $table->string('user_id', 36)->index();

            $table->dateTime('timestamp');

            $table->string('type');

            $table->string('method');

            $table->string('latitude')->nullable();

            $table->string('longitude')->nullable();

            $table->string('address')->nullable();

            $table->text('notes')->nullable();

            $table->string('status')->default('valid');

            $table->boolean('is_manual')->default(false);

            $table->unsignedBigInteger('created_by')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();

            $table->index(['user_id', 'timestamp'], 'time_records_user_timestamp_idx');
            $table->index(['type'], 'time_records_type_idx');
            $table->index(['status'], 'time_records_status_idx');
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
