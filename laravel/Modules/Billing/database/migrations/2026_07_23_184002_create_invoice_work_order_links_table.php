<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Billing\Models\InvoiceWorkOrderLink;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = InvoiceWorkOrderLink::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();

            $table->unique(['invoice_id', 'work_order_id']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table);
        });
    }
};
