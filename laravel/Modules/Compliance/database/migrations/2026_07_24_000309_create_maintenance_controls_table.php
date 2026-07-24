<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('heating_system_id')->constrained('heating_systems')->cascadeOnDelete();
            $table->foreignId('boiler_id')->nullable()->constrained('boilers')->nullOnDelete();

            $table->string('control_type'); // maintenance | efficiency_check
            $table->date('performed_on');
            $table->foreignId('performed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('intervention_id')->nullable()->constrained('interventions')->nullOnDelete();

            $table->string('rcee_type')->nullable(); // RCEE allegato (tipo1 | tipo2 | ...)
            $table->string('outcome')->nullable(); // conforme | non_conforme

            // Combustion analysis (controllo fumi).
            $table->decimal('co_ppm', 8, 2)->nullable();
            $table->decimal('co2_percent', 5, 2)->nullable();
            $table->decimal('o2_percent', 5, 2)->nullable();
            $table->decimal('flue_gas_temp_c', 6, 2)->nullable();
            $table->decimal('air_temp_c', 6, 2)->nullable();
            $table->decimal('combustion_efficiency_percent', 5, 2)->nullable();
            $table->unsignedTinyInteger('smoke_index')->nullable(); // indice di fumosità (Bacharach 0-9)
            $table->decimal('draft_pa', 6, 2)->nullable(); // tiraggio

            $table->string('bollino_code')->nullable();
            $table->boolean('bollino_paid')->default(false);

            $table->date('next_due_on')->nullable();
            $table->text('notes')->nullable();

            $table->index(['heating_system_id']);
            $table->index(['control_type']);
            $table->index(['performed_on']);

            $this->updateTimestamps($table);
        });
    }
};
