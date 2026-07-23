<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('heating_systems', static function (Blueprint $table): void {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();

            $table->string('description')->nullable();
            $table->string('fuel_type')->nullable();               // metano | gpl | gasolio | biomassa | altro
            $table->decimal('total_power_kw', 8, 2)->nullable();
            $table->string('plant_registry_code')->nullable();      // codice catasto / libretto impianto
            $table->date('installation_date')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id']);
        });

        Schema::create('boilers', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('heating_system_id')->constrained('heating_systems')->cascadeOnDelete();

            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('generator_type')->nullable();           // standard | condensazione | pompa_calore | ...
            $table->string('fuel_type')->nullable();
            $table->decimal('power_kw', 8, 2)->nullable();
            $table->date('installation_date')->nullable();

            $table->unsignedInteger('maintenance_interval_months')->nullable();
            $table->unsignedInteger('efficiency_check_interval_months')->nullable();
            $table->date('next_maintenance_due_on')->nullable();
            $table->date('next_efficiency_check_due_on')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['heating_system_id']);
            $table->index(['next_maintenance_due_on']);
            $table->index(['next_efficiency_check_due_on']);
        });

        Schema::create('maintenance_controls', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('heating_system_id')->constrained('heating_systems')->cascadeOnDelete();
            $table->foreignId('boiler_id')->nullable()->constrained('boilers')->nullOnDelete();

            $table->string('control_type');                         // maintenance | efficiency_check
            $table->date('performed_on');
            $table->foreignId('performed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('intervention_id')->nullable()->constrained('interventions')->nullOnDelete();

            $table->string('rcee_type')->nullable();                // RCEE allegato (tipo1 | tipo2 | ...)
            $table->string('outcome')->nullable();                  // conforme | non_conforme

            // Combustion analysis (controllo fumi).
            $table->decimal('co_ppm', 8, 2)->nullable();
            $table->decimal('co2_percent', 5, 2)->nullable();
            $table->decimal('o2_percent', 5, 2)->nullable();
            $table->decimal('flue_gas_temp_c', 6, 2)->nullable();
            $table->decimal('air_temp_c', 6, 2)->nullable();
            $table->decimal('combustion_efficiency_percent', 5, 2)->nullable();
            $table->unsignedTinyInteger('smoke_index')->nullable(); // indice di fumosità (Bacharach 0-9)
            $table->decimal('draft_pa', 6, 2)->nullable();          // tiraggio

            $table->string('bollino_code')->nullable();
            $table->boolean('bollino_paid')->default(false);

            $table->date('next_due_on')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['heating_system_id']);
            $table->index(['control_type']);
            $table->index(['performed_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_controls');
        Schema::dropIfExists('boilers');
        Schema::dropIfExists('heating_systems');
    }
};
