<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberPackagedUnit;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberPackagedUnit::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(TimberDerivedMaterial::class)->constrained()->cascadeOnDelete();
            $table->string('package_code')->unique();
            $table->string('qr_code')->unique();
            $table->string('package_type_code')->index();
            $table->string('article_code')->nullable()->index();
            $table->string('traceable_code')->nullable()->index();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('unit_of_measure');
            $table->string('status_code')->default('pronto_per_vendita')->index();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('article_code')) {
                $table->string('article_code')->nullable()->index();
            }
            if (! $this->hasColumn('traceable_code')) {
                $table->string('traceable_code')->nullable()->index();
            }

            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
