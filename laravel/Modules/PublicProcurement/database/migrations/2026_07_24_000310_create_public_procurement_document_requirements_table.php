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
            $table->foreignId('public_procurement_contract_id')->nullable()->constrained(table: 'public_procurement_contracts', indexName: 'pp_doc_reqs_contract_id_foreign')->cascadeOnDelete();
            $table->foreignId('tender_opportunity_id')->nullable()->constrained(table: 'public_procurement_tender_opportunities', indexName: 'pp_doc_reqs_tender_opportunity_id_foreign')->cascadeOnDelete();
            $table->foreignId('compliance_document_id')->nullable()->constrained(table: 'compliance_documents', indexName: 'pp_doc_reqs_compliance_document_id_foreign')->nullOnDelete();
            $table->string('code');
            $table->string('title');
            $table->string('phase')->default('participation');
            $table->string('status')->default('missing');
            $table->string('severity')->default('warning');
            $table->boolean('is_required')->default(true);
            $table->date('due_on')->nullable();
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();

            $table->index(['public_procurement_contract_id', 'status'], 'pp_doc_reqs_contract_status_idx');
            $table->index(['tender_opportunity_id', 'phase'], 'pp_doc_reqs_tender_phase_idx');

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
