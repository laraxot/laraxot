<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('timber_documents', function (Blueprint $table): void {
            $table->id();
            $table->string('document_type_code')->index();
            $table->string('number')->nullable()->index();
            $table->date('document_date')->index();
            $table->foreignId('customer_id')->nullable()->constrained('timber_customers')->nullOnDelete();
            $table->string('status_code')->default('draft')->index();
            $table->string('reference')->nullable();
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamp('stock_posted_at')->nullable();
            $table->string('created_by_user_id', 36)->nullable()->index();
            $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['document_type_code', 'number']);
        });

        Schema::create('timber_document_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('document_id')->constrained('timber_documents')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('timber_products')->nullOnDelete();
            $table->string('description');
            $table->decimal('quantity', 14, 3)->default(0);
            $table->string('unit_of_measure')->default('quintali');
            $table->decimal('unit_price', 14, 4)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->string('lot_code')->nullable();
            $table->decimal('line_total', 14, 2)->default(0);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_document_lines');
        Schema::dropIfExists('timber_documents');
    }
};
