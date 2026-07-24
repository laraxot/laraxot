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
            $table->string('label');
            $table->decimal('works_amount', 14, 2)->default(0);
            $table->decimal('security_amount', 14, 2)->default(0);
            $table->decimal('available_sums', 14, 2)->default(0);
            $table->decimal('vat_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->json('meta')->nullable();

            $this->updateTimestamps($table);
        });
    }
};
