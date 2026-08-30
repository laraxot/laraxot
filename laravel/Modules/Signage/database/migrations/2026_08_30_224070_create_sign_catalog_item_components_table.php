<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Signage\Models\SignCatalogItemComponent;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = SignCatalogItemComponent::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sign_catalog_item_id')->constrained('sign_catalog_items')->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->string('work_center_code')->index();
            $table->foreignId('sign_price_list_item_id')->nullable()->constrained('sign_price_list_items')->nullOnDelete();
            $table->string('description');
            $table->string('unit_code');
            $table->decimal('quantity', 12, 3)->default(0);
            $table->decimal('width_mm', 12, 2)->nullable();
            $table->decimal('height_mm', 12, 2)->nullable();
            $table->decimal('unit_cost', 12, 4)->default(0);
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
