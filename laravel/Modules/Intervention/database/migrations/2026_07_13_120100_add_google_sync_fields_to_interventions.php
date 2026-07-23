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
            $table->string('google_event_id')->nullable()->after('notes_html');
            $table->string('google_calendar_id')->nullable()->after('google_event_id');
            // null | pending | synced | deleted_external | disabled | missing_owner
            $table->string('google_sync_status')->nullable()->after('google_calendar_id');
            $table->timestamp('google_synced_at')->nullable()->after('google_sync_status');

            $table->index(['google_event_id']);
        });
    }

    public function down(): void
    {
        Schema::table('interventions', static function (Blueprint $table): void {
            $table->dropColumn(['google_event_id', 'google_calendar_id', 'google_sync_status', 'google_synced_at']);
        });
    }
};
