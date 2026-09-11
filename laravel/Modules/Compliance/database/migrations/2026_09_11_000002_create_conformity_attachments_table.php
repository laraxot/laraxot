<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * Gli allegati della Dichiarazione di Conformita'.
 *
 * Una riga per ogni allegato previsto, creata anche quando il documento non
 * c'e' ancora: il fascicolo si legge come una lista di cose da fare, e la riga
 * vuota e' il promemoria. E' DetermineRequiredAttachmentsAction a decidere
 * quali di queste righe siano davvero dovute per questa dichiarazione.
 */
return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('declaration_id')->constrained('conformity_declarations')->cascadeOnDelete();

            // project, materials_report, plant_diagram, previous_declaration,
            // company_certificate, non_standard_attestation, use_instructions,
            // verification_report
            $table->string('kind', 40);
            $table->string('title')->nullable();
            $table->string('file_path')->nullable();

            // Se il documento sta gia' nel fascicolo della commessa, invece di
            // ricaricarlo si punta a quello.
            $table->foreignId('compliance_document_id')->nullable()->constrained('compliance_documents')->nullOnDelete();

            $table->text('note')->nullable();

            $table->index(['declaration_id', 'kind']);

            $this->updateTimestamps($table);
        });
    }
};
