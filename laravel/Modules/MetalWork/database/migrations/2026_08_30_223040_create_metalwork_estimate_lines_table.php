<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\MetalWork\Models\MetalEstimateLine;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = MetalEstimateLine::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('part_estimate_id')
                ->constrained('metalwork_part_estimates')
                ->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->string('work_center_code')->index();
            $table->string('description');
            $table->string('unit_code');
            $table->decimal('quantity', 14, 4)->default(0);
            $table->decimal('unit_cost', 14, 4)->default(0);
            $table->decimal('line_cost', 14, 2)->default(0);
            $table->decimal('markup_percent', 8, 2)->default(0);
            $table->decimal('line_price', 14, 2)->default(0);
            // Where the markup came from: estimate|work_center|config.
            $table->string('markup_source_code')->default('config');
            $table->string('driver_code')->nullable();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
