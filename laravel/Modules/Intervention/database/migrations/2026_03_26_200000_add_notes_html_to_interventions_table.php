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
            $table->longText('notes_html')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('interventions', static function (Blueprint $table): void {
            $table->dropColumn('notes_html');
        });
    }
};
