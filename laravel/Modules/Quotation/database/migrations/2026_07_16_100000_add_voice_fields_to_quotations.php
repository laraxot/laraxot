<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table): void {
            // What the operator dictated + the raw AI extraction, for transparency.
            $table->text('voice_transcript')->nullable()->after('notes');
            $table->json('voice_ai_response')->nullable()->after('voice_transcript');
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table): void {
            $table->dropColumn(['voice_transcript', 'voice_ai_response']);
        });
    }
};
