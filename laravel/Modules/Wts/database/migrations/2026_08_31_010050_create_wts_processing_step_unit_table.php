<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Wts\Models\WtsProcessingStepUnit;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WtsProcessingStepUnit::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('wts_processing_step_id')->constrained('wts_processing_steps')->cascadeOnDelete();
            $table->foreignId('wts_unit_id')->constrained('wts_units')->cascadeOnDelete();
            $table->unique(['wts_processing_step_id', 'wts_unit_id'], 'wts_processing_step_unit_unique');
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
