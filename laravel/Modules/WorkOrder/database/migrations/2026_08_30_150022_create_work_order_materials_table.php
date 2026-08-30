<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();

            $table->string('description');
            $table->string('unit_code')->nullable();
            $table->decimal('quantity', 12, 3)->default(1);
            $table->decimal('unit_cost', 12, 4)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->string('source_code')->default('manual');
            $table->text('notes')->nullable();
            $table->unsignedInteger('sort_order')->default(0);

            $table->index(['work_order_id']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
