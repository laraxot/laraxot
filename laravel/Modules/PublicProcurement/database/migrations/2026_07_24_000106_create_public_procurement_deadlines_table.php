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
            $table->string('type');
            $table->string('title');
            $table->string('status')->default('open');
            $table->dateTime('due_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('severity')->default('warning');
            $table->text('notes')->nullable();

            $table->index(['public_procurement_contract_id', 'status']);
            $table->index(['due_at']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
