<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $user_class = XotData::make()->getUserClass();

            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignIdFor($user_class, 'requested_by_user_id')->nullable()->index();
            $table->foreignIdFor($user_class, 'approved_by_user_id')->nullable()->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('estimated_amount', 12, 2)->nullable();
            $table->string('status')->default('proposed');
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('executed_at')->nullable();
            $table->dateTime('billed_at')->nullable();
            $table->foreignId('signature_media_id')->nullable()->constrained('document_media')->nullOnDelete();
            $table->json('meta')->nullable();

            $table->index(['work_order_id', 'status']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
