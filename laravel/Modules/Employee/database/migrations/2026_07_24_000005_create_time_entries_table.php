<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    /**
     * Table name following Laraxot philosophy.
     */
    protected ?string $table_name = 'time_entries';

    /**
     * Run the migration following Employee module naming standards.
     */
    public function up(): void
    {
        // Nota: 'employee_id' e 'approved_by' puntano alla tabella users (connessione 'user'),
        // mentre 'time_entries' vive sulla connessione 'xot'. Nessuna FK cross-database:
        // le relazioni sono gestite a livello applicativo (Eloquent).
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('employee_id')->index();

            $table->dateTime('clock_in');

            $table->dateTime('clock_out')->nullable();

            $table->dateTime('break_start')->nullable();

            $table->dateTime('break_end')->nullable();

            $table->unsignedInteger('break_duration')->default(0);

            $table->decimal('total_hours', 8, 2)->nullable();

            $table->decimal('regular_hours', 8, 2)->nullable();

            $table->decimal('overtime_hours', 8, 2)->nullable();

            $table->json('location_in')->nullable();

            $table->json('location_out')->nullable();

            $table->json('device_info')->nullable();

            $table->text('notes')->nullable();

            $table->text('employee_notes')->nullable();

            $table->text('supervisor_notes')->nullable();

            $table->string('status')->default('pending');

            $table->unsignedBigInteger('approved_by')->nullable();

            $table->dateTime('approved_at')->nullable();

            $table->text('rejection_reason')->nullable();

            $table->json('anomalies')->nullable();

            $table->index(['employee_id', 'clock_in'], 'time_entries_employee_clock_in_idx');
            $table->index(['status'], 'time_entries_status_idx');
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
