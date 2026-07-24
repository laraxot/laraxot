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
            $table->string('kind')->index();
            $table->string('number')->nullable();
            $table->date('invoice_date')->index();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
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
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->unique(['kind', 'number']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
