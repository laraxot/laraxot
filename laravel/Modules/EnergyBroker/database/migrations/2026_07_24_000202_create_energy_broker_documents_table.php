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
            $table->uuid('public_id')->nullable()->unique();
            $table->foreignId('energy_broker_import_batch_id')->nullable()->constrained('energy_broker_import_batches')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('document_media')->nullOnDelete();

            $table->string('source_file_name');
            $table->string('file_hash')->nullable()->index();
            $table->string('document_type')->default('unknown');
            $table->string('service_type')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('analysis_status')->default('draft');
            $table->decimal('confidence_score', 5, 4)->nullable();
            $table->longText('raw_text')->nullable();
            $table->json('analysis_payload')->nullable();
            $table->json('normalized_payload')->nullable();
            $table->json('reviewed_payload')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->dateTime('imported_at')->nullable();

            $table->index(['document_type']);
            $table->index(['analysis_status']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
