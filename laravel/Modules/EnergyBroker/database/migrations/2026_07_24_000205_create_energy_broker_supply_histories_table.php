<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('energy_broker_supply_point_id')->constrained('energy_broker_supply_points')->cascadeOnDelete();
            $table->foreignId('energy_broker_document_id')->nullable()->constrained('energy_broker_documents')->nullOnDelete();
            $table->string('supplier_name');
            $table->string('offer_name')->nullable();
            $table->string('offer_code')->nullable();
            $table->string('status')->default('current');
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->json('meta')->nullable();

            $table->index(['energy_broker_supply_point_id', 'status']);

            $this->updateTimestamps($table);
        });
    }
};
