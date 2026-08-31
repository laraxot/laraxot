<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WtsDerivedMaterial::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('processing_step_id')->constrained('wts_processing_steps')->cascadeOnDelete();
            $table->foreignId('technical_sheet_snapshot_id')->nullable()->constrained('wts_technical_sheet_snapshots')->nullOnDelete();
            $table->string('output_code')->unique();
            $table->string('output_type_code')->index();
            $table->string('container_type_code')->index();
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('weight', 12, 2)->nullable();
            $table->boolean('is_dirty')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
