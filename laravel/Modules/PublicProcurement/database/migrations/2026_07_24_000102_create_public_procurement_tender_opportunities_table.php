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
            $table->foreignId('tender_source_id')->nullable()->constrained('public_procurement_tender_sources')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
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

            $table->index(['status']);
            $table->index(['cig']);
            $table->index(['cup']);
            $table->index(['submission_deadline_at']);
            $table->unique(['tender_source_id', 'external_id'], 'pub_proc_tender_source_external_unique');

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
