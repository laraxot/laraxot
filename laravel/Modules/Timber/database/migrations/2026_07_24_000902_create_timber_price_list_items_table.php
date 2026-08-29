<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Timber\Models\TimberPriceList;
use Modules\Timber\Models\TimberPriceListItem;
use Modules\Timber\Models\TimberProduct;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberPriceListItem::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(TimberPriceList::class, 'price_list_id')->constrained('timber_price_lists')->cascadeOnDelete();
            $table->foreignIdFor(TimberProduct::class, 'product_id')->constrained('timber_products')->cascadeOnDelete();
            $table->decimal('net_price', 14, 4)->default(0);

            $table->unique(['price_list_id', 'product_id']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
