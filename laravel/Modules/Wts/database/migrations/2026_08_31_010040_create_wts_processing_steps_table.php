<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WtsProcessingStep::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('work_sheet_code')->unique();
            $table->string('step_type_code')->index();
            $table->string('quantity_mode')->default('units');
            $table->unsignedInteger('selected_units_count')->default(0);
            $table->decimal('input_weight', 12, 2)->nullable();
            $table->decimal('output_weight', 12, 2)->nullable();
            $table->decimal('remainder_weight', 12, 2)->nullable();
            $table->string('remainder_waste_code')->nullable();
            $table->foreignId('technical_sheet_snapshot_id')->nullable()->constrained('wts_technical_sheet_snapshots')->nullOnDelete();
            // L'id utente e' un UUID di 36 caratteri, non un intero: un foreignId qui
            // troncherebbe il valore a 0 e perderebbe silenziosamente l'attribuzione.
            $table->string('executed_by_user_id', 36)->nullable();
            $table->timestamp('executed_at');
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
