<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = MetelCatalog::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();

            $table->string('code')->unique();
            $table->string('brand_code')->nullable();
            $table->string('name');
            $table->string('manufacturer')->nullable();

            $table->string('source_type')->default('file');
            $table->string('version')->nullable();
            $table->dateTime('imported_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->index(['brand_code']);
            $table->index(['is_active']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table);
        });
    }
};
