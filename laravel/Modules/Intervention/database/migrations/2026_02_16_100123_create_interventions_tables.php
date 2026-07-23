<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interventions', static function (Blueprint $table): void {
            $table->id();

            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('performed_by_user_id')->constrained('users')->cascadeOnDelete();

            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();

            $table->unsignedInteger('travel_km')->nullable();
            $table->unsignedInteger('work_minutes')->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['work_order_id']);
            $table->index(['performed_by_user_id']);
        });

        Schema::create('time_punches', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('intervention_id')->constrained('interventions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->timestamps();

            $table->index(['intervention_id']);
            $table->index(['user_id']);
            $table->index(['started_at']);
        });

        Schema::create('issue_reports', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('intervention_id')->constrained('interventions')->cascadeOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body');
            $table->timestamps();

            $table->index(['intervention_id']);
        });

        Schema::create('daily_notes', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('note_date');
            $table->text('body');
            $table->timestamps();

            $table->unique(['user_id', 'note_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_notes');
        Schema::dropIfExists('issue_reports');
        Schema::dropIfExists('time_punches');
        Schema::dropIfExists('interventions');
    }
};
