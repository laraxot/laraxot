<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Quotation\Models\SiteSurvey;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/**
 * Sopralluogo: il rilievo in loco che precede il preventivo.
 *
 * Vive nel modulo Quotation perche' e' la fase pre-vendita da cui nasce il
 * preventivo, non l'esecuzione (quella e' WorkOrder). Foto, video e note
 * audio sono allegati gestiti via Spatie MediaLibrary (vedi SiteSurvey model).
 */
return new class() extends XotBaseMigration
{
    protected ?string $model_class = SiteSurvey::class;

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained('customer_sites')->nullOnDelete();
            // Un sopralluogo puo' nascere su una commessa gia' aperta (variante,
            // sopralluogo aggiuntivo) oppure precederla del tutto.
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            // Preventivo generato dal sopralluogo: chiude il cerchio rilievo -> offerta.
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->nullOnDelete();
            $table->foreignId('performed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title')->nullable();
            $table->text('notes')->nullable();
            // planned = da fare, done = rilevato, quoted = preventivo emesso.
            $table->string('status_code', 32)->default('planned')->index();
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('performed_at')->nullable();

            $table->index(['customer_id']);
            $table->index(['work_order_id']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
