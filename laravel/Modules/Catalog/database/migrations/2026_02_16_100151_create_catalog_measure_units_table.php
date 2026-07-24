<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Catalog\Models\CatalogMeasureUnit;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = CatalogMeasureUnit::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('symbol')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table);
        });
    }
};
