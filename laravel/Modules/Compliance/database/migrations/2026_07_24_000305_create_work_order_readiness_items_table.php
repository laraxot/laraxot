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
            $table->string('code');
            $table->string('label');
            $table->string('severity')->default('warning');
            $table->string('status')->default('missing');
            $table->text('message')->nullable();
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->date('due_on')->nullable();
            $table->json('meta')->nullable();

            $table->unique(['work_order_id', 'code']);
            $table->index(['work_order_id', 'severity']);
            $table->index(['status']);

            $this->updateTimestamps($table);
        });
    }
};
