<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberLabelPrint;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberLabelPrint::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->morphs('labelable');
            // Log ristampe: label_code NON unique (stessa etichetta ristampabile)
            $table->string('label_code')->index();
            $table->string('qr_payload');
            $table->string('print_reason_code')->default('initial_print');
            $table->foreignIdFor($userClass, 'printed_by_user_id')->nullable()->index();
            $table->timestamp('printed_at');
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
