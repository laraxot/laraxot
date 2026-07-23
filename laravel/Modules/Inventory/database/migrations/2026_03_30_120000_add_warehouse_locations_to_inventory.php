<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_locations', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->string('code', 64);
            $table->string('label')->nullable();
            $table->unsignedInteger('row')->nullable();
            $table->unsignedInteger('column')->nullable();
            $table->unsignedInteger('level')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['warehouse_id', 'code'], 'warehouse_locations_unique_code');
            $table->index(['warehouse_id', 'is_active']);
            $table->index(['warehouse_id', 'row', 'column', 'level'], 'warehouse_locations_grid_index');
        });

        Schema::table('inventory_movements', static function (Blueprint $table): void {
            $table->foreignId('from_location_id')
                ->nullable()
                ->after('from_warehouse_id')
                ->constrained('warehouse_locations')
                ->nullOnDelete();
            $table->foreignId('to_location_id')
                ->nullable()
                ->after('to_warehouse_id')
                ->constrained('warehouse_locations')
                ->nullOnDelete();
        });

        Schema::table('product_units', static function (Blueprint $table): void {
            $table->foreignId('current_location_id')
                ->nullable()
                ->after('current_warehouse_id')
                ->constrained('warehouse_locations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('product_units', static function (Blueprint $table): void {
            $table->dropConstrainedForeignId('current_location_id');
        });

        Schema::table('inventory_movements', static function (Blueprint $table): void {
            $table->dropConstrainedForeignId('from_location_id');
            $table->dropConstrainedForeignId('to_location_id');
        });

        Schema::dropIfExists('warehouse_locations');
    }
};
