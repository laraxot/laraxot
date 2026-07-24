<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('timber_products', function (Blueprint $table): void {
            $table->id();

            // Prodotto
            $table->string('code')->nullable()->unique();
            $table->string('description');
            $table->string('product_type_code')->default('lot_managed')->index();
            $table->string('category')->nullable()->index();
            $table->string('subcategory')->nullable();
            $table->string('unit_of_measure')->default('quintali');
            $table->json('list_prices')->nullable();
            $table->text('notes')->nullable();

            // Caratteristiche
            $table->string('barcode')->nullable()->unique();
            $table->string('vat_rate_code')->nullable();
            $table->string('commission_class')->nullable();
            $table->string('product_url')->nullable();
            $table->string('manufacturer')->nullable();
            $table->boolean('is_assembled')->default(false);
            $table->boolean('publish_on_ecommerce')->default(false);
            $table->string('free_1')->nullable();
            $table->string('free_2')->nullable();
            $table->string('free_3')->nullable();
            $table->string('free_4')->nullable();

            // Fornitura
            $table->foreignId('supplier_id')->nullable()->constrained('timber_suppliers')->nullOnDelete();
            $table->string('supplier_product_code')->nullable();
            $table->decimal('supplier_net_price', 12, 4)->nullable();
            $table->string('supply_notes')->nullable();

            // Magazzino
            $table->decimal('reorder_multiple', 12, 2)->nullable();
            $table->unsignedInteger('reorder_lead_days')->nullable();
            $table->decimal('minimum_stock', 12, 2)->nullable();
            $table->string('warehouse_location')->nullable();
            $table->decimal('average_cost', 12, 4)->nullable();
            $table->decimal('last_cost', 12, 4)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('description');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timber_products');
    }
};
