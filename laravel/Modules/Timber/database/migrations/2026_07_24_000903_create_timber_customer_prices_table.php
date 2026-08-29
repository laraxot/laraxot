<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberCustomerPrice;
use Modules\Timber\Models\TimberProduct;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberCustomerPrice::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(TimberCustomer::class, 'customer_id')->constrained('timber_customers')->cascadeOnDelete();
            $table->foreignIdFor(TimberProduct::class, 'product_id')->constrained('timber_products')->cascadeOnDelete();
            $table->decimal('net_price', 14, 4)->default(0);

            $table->unique(['customer_id', 'product_id']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
