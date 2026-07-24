<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('from_status_id')->nullable()->constrained('work_order_statuses')->nullOnDelete();
            $table->foreignId('to_status_id')->nullable()->constrained('work_order_statuses')->nullOnDelete();
            $table->unsignedBigInteger('changed_by_user_id')->nullable()->index();
            $table->text('note')->nullable();
            $table->dateTime('changed_at')->nullable();
            $table->index(['work_order_id']);
            $table->index(['changed_at']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
