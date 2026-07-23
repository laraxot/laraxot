<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timber_e_invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained('timber_invoices')->cascadeOnDelete();
            $table->string('direction')->default('out')->index(); // out = emesse, in = ricevute
            $table->string('sdi_status')->default('da_inviare')->index();
            $table->string('sdi_identifier')->nullable();
            $table->string('file_name')->nullable();
            $table->longText('xml_content')->nullable();
            $table->timestamp('transmitted_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->unique('invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_e_invoices');
    }
};
