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
            $table->foreignId('price_item_id')->nullable()->constrained('public_procurement_price_items')->nullOnDelete();
            $table->foreignId('intervention_id')->nullable()->constrained('interventions')->nullOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->date('measured_on')->nullable();
            $table->decimal('quantity', 14, 3)->default(0);
            $table->decimal('amount', 14, 2)->default(0);
            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            $table->index(['public_procurement_contract_id', 'status']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
