<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('timber_payment_installments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained('timber_invoices')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('direction')->default('in')->index(); // in = incasso, out = pagamento fornitore
            $table->date('due_date')->index();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('payment_method_code')->nullable()->index();
            $table->string('status_code')->default('da_saldare')->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('reminded_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_payment_installments');
    }
};
