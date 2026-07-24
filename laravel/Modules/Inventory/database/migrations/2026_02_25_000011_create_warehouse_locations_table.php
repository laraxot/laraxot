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
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->string('code', 64);
            $table->string('label')->nullable();
            $table->unsignedInteger('row')->nullable();
            $table->unsignedInteger('column')->nullable();
            $table->unsignedInteger('level')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();

            $table->unique(['warehouse_id', 'code'], 'warehouse_locations_unique_code');
            $table->index(['warehouse_id', 'is_active']);
            $table->index(['warehouse_id', 'row', 'column', 'level'], 'warehouse_locations_grid_index');
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
