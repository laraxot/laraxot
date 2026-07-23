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
            $table->string('report_payment_session_id')->nullable()->after('total_amount');
            $table->timestamp('report_paid_at')->nullable()->after('report_payment_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('interventions', function (Blueprint $table): void {
            $table->dropColumn(['report_payment_session_id', 'report_paid_at']);
        });
    }
};
