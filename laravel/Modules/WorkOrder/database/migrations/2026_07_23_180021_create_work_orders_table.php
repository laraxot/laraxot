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
            $table->string('code')->nullable()->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();
            $table->foreignId('primary_contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('work_order_statuses')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('opened_at')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->unsignedInteger('planned_minutes')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->index(['customer_id']);
            $table->index(['status_id']);
            $table->index(['scheduled_at']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
