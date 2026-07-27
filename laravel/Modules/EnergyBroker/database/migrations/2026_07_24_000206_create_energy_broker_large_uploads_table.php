<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\EnergyBroker\Models\EnergyBrokerImportBatch;
use Modules\EnergyBroker\Models\EnergyBrokerLargeUpload;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = EnergyBrokerLargeUpload::class;

    public function up(): void
    {
        $user_class = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($user_class): void {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignIdFor($user_class, 'uploaded_by_user_id')->nullable()->index();
            $table->foreignIdFor(EnergyBrokerImportBatch::class, 'energy_broker_import_batch_id')
                ->nullable()
                ->constrained(indexName: 'eb_large_uploads_import_batch_id_foreign')
                ->nullOnDelete();
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('total_size');
            $table->unsignedBigInteger('chunk_size');
            $table->unsignedInteger('total_chunks');
            $table->unsignedInteger('received_chunks')->default(0);
            $table->string('status')->default('uploading');
            $table->string('stored_path')->nullable();
            $table->string('file_hash')->nullable()->index();
            $table->json('received_chunk_indexes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['uploaded_by_user_id', 'status']);
            $table->index(['status']);
        });
    }
};
