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
            $table->foreignId('public_procurement_contract_id')->constrained(table: 'public_procurement_contracts', indexName: 'pp_progress_reports_contract_id_foreign')->cascadeOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $table->date('issued_on')->nullable();
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            $table->decimal('work_amount', 14, 2)->default(0);
            $table->decimal('security_amount', 14, 2)->default(0);
            $table->decimal('retention_amount', 14, 2)->default(0);
            $table->decimal('certificate_amount', 14, 2)->default(0);
            $table->decimal('progress_percentage', 6, 2)->default(0);
            $table->text('notes')->nullable();

            $table->unique(['public_procurement_contract_id', 'number'], 'pub_proc_progress_number_unique');

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
