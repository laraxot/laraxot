<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('timber_stock_movements', function (Blueprint $table): void {
            $table->id();
            $table->date('movement_date')->index();
            $table->foreignId('product_id')->constrained('timber_products')->cascadeOnDelete();
            $table->string('direction_code')->index();
            $table->decimal('quantity', 14, 3);
            $table->string('unit_of_measure');
            $table->foreignId('customer_id')->nullable()->constrained('timber_customers')->nullOnDelete();
            $table->string('document_type_code')->nullable()->index();
            $table->string('document_reference')->nullable();
            $table->string('lot_code')->nullable()->index();
            $table->string('category')->nullable();
            $table->decimal('unit_cost', 14, 4)->nullable();
            $table->text('notes')->nullable();
            $table->string('created_by_user_id', 36)->nullable()->index();
            $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_stock_movements');
    }
};
