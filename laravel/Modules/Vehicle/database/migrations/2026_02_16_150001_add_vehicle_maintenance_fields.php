<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', static function (Blueprint $table): void {
            $table->unsignedInteger('odometer_km')->nullable()->after('year');
            $table->date('last_service_on')->nullable()->after('odometer_km');
            $table->date('insurance_expires_on')->nullable()->after('last_service_on');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', static function (Blueprint $table): void {
            $table->dropColumn(['odometer_km', 'last_service_on', 'insurance_expires_on']);
        });
    }
};

