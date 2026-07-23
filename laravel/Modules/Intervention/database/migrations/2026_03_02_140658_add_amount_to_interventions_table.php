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
            $table->decimal('amount', 10, 2)->nullable()->after('work_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('interventions', static function (Blueprint $table): void {
            $table->dropColumn('amount');
        });
    }
};
