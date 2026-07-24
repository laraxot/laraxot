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
            $table->foreignId('public_procurement_contract_id')->nullable()->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->foreignId('tender_opportunity_id')->nullable()->constrained('public_procurement_tender_opportunities')->cascadeOnDelete();
            $table->foreignId('compliance_document_id')->nullable()->constrained('compliance_documents')->nullOnDelete();
            $table->string('code');
            $table->string('title');
            $table->string('phase')->default('participation');
            $table->string('status')->default('missing');
            $table->string('severity')->default('warning');
            $table->boolean('is_required')->default(true);
            $table->date('due_on')->nullable();
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();

            $table->index(['public_procurement_contract_id', 'status']);
            $table->index(['tender_opportunity_id', 'phase']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
