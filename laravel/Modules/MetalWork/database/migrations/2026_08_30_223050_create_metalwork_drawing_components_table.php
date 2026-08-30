<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\MetalWork\Models\MetalDrawingComponent;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = MetalDrawingComponent::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('drawing_id')
                ->constrained('metalwork_drawings')
                ->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->unsignedInteger('quantity')->default(1);

            // Bounding box of the piece, largest dimension first. Read as the stock
            // it is cut from, not as the finished piece: the offcut is paid for.
            $table->decimal('length_mm', 12, 2);
            $table->decimal('width_mm', 12, 2);
            $table->decimal('thickness_mm', 12, 2);

            // plate|bar|block — what the piece was cut from, which decides both the
            // raw material it is matched against and how it is cut.
            $table->string('shape_code')->index();
            $table->unsignedInteger('plane_count')->default(0);
            $table->unsignedInteger('cylinder_count')->default(0);
            $table->unsignedInteger('hole_count')->default(0);
            // A profiled outline needs a torch or a laser; a square one comes off a saw.
            $table->boolean('has_profiled_cut')->default(false);

            // The material proposed by matching the section against the price list.
            // Nullable on purpose: no match is better than a wrong one, and the
            // operator picks from the list on screen.
            $table->foreignId('raw_material_id')
                ->nullable()
                ->constrained('metalwork_raw_materials')
                ->nullOnDelete();
            $table->boolean('is_material_confirmed')->default(false);

            $table->index(['drawing_id', 'position']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
