<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interventions', function (Blueprint $table): void {
            // Allow interventions attached directly to a customer (no work order)
            $table->unsignedBigInteger('work_order_id')->nullable()->change();

            $table->foreignId('customer_id')
                ->nullable()
                ->after('work_order_id')
                ->constrained('customers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('interventions', function (Blueprint $table): void {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
            $table->unsignedBigInteger('work_order_id')->nullable(false)->change();
        });
    }
};
