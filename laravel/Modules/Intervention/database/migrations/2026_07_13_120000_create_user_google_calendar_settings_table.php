<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_google_calendar_settings', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            $table->boolean('enabled')->default(false);
            $table->string('google_email')->nullable();
            $table->string('calendar_id')->nullable();      // defaults to 'primary'

            $table->text('access_token')->nullable();        // encrypted at model level
            $table->text('refresh_token')->nullable();       // encrypted at model level
            $table->timestamp('token_expires_at')->nullable();

            $table->timestamp('last_synced_at')->nullable();
            $table->longText('last_synced_events')->nullable();  // JSON cache fallback
            $table->timestamp('last_sync_error_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_google_calendar_settings');
    }
};
