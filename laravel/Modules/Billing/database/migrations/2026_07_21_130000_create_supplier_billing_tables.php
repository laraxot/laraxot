<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ciclo passivo: fornitori, DDT ricevuti e fatture fornitore, con il legame che
 * permette di conciliare quello che e' stato consegnato con quello che viene
 * fatturato.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', static function (Blueprint $table): void {
            $table->id();
            $table->string('legal_name');
            $table->string('vat_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['legal_name']);
        });

        Schema::create('delivery_notes', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            // Il DDT puo' essere imputato alla commessa per cui e' stato ordinato.
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            $table->string('number');
            $table->date('received_on')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['supplier_id', 'number']);
        });

        Schema::create('delivery_note_lines', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('delivery_note_id')->constrained('delivery_notes')->cascadeOnDelete();
            $table->string('description');
            $table->decimal('quantity', 12, 2)->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['delivery_note_id']);
        });

        Schema::create('supplier_invoices', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->string('number');
            $table->date('issued_on')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            // pending = da conciliare, reconciled = tutto quadra,
            // disputed = ci sono differenze da contestare al fornitore.
            $table->string('status_code', 32)->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['supplier_id', 'number']);
        });

        Schema::create('supplier_invoice_lines', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_invoice_id')->constrained('supplier_invoices')->cascadeOnDelete();
            // Riga di DDT a cui questa riga di fattura e' stata agganciata.
            $table->foreignId('delivery_note_line_id')->nullable()->constrained('delivery_note_lines')->nullOnDelete();
            $table->string('description');
            $table->decimal('quantity', 12, 2)->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['supplier_invoice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_invoice_lines');
        Schema::dropIfExists('supplier_invoices');
        Schema::dropIfExists('delivery_note_lines');
        Schema::dropIfExists('delivery_notes');
        Schema::dropIfExists('suppliers');
    }
};
