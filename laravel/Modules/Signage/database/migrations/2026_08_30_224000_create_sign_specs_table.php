<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Signage\Models\SignSpec;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = SignSpec::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('source_file');
            $table->string('drawing_title')->nullable();
            $table->string('customer_name')->nullable()->index();
            $table->string('referent_name')->nullable();
            $table->string('project')->nullable();
            $table->string('brand')->nullable()->index();
            $table->string('shop')->nullable();
            $table->string('sign_type_code')->nullable()->index();
            $table->unsignedInteger('quantity')->nullable();
            $table->string('family')->nullable();
            $table->string('color_ral')->nullable();
            $table->decimal('thickness_mm', 8, 2)->nullable();
            $table->unsignedInteger('width_mm')->nullable();
            $table->unsignedInteger('height_mm')->nullable();
            $table->string('painting')->nullable();
            $table->string('finishing')->nullable();
            $table->string('lighting')->nullable();
            $table->string('scale')->nullable();
            $table->string('sheet_size')->nullable();
            $table->string('revision')->nullable();
            $table->string('creator')->nullable();
            $table->json('layers')->nullable();
            $table->json('notes')->nullable();
            $table->json('raw_extraction')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('artwork_path')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
