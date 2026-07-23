<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timber_invoices', function (Blueprint $table): void {
            $table->id();
            $table->string('kind')->index();
            $table->string('number')->nullable();
            $table->date('invoice_date')->index();
            $table->foreignId('customer_id')->nullable()->constrained('timber_customers')->nullOnDelete();
            $table->foreignId('source_document_id')->nullable()->constrained('timber_documents')->nullOnDelete();
            $table->string('status_code')->default('draft')->index();
            $table->string('payment_method_code')->nullable();
            $table->string('payment_terms')->nullable();
            $table->decimal('taxable_amount', 14, 2)->default(0);
            $table->decimal('vat_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('stock_posted_at')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['kind', 'number']);
        });

        Schema::create('timber_invoice_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('invoice_id')->constrained('timber_invoices')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('timber_products')->nullOnDelete();
            $table->string('description');
            $table->decimal('quantity', 14, 3)->default(0);
            $table->string('unit_of_measure')->default('quintali');
            $table->decimal('unit_price', 14, 4)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(22);
            $table->string('lot_code')->nullable();
            $table->decimal('line_total', 14, 2)->default(0);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_invoice_lines');
        Schema::dropIfExists('timber_invoices');
    }
};
