<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intervention_material_lines', static function (Blueprint $table): void {
            $table->string('unit')->default('pz')->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('intervention_material_lines', static function (Blueprint $table): void {
            $table->dropColumn('unit');
        });
    }
};
