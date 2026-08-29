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
            $table->foreignId('public_procurement_contract_id')->constrained(table: 'public_procurement_contracts', indexName: 'pp_official_records_contract_id_foreign')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('document_media')->nullOnDelete();
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
