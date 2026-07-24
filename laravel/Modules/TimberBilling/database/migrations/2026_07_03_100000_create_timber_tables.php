<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('timber_suppliers', function (Blueprint $table): void {
            $table->id();
            $table->string('legal_name');
            $table->string('vat_number')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('province')->nullable();
            $table->string('country_code')->default('IT');
            $table->string('certifications')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('timber_transporters', function (Blueprint $table): void {
            $table->id();
            $table->string('legal_name');
            $table->string('vat_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('timber_agents', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('zone')->nullable();
            $table->decimal('commission_percent', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('timber_purchase_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_id')->constrained('timber_suppliers')->restrictOnDelete();
            $table->foreignId('transporter_id')->nullable()->constrained('timber_transporters')->nullOnDelete();
            $table->string('origin_forest')->nullable();
            $table->string('ddt_number')->nullable()->index();
            $table->date('ddt_date')->nullable();
            $table->string('wood_species')->index();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('unit_of_measure');
            $table->decimal('gross_weight', 12, 2)->nullable();
            $table->decimal('net_weight', 12, 2)->nullable();
            $table->decimal('unit_purchase_price', 12, 2)->nullable();
            $table->string('certifications')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->string('status_code')->default('registered')->index();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
        });

        Schema::create('timber_lots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('purchase_record_id')->constrained('timber_purchase_records')->cascadeOnDelete();
            $table->string('lot_code')->unique();
            $table->string('status_code')->default('available')->index();
            $table->string('current_area_code')->default('piazzale')->index();
            $table->decimal('quantity_available', 12, 2)->default(0);
            $table->string('unit_of_measure');
            $table->string('wood_species')->index();
            $table->timestamps();
        });

        Schema::create('timber_processing_steps', function (Blueprint $table): void {
            $table->id();
            $table->string('work_sheet_code')->unique();
            $table->string('step_type_code')->index();
            $table->decimal('input_quantity', 12, 2)->nullable();
            $table->decimal('output_quantity', 12, 2)->nullable();
            $table->decimal('waste_quantity', 12, 2)->nullable();
            $table->decimal('recoverable_quantity', 12, 2)->nullable();
            $table->string('unit_of_measure');
            $table->foreignId('executed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('executed_at');
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('timber_derived_materials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('processing_step_id')->constrained('timber_processing_steps')->cascadeOnDelete();
            $table->string('output_code')->unique();
            $table->string('wood_species')->index();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('unit_of_measure');
            $table->string('status_code')->default('da_lavorare')->index();
            $table->string('current_area_code')->default('zona_taglio')->index();
            $table->boolean('is_waste')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('timber_processing_step_processable', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('processing_step_id')->constrained('timber_processing_steps')->cascadeOnDelete();
            $table->morphs('processable');
            $table->unique(['processing_step_id', 'processable_type', 'processable_id'], 'timber_step_processable_unique');
        });

        Schema::create('timber_drying_cycles', function (Blueprint $table): void {
            $table->id();
            $table->morphs('dryable');
            $table->timestamp('entered_at');
            $table->timestamp('expected_exit_at')->nullable();
            $table->timestamp('actual_exit_at')->nullable();
            $table->decimal('target_humidity_percent', 5, 2)->nullable();
            $table->decimal('available_quantity', 12, 2)->default(0);
            $table->string('status_code')->default('in_progress')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('timber_humidity_checks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('drying_cycle_id')->constrained('timber_drying_cycles')->cascadeOnDelete();
            $table->timestamp('checked_at');
            $table->decimal('humidity_percent', 5, 2);
            $table->foreignId('checked_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('timber_packaged_units', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('derived_material_id')->constrained('timber_derived_materials')->cascadeOnDelete();
            $table->string('package_code')->unique();
            $table->string('qr_code')->unique();
            $table->string('package_type_code')->index();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('unit_of_measure');
            $table->string('status_code')->default('pronto_per_vendita')->index();
            $table->timestamps();
        });

        Schema::create('timber_movements', function (Blueprint $table): void {
            $table->id();
            $table->morphs('movable');
            $table->string('from_area_code')->nullable();
            $table->string('to_area_code');
            $table->foreignId('moved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moved_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('timber_label_prints', function (Blueprint $table): void {
            $table->id();
            $table->morphs('labelable');
            $table->string('label_code')->unique();
            $table->string('qr_payload');
            $table->string('print_reason_code')->default('initial_print');
            $table->foreignId('printed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('printed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_label_prints');
        Schema::dropIfExists('timber_movements');
        Schema::dropIfExists('timber_packaged_units');
        Schema::dropIfExists('timber_humidity_checks');
        Schema::dropIfExists('timber_drying_cycles');
        Schema::dropIfExists('timber_processing_step_processable');
        Schema::dropIfExists('timber_derived_materials');
        Schema::dropIfExists('timber_processing_steps');
        Schema::dropIfExists('timber_lots');
        Schema::dropIfExists('timber_purchase_records');
        Schema::dropIfExists('timber_agents');
        Schema::dropIfExists('timber_transporters');
        Schema::dropIfExists('timber_suppliers');
    }
};
