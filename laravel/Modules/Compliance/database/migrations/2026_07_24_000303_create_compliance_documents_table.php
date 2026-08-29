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
            $table->string('type');
            $table->string('title');
            $table->string('status')->default('missing');
            $table->boolean('is_required')->default(true);
            $table->date('issued_on')->nullable();
            $table->date('expires_on')->nullable();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('subcontractor_id')->nullable()->constrained('subcontractors')->cascadeOnDelete();
            $table->foreignId('equipment_id')->nullable()->constrained('equipment')->nullOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('document_media')->nullOnDelete();
            $table->foreignIdFor($user_class, 'uploaded_by_user_id')->nullable()->index();
            $table->longText('extracted_text')->nullable();
            $table->json('ai_classification')->nullable();
            $table->json('ai_extracted_fields')->nullable();
            $table->timestamp('ai_processed_at')->nullable();
            $table->json('meta')->nullable();
            $table->text('notes')->nullable();

            $table->index(['type']);
            $table->index(['status']);
            $table->index(['expires_on']);
            $table->index(['work_order_id', 'type']);

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
