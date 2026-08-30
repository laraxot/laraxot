<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * Un circuito refrigerante e' un'entita' a se', non un attributo della caldaia:
 * un impianto puo' contenerne piu' d'uno e l'obbligo di controllo fughe segue il
 * circuito (per il suo CO2 equivalente), non l'apparecchio.
 */
return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('heating_system_id')->nullable()->constrained('heating_systems')->nullOnDelete();

            $table->string('code')->nullable()->unique();
            $table->string('description')->nullable();
            $table->string('location')->nullable();

            $table->string('refrigerant_code', 16);
            $table->decimal('charge_kg', 8, 3)->default(0);
            $table->decimal('co2_equivalent_tonnes', 10, 3)->nullable();

            $table->boolean('is_hermetically_sealed')->default(false);
            $table->boolean('has_leak_detection_system')->default(false);

            $table->unsignedInteger('check_interval_months')->nullable();
            $table->date('last_leak_check_on')->nullable();
            $table->date('next_leak_check_due_on')->nullable();

            $table->date('installation_date')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            $table->index(['customer_id']);
            $table->index(['next_leak_check_due_on']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
