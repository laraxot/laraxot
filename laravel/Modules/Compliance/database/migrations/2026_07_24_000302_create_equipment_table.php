<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('status')->default('available');
            $table->date('last_checked_on')->nullable();
            $table->date('next_check_due_on')->nullable();
            $table->date('maintenance_due_on')->nullable();
            $table->foreignId('assigned_work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->text('notes')->nullable();

            $table->index(['type']);
            $table->index(['status']);
            $table->index(['next_check_due_on']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
