<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Compliance\Models\FGasLeakCheck;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * Ogni controllo fughe va registrato con esito, gas aggiunto/recuperato e
 * patentino di chi ha firmato: e' la traccia che l'autorita' chiede a campione.
 */
return new class() extends XotBaseMigration
{
    /**
     * Il nome derivato dal file sarebbe "FgasLeakCheck": l'acronimo F-Gas si
     * scrive con la G maiuscola, quindi il modello va dichiarato.
     */
    protected ?string $model_class = FGasLeakCheck::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('refrigerant_circuit_id')->constrained('refrigerant_circuits')->cascadeOnDelete();
            // L'id utente e' un UUID di 36 caratteri: una colonna intera lo troncherebbe
            // a 0, perdendo in silenzio chi ha eseguito l'operazione.
            $table->string('performed_by_user_id', 36)->nullable();
            $table->foreignId('intervention_id')->nullable()->constrained('interventions')->nullOnDelete();

            $table->date('performed_on');
            $table->string('outcome', 32)->default('no_leak');
            $table->string('method', 32)->nullable();

            $table->decimal('gas_added_kg', 8, 3)->default(0);
            $table->decimal('gas_recovered_kg', 8, 3)->default(0);

            $table->string('operator_certificate')->nullable();

            $table->date('next_due_on')->nullable();
            $table->text('notes')->nullable();

            $table->index(['refrigerant_circuit_id']);
            $table->index(['performed_on']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
