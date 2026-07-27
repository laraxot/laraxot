<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberDryingCycle;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberDryingCycle::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->morphs('dryable');
            $table->timestamp('entered_at');
            $table->timestamp('expected_exit_at')->nullable();
            $table->timestamp('actual_exit_at')->nullable();
            $table->decimal('target_humidity_percent', 5, 2)->nullable();
            $table->decimal('available_quantity', 12, 2)->default(0);
            $table->string('status_code')->default('in_progress')->index();
            $table->text('notes')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
