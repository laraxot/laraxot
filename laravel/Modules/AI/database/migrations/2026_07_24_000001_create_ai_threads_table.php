<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    /**
     * Table name following Laraxot philosophy.
     */
    protected ?string $table_name = 'ai_threads';

    /**
     * Run the migration following AI module naming standards.
     *
     * Nota: nessuna FK verso 'users' perché la tabella users è sulla connessione 'user',
     * mentre ai_threads è sulla connessione 'xot'. MySQL non permette FK cross-database.
     */
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();

            $table->uuid('public_id')->unique();

            // L'id utente e' un UUID di 36 caratteri: una colonna intera lo troncherebbe
            // a 0, perdendo in silenzio chi ha eseguito l'operazione.
            $table->string('created_by_user_id', 36)->index();

            $table->string('panel_id')->default('operator');

            $table->dateTime('last_message_at')->nullable();

            $table->json('meta')->nullable();

            $this->updateTimestamps($table);
        });
    }
};
