<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = SignCatalogItem::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sign_price_list_id')->constrained('sign_price_lists')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('family_code')->nullable();
            $table->string('color_code')->nullable();
            $table->decimal('thickness_mm', 8, 2)->nullable();
            $table->unsignedInteger('letter_height_min_mm')->nullable();
            $table->unsignedInteger('letter_height_max_mm')->nullable();
            $table->string('led_temperature_code')->nullable();
            $table->string('unit_code')->default('nr');
            // Overrides the work-centre markup for every row of this distinta.
            $table->decimal('markup_percent', 8, 2)->nullable();
            // Hand-written price. When set it wins over anything computed.
            $table->decimal('manual_sale_price', 12, 2)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
