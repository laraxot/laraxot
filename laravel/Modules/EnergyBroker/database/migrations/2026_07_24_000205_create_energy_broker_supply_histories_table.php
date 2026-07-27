<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * MySQL identifier ≤64: auto-index su (supply_point_id, status) supera il limite.
 */
return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('energy_broker_supply_point_id')->constrained(table: 'energy_broker_supply_points', indexName: 'eb_supply_histories_supply_point_id_foreign')->cascadeOnDelete();
            $table->foreignId('energy_broker_document_id')->nullable()->constrained(table: 'energy_broker_documents', indexName: 'eb_supply_histories_document_id_foreign')->nullOnDelete();
            $table->string('supplier_name');
            $table->string('offer_name')->nullable();
            $table->string('offer_code')->nullable();
            $table->string('status')->default('current');
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->json('meta')->nullable();

            $table->index(['energy_broker_supply_point_id', 'status'], 'eb_supply_histories_point_status_idx');

            $this->updateTimestamps($table);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasIndex('eb_supply_histories_point_status_idx')) {
                $table->index(['energy_broker_supply_point_id', 'status'], 'eb_supply_histories_point_status_idx');
            }

            $this->updateTimestamps($table);
        });
    }
};
