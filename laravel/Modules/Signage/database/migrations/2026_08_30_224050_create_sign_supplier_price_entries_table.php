<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Signage\Models\SignSupplierPriceEntry;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = SignSupplierPriceEntry::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sign_price_list_item_id')->constrained('sign_price_list_items')->cascadeOnDelete();
            $table->string('invoice_number')->nullable();
            $table->date('recorded_on');
            $table->decimal('amount', 12, 4);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
