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
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('type');
            $table->string('title');
            $table->string('protocol_number')->nullable();
            $table->date('recorded_on')->nullable();
            $table->string('status')->default('draft');
            $table->text('summary')->nullable();

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
