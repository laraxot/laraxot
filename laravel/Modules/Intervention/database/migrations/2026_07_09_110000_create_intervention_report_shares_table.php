<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intervention_report_shares', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('intervention_id')->constrained('interventions')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['intervention_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intervention_report_shares');
    }
};
