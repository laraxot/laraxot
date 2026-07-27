<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberPurchaseRecord;
use Modules\TimberBilling\Models\TimberSupplier;
use Modules\TimberBilling\Models\TimberTransporter;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberPurchaseRecord::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->foreignIdFor(TimberSupplier::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(TimberTransporter::class)->nullable()->constrained()->nullOnDelete();
            $table->string('origin_forest')->nullable();
            $table->string('ddt_number')->nullable()->index();
            $table->date('ddt_date')->nullable();
            $table->string('wood_species')->index();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('unit_of_measure');
            $table->decimal('gross_weight', 12, 2)->nullable();
            $table->decimal('net_weight', 12, 2)->nullable();
            $table->decimal('unit_purchase_price', 12, 2)->nullable();
            $table->string('purchase_price_unit')->default('quintale');
            $table->string('certifications')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->string('status_code')->default('registered')->index();
            $table->foreignIdFor($userClass, 'created_by_user_id')->nullable()->index();
            $table->timestamp('received_at')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('purchase_price_unit')) {
                $table->string('purchase_price_unit')->default('quintale');
            }

            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
