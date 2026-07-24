<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Billing\Models\SupplierInvoice;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = SupplierInvoice::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->string('number');
            $table->date('issued_on')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            // pending = da conciliare, reconciled = tutto quadra,
            // disputed = ci sono differenze da contestare al fornitore.
            $table->string('status_code', 32)->default('pending')->index();
            $table->text('notes')->nullable();

            $table->index(['supplier_id', 'number']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
