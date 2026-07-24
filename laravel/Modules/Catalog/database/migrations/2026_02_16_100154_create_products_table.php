<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Catalog\Models\Product;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = Product::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();

            $table->string('name');
            $table->string('sku')->nullable()->unique();
            $table->text('description')->nullable();

            $table->foreignId('catalog_model_id')->nullable()->constrained('catalog_models')->nullOnDelete();
            $table->foreignId('catalog_part_type_id')->nullable()->constrained('catalog_part_types')->nullOnDelete();
            $table->foreignId('catalog_measure_unit_id')->nullable()->constrained('catalog_measure_units')->nullOnDelete();
            $table->foreignId('catalog_frequency_id')->nullable()->constrained('catalog_frequencies')->nullOnDelete();

            $table->decimal('unit_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);

            $table->index(['name']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
