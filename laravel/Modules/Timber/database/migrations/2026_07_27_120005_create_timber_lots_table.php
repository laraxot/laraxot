<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPurchaseRecord;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberLot::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(TimberPurchaseRecord::class, 'purchase_record_id')->constrained()->cascadeOnDelete();
            $table->string('lot_code')->unique();
            $table->string('status_code')->default('available')->index();
            $table->string('current_area_code')->default('piazzale')->index();
            $table->decimal('quantity_available', 12, 2)->default(0);
            $table->string('unit_of_measure');
            $table->string('wood_species')->index();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
