<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberPriceList;
use Modules\TimberBilling\Models\TimberPriceListVersion;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberPriceListVersion::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $user_class = XotData::make()->getUserClass();

            $table->id();
            $table->foreignIdFor(TimberPriceList::class, 'price_list_id')->constrained('timber_price_lists')->cascadeOnDelete();
            $table->json('items');
            $table->text('note')->nullable();
            $table->foreignIdFor($user_class, 'created_by_user_id')->nullable()->index();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
