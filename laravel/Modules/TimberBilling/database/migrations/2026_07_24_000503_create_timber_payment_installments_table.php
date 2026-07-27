<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberCustomer;
use Modules\TimberBilling\Models\TimberInvoice;
use Modules\TimberBilling\Models\TimberPaymentInstallment;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberPaymentInstallment::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->foreignIdFor(TimberInvoice::class, 'invoice_id')->nullable()->constrained('timber_invoices')->cascadeOnDelete();
            $table->foreignIdFor(TimberCustomer::class, 'customer_id')->nullable()->index();
            $table->string('direction')->default('in')->index();
            $table->date('due_date')->index();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('payment_method_code')->nullable()->index();
            $table->string('status_code')->default('da_saldare')->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('reminded_at')->nullable();
            $table->text('notes')->nullable();

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};