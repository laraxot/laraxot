<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Models\TimberStockMovement;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberStockMovement::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->date('movement_date')->index();
            $table->foreignIdFor(TimberProduct::class, 'product_id')->constrained()->cascadeOnDelete();
            $table->string('direction_code')->index();
            $table->decimal('quantity', 14, 3);
            $table->string('unit_of_measure');
            $table->foreignIdFor(TimberCustomer::class, 'customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_type_code')->nullable()->index();
            $table->string('document_reference')->nullable();
            $table->string('lot_code')->nullable()->index();
            $table->string('category')->nullable();
            $table->decimal('unit_cost', 14, 4)->nullable();
            $table->text('notes')->nullable();
            $table->foreignIdFor($userClass, 'created_by_user_id')->nullable()->index();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
