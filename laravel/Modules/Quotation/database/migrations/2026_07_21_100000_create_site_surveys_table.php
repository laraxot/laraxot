<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sopralluogo: il rilievo in loco che precede il preventivo.
 *
 * Vive nel modulo Quotations perche' e' la fase pre-vendita da cui nasce il
 * preventivo, non l'esecuzione (quella e' Interventions). Foto, video e note
 * audio sono allegati polimorfici sulla tabella `media` gia' esistente.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_surveys', static function (Blueprint $table): void {
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
            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id']);
            $table->index(['work_order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_surveys');
    }
};
