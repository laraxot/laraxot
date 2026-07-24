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
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('serial')->nullable()->unique();
            $table->foreignId('current_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->foreignId('current_location_id')->nullable()->constrained('warehouse_locations')->nullOnDelete();
            $table->string('status')->default('available'); // available|reserved|consumed|scrapped
            $table->json('meta')->nullable();

            $table->index(['product_id']);
            $table->index(['current_warehouse_id']);
            $table->index(['status']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
