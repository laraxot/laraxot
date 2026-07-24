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
            $table->foreignId('public_procurement_contract_id')->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->string('category')->nullable();
            $table->text('description');
            $table->string('unit')->default('cad');
            $table->decimal('planned_quantity', 14, 3)->default(0);
            $table->decimal('unit_price', 14, 4)->default(0);
            $table->decimal('planned_total', 14, 2)->default(0);
            $table->json('meta')->nullable();

            $table->index(['public_procurement_contract_id', 'code']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
