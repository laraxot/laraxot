<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberAgent;
use Modules\TimberBilling\Models\TimberAgentCommission;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberAgentCommission::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(TimberAgent::class, 'agent_id')->constrained()->cascadeOnDelete();
            $table->string('scope_type')->default('default'); // default | category | product
            $table->string('category')->nullable();
            $table->foreignIdFor(TimberProduct::class, 'product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('rate_percent', 5, 2)->default(0);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
