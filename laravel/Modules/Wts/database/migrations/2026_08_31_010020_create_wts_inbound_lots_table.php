<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WtsInboundLot::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignId('inbound_record_id')->constrained('wts_inbound_records')->cascadeOnDelete();
            $table->string('lot_code')->unique();
            $table->string('status_code')->default('registered')->index();
            $table->string('current_area_code')->default('incoming')->index();
            $table->unsignedInteger('initial_units_count')->default(0);
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
