<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_frequencies', static function (Blueprint $table): void {
            $table->id();
            $table->timestamps();

            $table->string('name')->unique();
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('catalog_measure_units', static function (Blueprint $table): void {
            $table->id();
            $table->timestamps();

            $table->string('name')->unique();
            $table->string('symbol')->nullable();
        });

        Schema::create('catalog_part_types', static function (Blueprint $table): void {
            $table->id();
            $table->timestamps();

            $table->string('name')->unique();
        });

        Schema::create('catalog_models', static function (Blueprint $table): void {
            $table->id();
            $table->timestamps();

            $table->string('name')->unique();
        });

        Schema::create('products', static function (Blueprint $table): void {
            $table->id();

            $table->string('name');
            $table->string('sku')->nullable()->unique();
            $table->text('description')->nullable();

            $table->foreignId('catalog_model_id')->nullable()->constrained('catalog_models')->nullOnDelete();
            $table->foreignId('catalog_part_type_id')->nullable()->constrained('catalog_part_types')->nullOnDelete();
            $table->foreignId('catalog_measure_unit_id')->nullable()->constrained('catalog_measure_units')->nullOnDelete();
            $table->foreignId('catalog_frequency_id')->nullable()->constrained('catalog_frequencies')->nullOnDelete();

            $table->decimal('unit_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('catalog_models');
        Schema::dropIfExists('catalog_part_types');
        Schema::dropIfExists('catalog_measure_units');
        Schema::dropIfExists('catalog_frequencies');
    }
};
