<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained('timber_invoices')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('description');
            $table->decimal('quantity', 14, 3)->default(0);
            $table->string('unit_of_measure')->default('quintali');
            $table->decimal('unit_price', 14, 4)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(22);
            $table->string('lot_code')->nullable();
            $table->decimal('line_total', 14, 2)->default(0);
            $table->unsignedInteger('position')->default(0);

            $this->updateTimestamps($table);
        });
    }
};
