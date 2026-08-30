<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Signage\Models\SignEstimationRule;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = SignEstimationRule::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('role_code')->unique(); // body|face|cutting|led|cable|fixing|paint
            $table->string('unit_code'); // mq|mt|nr|min
            $table->string('strategy_code'); // area|perimeter|constant|none
            $table->decimal('factor', 10, 4)->default(1);
            $table->decimal('base_value', 12, 4)->default(0);
            $table->decimal('min_per_piece', 12, 4)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
