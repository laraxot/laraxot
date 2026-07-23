<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metel_catalogs', static function (Blueprint $table): void {
            $table->id();

            $table->string('code')->unique();          // human identifier / slug
            $table->string('brand_code')->nullable();   // METEL sigla marchio (3 chars)
            $table->string('name');
            $table->string('manufacturer')->nullable();

            $table->string('source_type')->default('file'); // file | api
            $table->string('version')->nullable();
            $table->dateTime('imported_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['brand_code']);
            $table->index(['is_active']);
        });

        Schema::create('metel_catalog_items', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('metel_catalog_id')->constrained('metel_catalogs')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();

            $table->string('metel_code');               // codice prodotto azienda
            $table->string('barcode')->nullable();       // EAN
            $table->string('etim_code')->nullable();     // Electrocod / ETIM classification
            $table->string('description');
            $table->string('unit_of_measure')->nullable();
            $table->string('packaging')->nullable();     // pieces per pack

            $table->decimal('gross_price', 14, 4)->default(0);
            $table->decimal('net_price', 14, 4)->nullable();

            $table->timestamps();

            $table->unique(['metel_catalog_id', 'metel_code']);
            $table->index(['metel_code']);
            $table->index(['description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metel_catalog_items');
        Schema::dropIfExists('metel_catalogs');
    }
};
