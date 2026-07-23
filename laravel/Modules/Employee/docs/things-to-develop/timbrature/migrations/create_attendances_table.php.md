<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * Class CreateAttendancesTable.
 *
 * Migrazione per la creazione della tabella attendances.
 * Gestisce presenze, timbrature, calcolo ore e geolocalizzazione.
 */
return new class() extends XotBaseMigration
{
    /**
     * The name of the table.
     */
    protected string $table = 'attendances';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->uuid('uuid')->unique();
                // Relazioni
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->foreignId('work_schedule_id')->nullable()->constrained('work_schedules');
                $table->foreignId('approved_by')->nullable()->constrained('employees');

                // Dati temporali
                $table->date('date');
                $table->time('time_in')->nullable();
                $table->time('time_out')->nullable();

                // Calcoli ore
                $table->decimal('total_hours', 5, 2)->default(0);
                $table->decimal('overtime_hours', 5, 2)->default(0);
                $table->decimal('break_hours', 5, 2)->default(0);

                // Tipo e stato
                $table->enum('type', [
                    'normale',
                    'straordinario',
                    'permesso',
                    'malattia',
                    'smart_working',
                ])->default('normale');

                $table->enum('status', [
                    'registrata',
                    'approvata',
                    'rifiutata',
                ])->default('registrata');

                // Geolocalizzazione
                $table->json('location')->nullable();              // Lat, lng, address
                $table->boolean('location_validated')->default(false);

                // Informazioni dispositivo
                $table->json('device_info')->nullable();           // Browser, IP, user agent

                // Note e motivi
                $table->text('notes')->nullable();
                $table->text('rejection_reason')->nullable();

                // Timestamp approvazione
                $table->timestamp('approved_at')->nullable();

                // Flag lavoro remoto
                $table->boolean('is_remote')->default(false);

                // Timestamps standard
                $table->timestamps();

                // Tipo e stato
                $table->enum('type', [
                    'normale',
                    'straordinario',
                    'permesso',
                    'malattia',
                    'smart_working',
                ])->default('normale');

                $table->enum('status', [
                    'registrata',
                    'approvata',
                    'rifiutata',
                ])->default('registrata');

                // Geolocalizzazione
                $table->json('location')->nullable();              // Lat, lng, address
                $table->boolean('location_validated')->default(false);

                // Informazioni dispositivo
                $table->json('device_info')->nullable();           // Browser, IP, user agent

                // Note e motivi
                $table->text('notes')->nullable();
                $table->text('rejection_reason')->nullable();

                // Timestamp approvazione
                $table->timestamp('approved_at')->nullable();

                // Flag lavoro remoto
                $table->boolean('is_remote')->default(false);

                // Timestamps standard
                $table->timestamps();

                // Indici per performance
                $table->index(['employee_id', 'date']);
                $table->index(['date', 'status']);
                $table->index(['employee_id', 'status']);
                $table->index(['type', 'status']);
                $table->index(['is_remote']);

                // Indice univoco per evitare duplicati
                $table->unique(['employee_id', 'date'], 'unique_employee_date');
            }
        );

        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                // Aggiungi colonne se non esistono
                if (! $this->hasColumn('uuid')) {
                    $table->uuid('uuid')->unique()->after('id');
                }

                if (! $this->hasColumn('work_schedule_id')) {
                    $table->foreignId('work_schedule_id')->nullable()->constrained('work_schedules')->after('employee_id');
                }

                if (! $this->hasColumn('approved_by')) {
                    $table->foreignId('approved_by')->nullable()->constrained('employees')->after('work_schedule_id');
                }

                if (! $this->hasColumn('break_hours')) {
                    $table->decimal('break_hours', 5, 2)->default(0)->after('overtime_hours');
                }

                if (! $this->hasColumn('uuid')) {
                    $table->uuid('uuid')->unique()->after('id');
                }

                if (! $this->hasColumn('work_schedule_id')) {
                    $table->foreignId('work_schedule_id')->nullable()->constrained('work_schedules')->after('employee_id');
                }

                if (! $this->hasColumn('approved_by')) {
                    $table->foreignId('approved_by')->nullable()->constrained('employees')->after('work_schedule_id');
                }

                if (! $this->hasColumn('break_hours')) {
                    $table->decimal('break_hours', 5, 2)->default(0)->after('overtime_hours');
                }

                if (! $this->hasColumn('smart_working')) {
                    // Aggiorna enum type se necessario
                    if ($this->hasColumn('type')) {
                        // Nota: MySQL non permette di modificare enum facilmente
                        // In produzione, considerare una migrazione separata
                    }
                }

                if (! $this->hasColumn('location_validated')) {
                    $table->boolean('location_validated')->default(false)->after('location');
                }

                if (! $this->hasColumn('is_remote')) {
                    $table->boolean('is_remote')->default(false)->after('approved_at');
                }

                if (! $this->hasColumn('rejection_reason')) {
                    $table->text('rejection_reason')->nullable()->after('notes');
                }

                if (! $this->hasColumn('approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('rejection_reason');
                }

                // Aggiorna indici se non esistono
                if (! $this->hasIndex('employee_id')) {
                    $table->index(['employee_id', 'date'], 'idx_employee_date');
                }

                if (! $this->hasIndex('date_status')) {
                    $table->index(['date', 'status'], 'idx_date_status');
                }

                if (! $this->hasIndex('employee_status')) {
                    $table->index(['employee_id', 'status'], 'idx_employee_status');
                }

                if (! $this->hasIndex('type_status')) {
                    $table->index(['type', 'status'], 'idx_type_status');
                }

                if (! $this->hasIndex('is_remote')) {
                    $table->index(['is_remote'], 'idx_is_remote');
                }

                // Aggiorna unique constraint se non esiste
                if (! $this->hasIndex('unique_employee_date')) {
                    $table->unique(['employee_id', 'date'], 'unique_employee_date');
                }

                // Aggiorna timestamps
                $this->updateTimestamps($table, false);
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rimuovi indici
        $this->query('DROP INDEX IF EXISTS idx_employee_date ON attendances');
        $this->query('DROP INDEX IF EXISTS idx_date_status ON attendances');
        $this->query('DROP INDEX IF EXISTS idx_employee_status ON attendances');
        $this->query('DROP INDEX IF EXISTS idx_type_status ON attendances');
        $this->query('DROP INDEX IF EXISTS idx_is_remote ON attendances');
        $this->query('DROP INDEX IF EXISTS unique_employee_date ON attendances');

        // Rimuovi foreign keys
        $this->query('ALTER TABLE attendances DROP FOREIGN KEY IF EXISTS attendances_employee_id_foreign');
        $this->query('ALTER TABLE attendances DROP FOREIGN KEY IF EXISTS attendances_work_schedule_id_foreign');
        $this->query('ALTER TABLE attendances DROP FOREIGN KEY IF EXISTS attendances_approved_by_foreign');

        // Rimuovi tabella
        $this->dropTableIfExists($this->table);
    }
};
