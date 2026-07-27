<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Modules\Vehicle\Models\Vehicle;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * Warehouse — magazzino fisso o mobile (furgone).
 *
 * Politica FK `vehicle_id`: stessa connection di Vehicle (`xot`), ma parent
 * cross-modulo. `constrained()` solo dopo che `vehicles` esiste (timestamp
 * migration > create_vehicles). Riparazione idempotente in coda a `up()`.
 */
return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            // Colonna dal modello — niente constrained qui: create Laravel fa
            // ALTER FK dopo CREATE; se `vehicles` manca → MySQL 1824 e tabella orfana.
            $table->foreignIdFor(Vehicle::class)->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_final_destination')->default(false);

            $table->index(['name']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('vehicle_id')) {
                $table->foreignIdFor(Vehicle::class)->nullable()->index();
            }

            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });

        $this->ensureVehicleForeignKey();
    }

    /**
     * Vincolo DB solo se parent pronto e vincolo assente (migrate riparabile).
     */
    private function ensureVehicleForeignKey(): void
    {
        if (! $this->hasTable('vehicles') || ! $this->hasColumn('vehicle_id')) {
            return;
        }

        if ($this->hasVehicleForeignKey()) {
            return;
        }

        $this->getConn()->table('warehouses', function (Blueprint $table): void {
            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->nullOnDelete();
        });
    }

    private function hasVehicleForeignKey(): bool
    {
        $database = $this->getConn()->getConnection()->getDatabaseName();

        $rows = DB::connection($this->getConnection())->select(
            'select 1 as ok
             from information_schema.TABLE_CONSTRAINTS
             where CONSTRAINT_SCHEMA = ?
               and TABLE_NAME = ?
               and CONSTRAINT_NAME = ?
               and CONSTRAINT_TYPE = ?
             limit 1',
            [$database, 'warehouses', 'warehouses_vehicle_id_foreign', 'FOREIGN KEY'],
        );

        return $rows !== [];
    }
};
