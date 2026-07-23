<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcontractors', static function (Blueprint $table): void {
            $table->id();
            $table->string('business_name');
            $table->string('vat_number')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['business_name']);
            $table->index(['status']);
        });

        Schema::create('equipment', static function (Blueprint $table): void {
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
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type']);
            $table->index(['status']);
            $table->index(['next_check_due_on']);
        });

        Schema::create('compliance_documents', static function (Blueprint $table): void {
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
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('extracted_text')->nullable();
            $table->json('ai_classification')->nullable();
            $table->json('ai_extracted_fields')->nullable();
            $table->timestamp('ai_processed_at')->nullable();
            $table->json('meta')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type']);
            $table->index(['status']);
            $table->index(['expires_on']);
            $table->index(['work_order_id', 'type']);
        });

        Schema::create('work_order_change_orders', static function (Blueprint $table): void {
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
            $table->timestamps();
            $table->softDeletes();

            $table->index(['work_order_id', 'status']);
        });

        Schema::create('work_order_readiness_items', static function (Blueprint $table): void {
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
            $table->timestamps();

            $table->unique(['work_order_id', 'code']);
            $table->index(['work_order_id', 'severity']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_readiness_items');
        Schema::dropIfExists('work_order_change_orders');
        Schema::dropIfExists('compliance_documents');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('subcontractors');
    }
};
