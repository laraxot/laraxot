<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouses', static function (Blueprint $table): void {
            if (Schema::hasColumn('warehouses', 'vehicle_id')) {
                return;
            }

            $table
                ->foreignId('vehicle_id')
                ->nullable()
                ->after('code')
                ->constrained('vehicles')
                ->nullOnDelete();

            $table->index(['vehicle_id']);
        });
    }

    public function down(): void
    {
        Schema::table('warehouses', static function (Blueprint $table): void {
            if (! Schema::hasColumn('warehouses', 'vehicle_id')) {
                return;
            }

            $table->dropConstrainedForeignId('vehicle_id');
        });
    }
};

