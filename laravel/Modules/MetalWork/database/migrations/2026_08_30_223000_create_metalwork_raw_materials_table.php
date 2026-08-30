<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = MetalRawMaterial::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('description');
            $table->string('supplier_name')->nullable()->index();
            // sheet|tube|beam|flat|angle|round|square|oxy_cut|mesh|service|other
            $table->string('family_code')->default('other')->index();
            // The unit the price is quoted in: kg|ml|mq|nr.
            $table->string('unit_code')->default('kg');
            // S235JR, S275JR, S355J2, DX51, S355J0WP, ...
            $table->string('steel_grade')->nullable()->index();
            $table->decimal('thickness_mm', 10, 2)->nullable();
            $table->decimal('width_mm', 10, 2)->nullable();
            $table->decimal('height_mm', 10, 2)->nullable();
            $table->decimal('length_mm', 10, 2)->nullable();
            // Overrides config('metalwork.default_density_kg_dm3') for stainless
            // or aluminium, which the customer buys occasionally.
            $table->decimal('density_kg_dm3', 8, 3)->nullable();
            // Weight per metre for profiles priced per metre, so a linear cost can
            // still be converted into kilos on the estimate.
            $table->decimal('weight_per_meter_kg', 10, 3)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
