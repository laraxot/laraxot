<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = MetalDrawing::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('part_code')->index();
            $table->string('revision')->nullable();
            $table->string('name');
            $table->string('source_file');
            $table->string('storage_path')->nullable();
            $table->string('content_hash', 64)->nullable()->index();
            $table->string('cad_system')->nullable();
            $table->string('length_unit_code')->default('mm');
            // Bounding box, largest dimension first.
            $table->decimal('bbox_length_mm', 12, 2)->nullable();
            $table->decimal('bbox_width_mm', 12, 2)->nullable();
            $table->decimal('bbox_thickness_mm', 12, 2)->nullable();
            $table->unsignedInteger('point_count')->default(0);
            $table->unsignedInteger('face_count')->default(0);
            $table->unsignedInteger('plane_count')->default(0);
            $table->unsignedInteger('cylinder_count')->default(0);
            $table->unsignedInteger('solid_count')->default(0);
            $table->boolean('is_assembly')->default(false);
            $table->foreignId('imported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
