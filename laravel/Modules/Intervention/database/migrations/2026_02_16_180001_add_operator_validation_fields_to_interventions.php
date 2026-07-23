<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interventions', static function (Blueprint $table): void {
            $table->unsignedInteger('travel_minutes')->default(0)->after('travel_km');
            $table->string('outcome_code')->nullable()->after('notes'); // completed|suspended|quotation
            $table->dateTime('validated_at')->nullable()->after('outcome_code');
            $table->foreignId('validated_by_user_id')->nullable()->after('validated_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('interventions', static function (Blueprint $table): void {
            $table->dropConstrainedForeignId('validated_by_user_id');
            $table->dropColumn(['validated_at', 'outcome_code', 'travel_minutes']);
        });
    }
};
