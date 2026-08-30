<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Costing\Models\WorkCenter;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * Una sola tabella per i centri di lavoro di tutti i moduli di preventivazione.
 * `context_code` li tiene separati: sullo stesso install girano aziende diverse e
 * un'insegneria non deve mai vedere i centri di una carpenteria nei suoi elenchi.
 */
return new class() extends XotBaseMigration
{
    /**
     * Il nome derivato dal file sarebbe "CostingWorkCenter": il prefisso della
     * tabella e' il modulo, il modello si chiama solo WorkCenter.
     */
    protected ?string $model_class = WorkCenter::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('context_code')->index(); // signage|metalwork
            $table->string('code');
            $table->string('name');
            $table->boolean('is_internal')->default(true);

            // Quanto costa un'ora di questo centro. Le insegne prezzano la
            // manodopera al minuto tramite il listino fornitori e lasciano zero.
            $table->decimal('hourly_rate', 12, 2)->default(0);
            $table->decimal('default_markup_percent', 8, 2)->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();

            $table->unique(['context_code', 'code']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
