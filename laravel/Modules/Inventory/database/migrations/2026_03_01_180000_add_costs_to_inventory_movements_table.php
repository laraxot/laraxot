<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_movements', static function (Blueprint $table): void {
            if (! Schema::hasColumn('inventory_movements', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->nullable()->after('quantity');
            }

            if (! Schema::hasColumn('inventory_movements', 'line_total')) {
                $table->decimal('line_total', 10, 2)->nullable()->after('unit_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inventory_movements', static function (Blueprint $table): void {
            if (Schema::hasColumn('inventory_movements', 'line_total')) {
                $table->dropColumn('line_total');
            }

            if (Schema::hasColumn('inventory_movements', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
        });
    }
};
