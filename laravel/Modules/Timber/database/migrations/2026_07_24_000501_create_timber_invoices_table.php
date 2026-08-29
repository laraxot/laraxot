<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberInvoice;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberInvoice::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->string('kind')->index();
            $table->string('number')->nullable();
            $table->date('invoice_date')->index();
            $table->foreignIdFor(TimberCustomer::class, 'customer_id')->nullable()->index();
            $table->unsignedBigInteger('source_document_id')->nullable();
            $table->string('status_code')->default('draft')->index();
            $table->string('payment_method_code')->nullable();
            $table->string('payment_terms')->nullable();
            $table->decimal('taxable_amount', 14, 2)->default(0);
            $table->decimal('vat_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('stock_posted_at')->nullable();
            $table->foreignIdFor($userClass, 'created_by_user_id')->nullable()->index();
            $table->unique(['kind', 'number']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
