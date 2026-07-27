<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Billing\Models\SupplierInvoiceLine;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = SupplierInvoiceLine::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_invoice_id')->constrained('supplier_invoices')->cascadeOnDelete();
            // Riga di DDT a cui questa riga di fattura e' stata agganciata.
            $table->foreignId('delivery_note_line_id')->nullable()->constrained('delivery_note_lines')->nullOnDelete();
            $table->string('description');
            $table->decimal('quantity', 12, 2)->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);

            $table->index(['supplier_invoice_id']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table);
        });
    }
};
