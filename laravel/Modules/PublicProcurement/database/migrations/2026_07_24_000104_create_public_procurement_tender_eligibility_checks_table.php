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
            $table->foreignId('tender_opportunity_id')->nullable()->constrained('public_procurement_tender_opportunities')->cascadeOnDelete();
            $table->foreignId('public_procurement_contract_id')->nullable()->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->foreignId('evaluated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('needs_review');
            $table->unsignedInteger('score')->default(0);
            $table->json('required_documents')->nullable();
            $table->json('missing_documents')->nullable();
            $table->json('expired_documents')->nullable();
            $table->json('warnings')->nullable();
            $table->json('actions')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->text('summary')->nullable();

            $table->index(['status']);
            $table->index(['evaluated_at']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
