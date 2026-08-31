<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Wts\Models\WtsUnit;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WtsUnit::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('inbound_lot_id')->constrained('wts_inbound_lots')->cascadeOnDelete();
            $table->foreignId('technical_sheet_snapshot_id')->nullable()->constrained('wts_technical_sheet_snapshots')->nullOnDelete();
            $table->string('unit_code')->unique();
            $table->string('qr_code')->unique();
            $table->string('unit_type_code')->index();
            $table->string('status_code')->default('available')->index();
            $table->string('current_area_code')->default('incoming')->index();
            $table->string('location_label')->nullable();
            $table->decimal('gross_weight', 12, 2)->nullable();
            $table->boolean('is_banned')->default(false);
            $table->unsignedInteger('generated_sequence')->default(1);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
