<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Billing\Models\Invoice;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = Invoice::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();

            $table->string('number')->nullable();
            $table->date('issued_on')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->text('notes')->nullable();

            // Tracciamento invio fattura elettronica al provider SDI configurato.
            $table->string('electronic_invoice_provider')->nullable();
            $table->string('electronic_invoice_external_id')->nullable();
            $table->timestamp('electronic_invoice_sent_at')->nullable();
            $table->text('electronic_invoice_last_error')->nullable();

            $table->index(['customer_id']);
            $table->index(['work_order_id']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('electronic_invoice_provider')) {
                $table->string('electronic_invoice_provider')->nullable();
            }

            if (! $this->hasColumn('electronic_invoice_external_id')) {
                $table->string('electronic_invoice_external_id')->nullable();
            }

            if (! $this->hasColumn('electronic_invoice_sent_at')) {
                $table->timestamp('electronic_invoice_sent_at')->nullable();
            }

            if (! $this->hasColumn('electronic_invoice_last_error')) {
                $table->text('electronic_invoice_last_error')->nullable();
            }

            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
