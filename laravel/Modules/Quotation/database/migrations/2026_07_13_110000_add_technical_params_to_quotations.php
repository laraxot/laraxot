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
            // Structured technical survey parameters (m², flue length, thermostat
            // present, etc.) captured by voice/attachments and used to drive the
            // parametric recompute of rule-governed line items.
            $table->json('technical_params')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('quotations', static function (Blueprint $table): void {
            $table->dropColumn('technical_params');
        });
    }
};
