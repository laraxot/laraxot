<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('timber_purchase_records', function (Blueprint $table): void {
            $table->string('purchase_price_unit')->default('quintale')->after('unit_purchase_price');
        });
    }

    public function down(): void
    {
        Schema::table('timber_purchase_records', function (Blueprint $table): void {
            $table->dropColumn('purchase_price_unit');
        });
    }
};
