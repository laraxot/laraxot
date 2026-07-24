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
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();

            $table->string('service_type');
            $table->string('pod')->nullable();
            $table->string('pdr')->nullable();
            $table->string('supply_code')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('offer_name')->nullable();
            $table->decimal('annual_consumption', 14, 3)->nullable();
            $table->decimal('power_kw', 8, 2)->nullable();

            $table->index(['customer_id', 'service_type']);
            $table->index(['pod']);
            $table->index(['pdr']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
