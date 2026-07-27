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
            $table->foreignId('public_procurement_contract_id')->constrained(table: 'public_procurement_contracts', indexName: 'pp_contacts_contract_id_foreign')->cascadeOnDelete();
            $table->string('role');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();

            $table->index(['public_procurement_contract_id', 'role'], 'pp_contacts_contract_role_idx');

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
