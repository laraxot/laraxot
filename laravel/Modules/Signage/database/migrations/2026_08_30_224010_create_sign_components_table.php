<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Signage\Models\SignComponent;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = SignComponent::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('sign_spec_id')->constrained('sign_specs')->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->string('role_code')->index(); // body|face|led|cable|fixing|paint|holes|cutting|text|dimension|other
            $table->string('label');
            $table->text('description')->nullable();
            $table->string('material')->nullable();
            $table->string('color_ral')->nullable();
            $table->decimal('thickness_mm', 8, 2)->nullable();
            $table->string('lighting')->nullable();
            $table->string('source_code'); // callout|layer|title_block|code
            $table->foreignId('price_list_item_id')->nullable()->constrained('sign_price_list_items')->nullOnDelete();
            $table->decimal('quantity', 12, 3)->nullable();
            $table->string('unit_code')->nullable();
            $table->decimal('unit_cost', 12, 4)->nullable();
            $table->decimal('line_total', 12, 2)->nullable();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
