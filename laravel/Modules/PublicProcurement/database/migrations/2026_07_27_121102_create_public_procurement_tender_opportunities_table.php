<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Customer\Models\Customer;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * MySQL identifier ≤64: auto-index su submission_deadline_at supera il limite (1059).
 * Prefissi corti `pub_proc_tender_*` / `pp_*`.
 */
return new class() extends XotBaseMigration
{
    protected ?string $model_class = PublicProcurementTenderOpportunity::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(PublicProcurementTenderSource::class, 'tender_source_id')
                ->nullable()
                ->constrained(table: 'public_procurement_tender_sources', indexName: 'pub_proc_tender_source_id_foreign')
                ->nullOnDelete();
            $table->foreignIdFor(Customer::class, 'customer_id')
                ->nullable()
                ->constrained(table: 'customers', indexName: 'pub_proc_tender_customer_id_foreign')
                ->nullOnDelete();
            $table->string('status')->default('new');
            $table->string('external_id')->nullable();
            $table->string('source_url')->nullable();
            $table->string('contracting_authority');
            $table->string('title');
            $table->string('cig')->nullable();
            $table->string('cup')->nullable();
            $table->string('cpv_code')->nullable();
            $table->string('category')->nullable();
            $table->string('territory')->nullable();
            $table->decimal('estimated_amount', 14, 2)->nullable();
            $table->dateTime('published_at')->nullable();
            $table->dateTime('submission_deadline_at')->nullable();
            $table->dateTime('site_visit_due_at')->nullable();
            $table->json('keywords')->nullable();
            $table->json('ai_summary')->nullable();
            $table->text('raw_excerpt')->nullable();
            $table->text('notes')->nullable();

            $table->index(['status'], 'pub_proc_tender_status_idx');
            $table->index(['cig'], 'pub_proc_tender_cig_idx');
            $table->index(['cup'], 'pub_proc_tender_cup_idx');
            $table->index(['submission_deadline_at'], 'pub_proc_tender_sub_deadline_idx');
            $table->unique(['tender_source_id', 'external_id'], 'pub_proc_tender_source_external_unique');
        });

        $this->tableUpdate(function (Blueprint $table): void {
            // Retry dopo 1059: tabella orfana senza indici corti
            if ($this->hasColumn('submission_deadline_at')
                && ! $this->hasIndex('pub_proc_tender_sub_deadline_idx')
                && ! $this->hasIndex('submission_deadline_at')) {
                $table->index(['submission_deadline_at'], 'pub_proc_tender_sub_deadline_idx');
            }

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
