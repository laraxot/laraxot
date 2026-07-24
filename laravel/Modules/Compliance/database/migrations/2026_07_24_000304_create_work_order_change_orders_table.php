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
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('estimated_amount', 12, 2)->nullable();
            $table->string('status')->default('proposed');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('executed_at')->nullable();
            $table->dateTime('billed_at')->nullable();
            $table->foreignId('signature_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->json('meta')->nullable();

            $table->index(['work_order_id', 'status']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
