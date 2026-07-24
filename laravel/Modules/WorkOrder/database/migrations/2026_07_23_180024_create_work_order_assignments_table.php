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
            $table->unsignedBigInteger('user_id')->index();
            $table->string('role')->nullable();
            $table->unsignedBigInteger('assigned_by_user_id')->nullable()->index();
            $table->dateTime('assigned_at')->nullable();
            $table->unique(['work_order_id', 'user_id']);
            $table->index(['work_order_id']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
