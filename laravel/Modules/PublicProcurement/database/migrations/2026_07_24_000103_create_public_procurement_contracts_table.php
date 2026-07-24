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
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->foreignId('tender_opportunity_id')->nullable()->constrained('public_procurement_tender_opportunities')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->string('code')->nullable()->unique();
            $table->string('title');
            $table->string('contracting_authority');
            $table->string('procedure_type')->nullable();
            $table->string('cig')->nullable();
            $table->string('cup')->nullable();
            $table->string('rup_name')->nullable();
            $table->string('works_director_name')->nullable();
            $table->string('safety_coordinator_name')->nullable();
            $table->date('awarded_on')->nullable();
            $table->date('signed_on')->nullable();
            $table->date('site_handover_on')->nullable();
            $table->date('works_start_on')->nullable();
            $table->date('works_due_on')->nullable();
            $table->date('works_completed_on')->nullable();
            $table->decimal('contract_amount', 14, 2)->default(0);
            $table->decimal('security_costs', 14, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(22);
            $table->decimal('discount_rate', 6, 3)->default(0);
            $table->decimal('retention_rate', 6, 3)->default(0);
            $table->json('meta')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            $table->index(['status']);
            $table->index(['cig']);
            $table->index(['cup']);
            $table->index(['work_order_id']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
