<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\MetalWork\Models\MetalSupplierPrice;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = MetalSupplierPrice::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('raw_material_id')
                ->constrained('metalwork_raw_materials')
                ->cascadeOnDelete();
            $table->string('supplier_name')->index();
            $table->string('invoice_number')->nullable();
            $table->string('delivery_note_number')->nullable();
            $table->date('recorded_on')->index();
            $table->decimal('quantity', 14, 3)->default(0);
            $table->string('unit_code');
            $table->decimal('unit_price', 12, 4);
            $table->decimal('line_total', 14, 2)->default(0);
            // Re-importing an invoice replaces every row that came from that file,
            // which is what makes the import idempotent. It cannot be a unique key
            // on the values instead: one invoice legitimately lists the same
            // article twice, once per delivery note.
            $table->string('source_file')->nullable()->index();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
