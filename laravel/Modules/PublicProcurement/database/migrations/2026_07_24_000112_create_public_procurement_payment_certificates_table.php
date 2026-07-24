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
            $table->foreignId('public_procurement_contract_id')->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->foreignId('progress_report_id')->nullable()->constrained('public_procurement_progress_reports')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $table->date('issued_on')->nullable();
            $table->decimal('amount', 14, 2)->default(0);
            $table->text('notes')->nullable();

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
