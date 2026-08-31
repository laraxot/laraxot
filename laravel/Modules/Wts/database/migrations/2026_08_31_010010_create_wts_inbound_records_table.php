<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WtsInboundRecord::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('legacy_reference')->nullable()->index();
            $table->string('form_number')->nullable()->index();
            $table->string('source_company_name');
            $table->string('vehicle_reference')->nullable();
            $table->decimal('gross_quantity', 12, 2)->default(0);
            $table->decimal('entry_weight', 12, 2)->nullable();
            $table->string('status_code')->default('registered')->index();
            $table->foreignId('technical_sheet_snapshot_id')->nullable()->constrained('wts_technical_sheet_snapshots')->nullOnDelete();
            $table->timestamp('received_at')->nullable();
            // L'id utente e' un UUID di 36 caratteri, non un intero: un foreignId qui
            // troncherebbe il valore a 0 e perderebbe silenziosamente l'attribuzione.
            $table->string('created_by_user_id', 36)->nullable();
            $table->text('notes')->nullable();
            $table->decimal('tare_weight', 12, 2)->nullable();
            $table->decimal('net_weight', 12, 2)->nullable();
            $table->string('acceptance_status')->nullable();
            $table->timestamp('taken_charge_at')->nullable();
            $table->string('commercial_company_name')->nullable();
            $table->string('batch_number')->nullable()->index();
            $table->unsignedInteger('ecos_units_count')->nullable();
            $table->boolean('is_weight_closed')->nullable();
            $table->string('treatment_line_code')->nullable();
            $table->string('production_cycle_code')->nullable();
            $table->json('legacy_payload')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
