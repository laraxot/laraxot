<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    /** Pivot table between InventoryMovement and ProductUnit: no dedicated model. */
    protected ?string $model_class = InventoryMovement::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('inventory_movement_id')->constrained('inventory_movements')->cascadeOnDelete();
            $table->foreignId('product_unit_id')->constrained('product_units')->cascadeOnDelete();
            // Pure pivot table (no dedicated model): plain timestamps, no user tracking.
            $table->timestamps();

            $table->unique(['inventory_movement_id', 'product_unit_id'], 'inv_mov_unit_unique');
            $table->index(['product_unit_id']);
        }, 'inventory_movement_product_units');
    }

    public function down(): void
    {
        $this->dropTableIfExists('inventory_movement_product_units');
    }
};
