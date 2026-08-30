<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Signage\Models\SignPriceListItem;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = SignPriceListItem::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('category_code')->index(); // material|machining|labor|led|cable|fixing|paint|packaging|shipping|other
            $table->string('work_center_code')->nullable()->index();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_code')->nullable();
            $table->string('unit_code'); // mq|mt|min|nr|kg|pz
            $table->decimal('unit_cost', 12, 4)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('work_center_code')) {
                $table->string('work_center_code')->nullable()->index();
            }

            $this->updateTimestamps($table);
        });
    }
};
