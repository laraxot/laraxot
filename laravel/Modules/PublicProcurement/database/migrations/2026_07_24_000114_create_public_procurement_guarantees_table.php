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
            $table->foreignId('public_procurement_contract_id')->constrained(table: 'public_procurement_contracts', indexName: 'pp_guarantees_contract_id_foreign')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('type');
            $table->string('title');
            $table->string('provider')->nullable();
            $table->string('policy_number')->nullable();
            $table->string('status')->default('valid');
            $table->decimal('amount', 14, 2)->nullable();
            $table->date('issued_on')->nullable();
            $table->date('expires_on')->nullable();
            $table->text('notes')->nullable();

            $table->index(['public_procurement_contract_id', 'expires_on'], 'pp_guarantees_contract_expires_idx');

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
