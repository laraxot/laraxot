<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('heating_system_id')->constrained('heating_systems')->cascadeOnDelete();

            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('generator_type')->nullable(); // standard | condensazione | pompa_calore | ...
            $table->string('fuel_type')->nullable();
            $table->decimal('power_kw', 8, 2)->nullable();
            $table->date('installation_date')->nullable();

            $table->unsignedInteger('maintenance_interval_months')->nullable();
            $table->unsignedInteger('efficiency_check_interval_months')->nullable();
            $table->date('next_maintenance_due_on')->nullable();
            $table->date('next_efficiency_check_due_on')->nullable();

            $table->text('notes')->nullable();

            $table->index(['heating_system_id']);
            $table->index(['next_maintenance_due_on']);
            $table->index(['next_efficiency_check_due_on']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
