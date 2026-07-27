<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberProcessingStep;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberProcessingStep::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->string('work_sheet_code')->unique();
            $table->string('step_type_code')->index();
            $table->decimal('input_quantity', 12, 2)->nullable();
            $table->decimal('output_quantity', 12, 2)->nullable();
            $table->decimal('waste_quantity', 12, 2)->nullable();
            $table->decimal('recoverable_quantity', 12, 2)->nullable();
            $table->string('unit_of_measure');
            $table->foreignIdFor($userClass, 'executed_by_user_id')->nullable()->index();
            $table->timestamp('executed_at');
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
