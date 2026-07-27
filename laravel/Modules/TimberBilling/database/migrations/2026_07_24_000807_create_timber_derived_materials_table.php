<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberProcessingStep;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberDerivedMaterial::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(TimberProcessingStep::class, 'processing_step_id')->constrained('timber_processing_steps')->cascadeOnDelete();
            $table->foreignIdFor(TimberLot::class, 'root_lot_id')->nullable()->constrained('timber_lots')->nullOnDelete();
            $table->string('output_code')->unique();
            $table->string('traceable_code')->nullable()->index();
            $table->string('stage_type_code')->nullable();
            $table->json('stage_history')->nullable();
            $table->string('wood_species')->index();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('unit_of_measure');
            $table->string('status_code')->default('da_lavorare')->index();
            $table->string('current_area_code')->default('zona_taglio')->index();
            $table->boolean('is_waste')->default(false);
            $table->json('meta')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
