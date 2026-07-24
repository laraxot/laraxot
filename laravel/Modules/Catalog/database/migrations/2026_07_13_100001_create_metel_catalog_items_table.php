<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Catalog\Models\MetelCatalogItem;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = MetelCatalogItem::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('metel_catalog_id')->constrained('metel_catalogs')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();

            $table->string('metel_code');
            $table->string('barcode')->nullable();
            $table->string('etim_code')->nullable();
            $table->string('description');
            $table->string('unit_of_measure')->nullable();
            $table->string('packaging')->nullable();

            $table->decimal('gross_price', 14, 4)->default(0);
            $table->decimal('net_price', 14, 4)->nullable();

            $table->unique(['metel_catalog_id', 'metel_code']);
            $table->index(['metel_code']);
            $table->index(['description']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table);
        });
    }
};
