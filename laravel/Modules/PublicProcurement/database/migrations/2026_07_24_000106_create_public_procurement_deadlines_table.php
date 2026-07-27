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
            $table->foreignId('public_procurement_contract_id')->constrained(table: 'public_procurement_contracts', indexName: 'pp_deadlines_contract_id_foreign')->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->string('status')->default('open');
            $table->dateTime('due_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('severity')->default('warning');
            $table->text('notes')->nullable();

            $table->index(['public_procurement_contract_id', 'status'], 'pp_deadlines_contract_status_idx');
            $table->index(['due_at'], 'pp_deadlines_due_at_idx');

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
