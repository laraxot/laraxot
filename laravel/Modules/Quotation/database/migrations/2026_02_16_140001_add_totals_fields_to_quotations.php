<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', static function (Blueprint $table): void {
            $table->decimal('discount_amount', 12, 2)->default(0)->after('subtotal');
            $table->decimal('taxable_amount', 12, 2)->default(0)->after('discount_amount');
            $table->decimal('tax_percent', 5, 2)->default(22)->after('taxable_amount');
        });
    }

    public function down(): void
    {
        Schema::table('quotations', static function (Blueprint $table): void {
            $table->dropColumn(['discount_amount', 'taxable_amount', 'tax_percent']);
        });
    }
};

