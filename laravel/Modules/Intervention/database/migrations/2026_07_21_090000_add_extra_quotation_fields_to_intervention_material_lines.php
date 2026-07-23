<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "Extra preventivo": materiale usato in cantiere che non era stato preventivato,
 * o che eccede la quantita' preventivata. Oggi quel materiale viene di fatto
 * regalato al cliente perche' nessuno lo intercetta prima della fatturazione.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intervention_material_lines', static function (Blueprint $table): void {
            $table->foreignId('quotation_item_id')
                ->nullable()
                ->after('intervention_id')
                ->constrained('quotation_items')
                ->nullOnDelete();

            // Quantita' oltre il preventivato (0 = tutto coperto dal preventivo).
            $table->decimal('extra_quantity', 10, 2)->default(0)->after('line_total');
            // Valore economico dell'extra: quanto si sta regalando se non lo si fattura.
            $table->decimal('extra_amount', 12, 2)->default(0)->after('extra_quantity');
            // pending = da decidere, to_bill = da fatturare, waived = regalato consapevolmente.
            $table->string('extra_status_code', 32)->default('pending')->after('extra_amount');

            $table->index(['extra_status_code']);
        });
    }

    public function down(): void
    {
        Schema::table('intervention_material_lines', static function (Blueprint $table): void {
            $table->dropConstrainedForeignId('quotation_item_id');
            $table->dropIndex(['extra_status_code']);
            $table->dropColumn(['extra_quantity', 'extra_amount', 'extra_status_code']);
        });
    }
};
