<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('public_procurement_tender_sources', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('source_type')->default('manual');
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('filters')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['source_type', 'is_active']);
        });

        Schema::create('public_procurement_tender_opportunities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tender_source_id')->nullable()->constrained('public_procurement_tender_sources')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('status')->default('new');
            $table->string('external_id')->nullable();
            $table->string('source_url')->nullable();
            $table->string('contracting_authority');
            $table->string('title');
            $table->string('cig')->nullable();
            $table->string('cup')->nullable();
            $table->string('cpv_code')->nullable();
            $table->string('category')->nullable();
            $table->string('territory')->nullable();
            $table->decimal('estimated_amount', 14, 2)->nullable();
            $table->dateTime('published_at')->nullable();
            $table->dateTime('submission_deadline_at')->nullable();
            $table->dateTime('site_visit_due_at')->nullable();
            $table->json('keywords')->nullable();
            $table->json('ai_summary')->nullable();
            $table->text('raw_excerpt')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index(['cig']);
            $table->index(['cup']);
            $table->index(['submission_deadline_at']);
            $table->unique(['tender_source_id', 'external_id'], 'pub_proc_tender_source_external_unique');
        });

        Schema::create('public_procurement_contracts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->foreignId('tender_opportunity_id')->nullable()->constrained('public_procurement_tender_opportunities')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->string('code')->nullable()->unique();
            $table->string('title');
            $table->string('contracting_authority');
            $table->string('procedure_type')->nullable();
            $table->string('cig')->nullable();
            $table->string('cup')->nullable();
            $table->string('rup_name')->nullable();
            $table->string('works_director_name')->nullable();
            $table->string('safety_coordinator_name')->nullable();
            $table->date('awarded_on')->nullable();
            $table->date('signed_on')->nullable();
            $table->date('site_handover_on')->nullable();
            $table->date('works_start_on')->nullable();
            $table->date('works_due_on')->nullable();
            $table->date('works_completed_on')->nullable();
            $table->decimal('contract_amount', 14, 2)->default(0);
            $table->decimal('security_costs', 14, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(22);
            $table->decimal('discount_rate', 6, 3)->default(0);
            $table->decimal('retention_rate', 6, 3)->default(0);
            $table->json('meta')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index(['cig']);
            $table->index(['cup']);
            $table->index(['work_order_id']);
        });

        Schema::create('public_procurement_tender_eligibility_checks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tender_opportunity_id')->nullable()->constrained('public_procurement_tender_opportunities')->cascadeOnDelete();
            $table->foreignId('public_procurement_contract_id')->nullable()->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->foreignId('evaluated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('needs_review');
            $table->unsignedInteger('score')->default(0);
            $table->json('required_documents')->nullable();
            $table->json('missing_documents')->nullable();
            $table->json('expired_documents')->nullable();
            $table->json('warnings')->nullable();
            $table->json('actions')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->text('summary')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status']);
            $table->index(['evaluated_at']);
        });

        Schema::create('public_procurement_contacts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('public_procurement_contract_id')->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->string('role');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['public_procurement_contract_id', 'role']);
        });

        Schema::create('public_procurement_deadlines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('public_procurement_contract_id')->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->string('status')->default('open');
            $table->dateTime('due_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('severity')->default('warning');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['public_procurement_contract_id', 'status']);
            $table->index(['due_at']);
        });

        Schema::create('public_procurement_document_requirements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('public_procurement_contract_id')->nullable()->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->foreignId('tender_opportunity_id')->nullable()->constrained('public_procurement_tender_opportunities')->cascadeOnDelete();
            $table->foreignId('compliance_document_id')->nullable()->constrained('compliance_documents')->nullOnDelete();
            $table->string('code');
            $table->string('title');
            $table->string('phase')->default('participation');
            $table->string('status')->default('missing');
            $table->string('severity')->default('warning');
            $table->boolean('is_required')->default(true);
            $table->date('due_on')->nullable();
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['public_procurement_contract_id', 'status']);
            $table->index(['tender_opportunity_id', 'phase']);
        });

        Schema::create('public_procurement_economic_frames', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('public_procurement_contract_id')->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->string('label');
            $table->decimal('works_amount', 14, 2)->default(0);
            $table->decimal('security_amount', 14, 2)->default(0);
            $table->decimal('available_sums', 14, 2)->default(0);
            $table->decimal('vat_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('public_procurement_price_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('public_procurement_contract_id')->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->string('category')->nullable();
            $table->text('description');
            $table->string('unit')->default('cad');
            $table->decimal('planned_quantity', 14, 3)->default(0);
            $table->decimal('unit_price', 14, 4)->default(0);
            $table->decimal('planned_total', 14, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['public_procurement_contract_id', 'code']);
        });

        Schema::create('public_procurement_measurements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('public_procurement_contract_id')->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->foreignId('price_item_id')->nullable()->constrained('public_procurement_price_items')->nullOnDelete();
            $table->foreignId('intervention_id')->nullable()->constrained('interventions')->nullOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->date('measured_on')->nullable();
            $table->decimal('quantity', 14, 3)->default(0);
            $table->decimal('amount', 14, 2)->default(0);
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['public_procurement_contract_id', 'status']);
        });

        Schema::create('public_procurement_progress_reports', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('public_procurement_contract_id')->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $table->date('issued_on')->nullable();
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            $table->decimal('work_amount', 14, 2)->default(0);
            $table->decimal('security_amount', 14, 2)->default(0);
            $table->decimal('retention_amount', 14, 2)->default(0);
            $table->decimal('certificate_amount', 14, 2)->default(0);
            $table->decimal('progress_percentage', 6, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['public_procurement_contract_id', 'number'], 'pub_proc_progress_number_unique');
        });

        Schema::create('public_procurement_payment_certificates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('public_procurement_contract_id')->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->foreignId('progress_report_id')->nullable()->constrained('public_procurement_progress_reports')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $table->date('issued_on')->nullable();
            $table->decimal('amount', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('public_procurement_official_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('public_procurement_contract_id')->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('type');
            $table->string('title');
            $table->string('protocol_number')->nullable();
            $table->date('recorded_on')->nullable();
            $table->string('status')->default('draft');
            $table->text('summary')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('public_procurement_guarantees', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('public_procurement_contract_id')->constrained('public_procurement_contracts')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('type');
            $table->string('title');
            $table->string('provider')->nullable();
            $table->string('policy_number')->nullable();
            $table->string('status')->default('valid');
            $table->decimal('amount', 14, 2)->nullable();
            $table->date('issued_on')->nullable();
            $table->date('expires_on')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['public_procurement_contract_id', 'expires_on']);
        });

        Schema::create('public_procurement_ai_extractions', function (Blueprint $table): void {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('document_type')->default('unknown');
            $table->string('status')->default('draft');
            $table->decimal('confidence', 5, 2)->default(0);
            $table->json('extracted_payload')->nullable();
            $table->longText('raw_text')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_procurement_ai_extractions');
        Schema::dropIfExists('public_procurement_guarantees');
        Schema::dropIfExists('public_procurement_official_records');
        Schema::dropIfExists('public_procurement_payment_certificates');
        Schema::dropIfExists('public_procurement_progress_reports');
        Schema::dropIfExists('public_procurement_measurements');
        Schema::dropIfExists('public_procurement_price_items');
        Schema::dropIfExists('public_procurement_economic_frames');
        Schema::dropIfExists('public_procurement_document_requirements');
        Schema::dropIfExists('public_procurement_deadlines');
        Schema::dropIfExists('public_procurement_contacts');
        Schema::dropIfExists('public_procurement_tender_eligibility_checks');
        Schema::dropIfExists('public_procurement_contracts');
        Schema::dropIfExists('public_procurement_tender_opportunities');
        Schema::dropIfExists('public_procurement_tender_sources');
    }
};
