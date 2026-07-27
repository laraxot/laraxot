<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberLabelPrint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * La migrazione modifica il taglio delle stampe per eliminarne l'unicità.
 *
 * Conformità alle convenzioni XotBaseMigration:
 * - Extends XotBaseMigration invece di Migration
 * - Utilizza $this->getTable() invece di hardcodare il nome della tabella
 * - Usa $this->tableUpdate() per le modifiche alle tabelle esistenti
 */

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberLabelPrint::class;

    public function up(): void
    {
        $this->tableUpdate(function (Blueprint $table): void {
            $table->dropUnique('timber_label_prints_label_code_unique');
            $table->index('label_code');
        });
    }

    public function down(): void
    {
        $this->tableUpdate(function (Blueprint $table): void {
            $table->dropIndex(['label_code']);
            $table->unique('label_code');
        });
    }
};