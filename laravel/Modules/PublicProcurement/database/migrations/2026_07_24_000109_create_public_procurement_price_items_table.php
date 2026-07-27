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
            $table->foreignId('public_procurement_contract_id')->constrained(table: 'public_procurement_contracts', indexName: 'pp_price_items_contract_id_foreign')->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->string('category')->nullable();
            $table->text('description');
            $table->string('unit')->default('cad');
            $table->decimal('planned_quantity', 14, 3)->default(0);
            $table->decimal('unit_price', 14, 4)->default(0);
            $table->decimal('planned_total', 14, 2)->default(0);
            $table->json('meta')->nullable();

            $table->index(['public_procurement_contract_id', 'code'], 'pp_price_items_contract_code_idx');

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
