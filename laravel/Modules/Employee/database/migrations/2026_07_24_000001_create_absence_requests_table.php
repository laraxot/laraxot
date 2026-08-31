<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    /**
     * Table name following Laraxot philosophy.
     */
    protected ?string $table_name = 'absence_requests';

    /**
     * Run the migration following Employee module naming standards.
     */
    public function up(): void
    {
        // Nota: nessuna FK verso 'users' perché la tabella users è sulla connessione 'user',
        // mentre absence_requests è sulla connessione 'xot' (workorder_data). MySQL non
        // permette FK cross-database. Le relazioni sono gestite a livello applicativo (Eloquent).
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();

            $table->string('user_id', 36)->index();

            $table->string('type');

            $table->dateTime('starts_at');

            $table->dateTime('ends_at');

            $table->text('notes')->nullable();

            $table->string('status')->default('pending');

            // L'id utente e' un UUID di 36 caratteri: una colonna intera lo troncherebbe
            // a 0, perdendo in silenzio chi ha eseguito l'operazione.
            $table->string('decided_by_user_id', 36)->nullable();

            $table->dateTime('decided_at')->nullable();

            $table->softDeletes();

            $this->updateTimestamps($table);

            $table->index(['user_id', 'starts_at'], 'absence_requests_user_starts_idx');
            $table->index(['status'], 'absence_requests_status_idx');
        });
    }
};
