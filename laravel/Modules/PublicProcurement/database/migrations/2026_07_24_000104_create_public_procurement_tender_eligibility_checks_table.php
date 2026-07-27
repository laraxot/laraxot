<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

/**
 * MySQL/MariaDB: identifier ≤64 char.
 * Auto-index `…_evaluated_by_user_id_index` supera il limite → errore 1059.
 */
return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $user_class = XotData::make()->getUserClass();

            $table->id();
            $table->foreignId('tender_opportunity_id')->nullable()->constrained(table: 'public_procurement_tender_opportunities', indexName: 'pp_elig_checks_tender_opportunity_id_foreign')->cascadeOnDelete();
            $table->foreignId('public_procurement_contract_id')->nullable()->constrained(table: 'public_procurement_contracts', indexName: 'pp_elig_checks_contract_id_foreign')->cascadeOnDelete();
            // short index name — no auto `{table}_{col}_index`
            $table->foreignIdFor($user_class, 'evaluated_by_user_id')->nullable()->index('pp_elig_checks_eval_by_user_idx');
            $table->string('status')->default('needs_review');
            $table->unsignedInteger('score')->default(0);
            $table->json('required_documents')->nullable();
            $table->json('missing_documents')->nullable();
            $table->json('expired_documents')->nullable();
            $table->json('warnings')->nullable();
            $table->json('actions')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->text('summary')->nullable();

            $table->index(['status'], 'pp_elig_checks_status_idx');
            $table->index(['evaluated_at'], 'pp_elig_checks_evaluated_at_idx');

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });

        // Retry dopo fail 1059: tabella può esistere senza indici corti
        $this->tableUpdate(function (Blueprint $table): void {
            if ($this->hasColumn('evaluated_by_user_id') && ! $this->hasIndex('pp_elig_checks_eval_by_user_idx') && ! $this->hasIndex('evaluated_by_user_id')) {
                $table->index('evaluated_by_user_id', 'pp_elig_checks_eval_by_user_idx');
            }
            if ($this->hasColumn('status') && ! $this->hasIndex('pp_elig_checks_status_idx') && ! $this->hasIndex('status')) {
                $table->index('status', 'pp_elig_checks_status_idx');
            }
            if ($this->hasColumn('evaluated_at') && ! $this->hasIndex('pp_elig_checks_evaluated_at_idx') && ! $this->hasIndex('evaluated_at')) {
                $table->index('evaluated_at', 'pp_elig_checks_evaluated_at_idx');
            }

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
