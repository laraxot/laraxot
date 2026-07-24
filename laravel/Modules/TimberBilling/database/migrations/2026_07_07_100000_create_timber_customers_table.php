<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('timber_customers', function (Blueprint $table): void {
            $table->id();

            // Anagrafica
            $table->string('code')->nullable()->unique();
            $table->string('business_name');
            $table->string('vat_number')->nullable();
            $table->string('tax_code')->nullable();
            $table->boolean('is_customer')->default(true);
            $table->boolean('is_supplier')->default(false);

            // Sede operativa
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->default('Italia');

            // Fattura elettronica
            $table->string('sdi_recipient_code')->nullable();
            $table->string('sdi_admin_ref')->nullable();
            $table->string('pec')->nullable();

            // Contatti
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();

            // Rapporti commerciali
            $table->decimal('default_discount_percent', 5, 2)->nullable();
            $table->string('price_list_code')->nullable();
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->foreignId('agent_id')->nullable()->constrained('timber_agents')->nullOnDelete();
            $table->string('payment_terms')->nullable();
            $table->string('iban')->nullable();
            $table->string('our_bank')->nullable();
            $table->string('carriage_code')->nullable();
            $table->string('vat_rate_code')->nullable();
            $table->string('intent_declaration_number')->nullable();
            $table->date('intent_declaration_date')->nullable();
            $table->boolean('send_documents_by_email')->default(false);
            $table->boolean('invoice_with_withholding')->default(false);
            $table->text('document_notice')->nullable();
            $table->text('document_note')->nullable();

            // Varie
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('business_name');
            $table->index('vat_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_customers');
    }
};
