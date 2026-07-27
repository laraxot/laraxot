<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberProcessingStep;
use Modules\TimberBilling\Models\TimberProcessingStepProcessable;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberProcessingStepProcessable::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(TimberProcessingStep::class, 'processing_step_id')
                ->constrained(indexName: 'timber_step_processable_step_fk')
                ->cascadeOnDelete();
            $table->morphs('processable', 'timber_step_processable_morph_idx');
            $table->unique(
                ['processing_step_id', 'processable_type', 'processable_id'],
                'timber_step_processable_unique',
            );
        });
    }
};
