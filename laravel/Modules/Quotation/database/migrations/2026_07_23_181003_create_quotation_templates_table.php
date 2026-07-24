<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Quotation\Models\QuotationTemplate;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = QuotationTemplate::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->string('title')->unique();
            $table->text('notes')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
