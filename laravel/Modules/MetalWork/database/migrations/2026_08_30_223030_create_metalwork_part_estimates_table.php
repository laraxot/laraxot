<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\MetalWork\Models\MetalPartEstimate;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = MetalPartEstimate::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('drawing_id')
                ->nullable()
                ->constrained('metalwork_drawings')
                ->nullOnDelete();
            $table->foreignId('raw_material_id')
                ->nullable()
                ->constrained('metalwork_raw_materials')
                ->nullOnDelete();
            $table->foreignId('quotation_id')
                ->nullable()
                ->constrained('quotations')
                ->nullOnDelete();
            $table->string('part_code')->index();
            $table->string('name');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('thickness_mm', 10, 2)->nullable();
            $table->decimal('occupancy_percent', 8, 2)->nullable();
            $table->decimal('scrap_percent', 8, 2)->nullable();
            $table->decimal('net_weight_kg', 12, 3)->default(0);
            $table->decimal('gross_weight_kg', 12, 3)->default(0);
            $table->decimal('total_cost', 14, 2)->default(0);
            $table->decimal('sale_price', 14, 2)->default(0);
            // Hand-written price. When set it wins over anything computed.
            $table->decimal('manual_sale_price', 14, 2)->nullable();
            $table->decimal('markup_percent', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
