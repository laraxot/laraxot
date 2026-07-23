<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('energy_broker_import_batches', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source_name');
            $table->string('source_type')->default('manual_upload');
            $table->string('status')->default('draft');
            $table->unsignedInteger('total_files')->default(0);
            $table->unsignedInteger('processed_files')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('energy_broker_documents', static function (Blueprint $table): void {
            $table->id();
            $table->uuid('public_id')->nullable()->unique();
            $table->foreignId('energy_broker_import_batch_id')->nullable()->constrained('energy_broker_import_batches')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();

            $table->string('source_file_name');
            $table->string('file_hash')->nullable()->index();
            $table->string('document_type')->default('unknown');
            $table->string('service_type')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('analysis_status')->default('draft');
            $table->decimal('confidence_score', 5, 4)->nullable();
            $table->longText('raw_text')->nullable();
            $table->json('analysis_payload')->nullable();
            $table->json('normalized_payload')->nullable();
            $table->json('reviewed_payload')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->dateTime('imported_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['document_type']);
            $table->index(['analysis_status']);
        });

        Schema::create('energy_broker_customer_matches', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('energy_broker_document_id')->constrained('energy_broker_documents')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->decimal('score', 5, 2)->default(0);
            $table->string('status')->default('needs_review');
            $table->json('matched_on')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('energy_broker_supply_points', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();

            $table->string('service_type');
            $table->string('pod')->nullable();
            $table->string('pdr')->nullable();
            $table->string('supply_code')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('offer_name')->nullable();
            $table->decimal('annual_consumption', 14, 3)->nullable();
            $table->decimal('power_kw', 8, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'service_type']);
            $table->index(['pod']);
            $table->index(['pdr']);
        });

        Schema::create('energy_broker_supply_histories', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('energy_broker_supply_point_id')->constrained('energy_broker_supply_points')->cascadeOnDelete();
            $table->foreignId('energy_broker_document_id')->nullable()->constrained('energy_broker_documents')->nullOnDelete();
            $table->string('supplier_name');
            $table->string('offer_name')->nullable();
            $table->string('offer_code')->nullable();
            $table->string('status')->default('current');
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['energy_broker_supply_point_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('energy_broker_supply_histories');
        Schema::dropIfExists('energy_broker_supply_points');
        Schema::dropIfExists('energy_broker_customer_matches');
        Schema::dropIfExists('energy_broker_documents');
        Schema::dropIfExists('energy_broker_import_batches');
    }
};
