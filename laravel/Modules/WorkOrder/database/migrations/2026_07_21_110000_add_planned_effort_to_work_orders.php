<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tempi previsti sulla commessa, per confrontarli con il consuntivo che gia'
 * arriva dagli interventi (interventions.work_minutes). Senza il previsto non
 * si puo' sapere se una commessa sta sforando finche' non e' troppo tardi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', static function (Blueprint $table): void {
            $table->unsignedInteger('planned_minutes')->nullable()->after('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', static function (Blueprint $table): void {
            $table->dropColumn('planned_minutes');
        });
    }
};
