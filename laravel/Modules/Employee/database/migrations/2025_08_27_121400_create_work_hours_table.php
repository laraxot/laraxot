<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    /**
     * Table name following Laraxot philosophy.
     */
    protected ?string $table_name = 'work_hours';

    /**
     * Run the migration following Employee module naming standards.
     */
    public function up(): void
    {
        // Check if table already exists
        if ($this->tableExists($this->table_name)) {
            return;
        }

        // -- CREATE --
        // Nota: nessuna FK verso 'users' perché la tabella users è sulla connessione 'user',
        // mentre work_hours è sulla connessione 'xot' (workorder_data). MySQL non permette
        // FK cross-database. Le relazioni sono gestite a livello applicativo (Eloquent).
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();

            $table->uuid('employee_id')->index();

            $table->enum('type', ['clock_in', 'clock_out', 'break_start', 'break_end']);

            $table->dateTime('timestamp');

            $table->decimal('location_lat', 10, 8)->nullable();

            $table->decimal('location_lng', 11, 8)->nullable();

            $table->string('location_name')->nullable();

            $table->json('device_info')->nullable();

            $table->string('photo_path')->nullable();

            $table->text('notes')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->uuid('approved_by')->nullable()->index();

            $table->dateTime('approved_at')->nullable();

            $table->timestamps();

            // Performance indexes
            $table->index(['employee_id', 'timestamp'], 'work_hours_employee_timestamp_idx');
            $table->index(['timestamp', 'type'], 'work_hours_timestamp_type_idx');
            $table->index(['status'], 'work_hours_status_idx');

            // Prevent duplicate entries within same minute
            $table->unique(['employee_id', 'timestamp', 'type'], 'work_hours_unique_entry');
        });
    }
};
