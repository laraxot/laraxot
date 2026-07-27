<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberDocument;
use Modules\TimberBilling\Models\TimberDocumentLine;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberDocumentLine::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(TimberDocument::class, 'document_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(TimberProduct::class, 'product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description');
            $table->decimal('quantity', 14, 3)->default(0);
            $table->string('unit_of_measure')->default('quintali');
            $table->decimal('unit_price', 14, 4)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->string('lot_code')->nullable();
            $table->decimal('line_total', 14, 2)->default(0);
            $table->unsignedInteger('position')->default(0);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
