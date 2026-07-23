<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_threads', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->string('panel_id')->default('operator');
            $table->timestamp('last_message_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_thread_id')->constrained('ai_threads')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('role'); // user|assistant|tool|system
            $table->longText('content')->nullable();
            $table->json('payload')->nullable(); // tool args/results, structured output, etc.
            $table->timestamps();
        });

        Schema::create('ai_action_proposals', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignId('ai_thread_id')->constrained('ai_threads')->cascadeOnDelete();
            $table->foreignId('proposed_by_user_id')->constrained('users');
            $table->string('type'); // e.g. start_time_punch, stop_time_punch, upsert_daily_note, change_work_order_status, finalize_intervention_report
            $table->json('payload');
            $table->longText('preview')->nullable();
            $table->string('status')->default('pending'); // pending|cancelled|confirmed|executed|failed
            $table->foreignId('confirmed_by_user_id')->nullable()->constrained('users');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->json('result')->nullable();
            $table->longText('error')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_tool_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_thread_id')->constrained('ai_threads')->cascadeOnDelete();
            $table->foreignId('ai_action_proposal_id')->nullable()->constrained('ai_action_proposals')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('tool_name');
            $table->json('arguments')->nullable();
            $table->json('response')->nullable();
            $table->string('status')->default('ok'); // ok|error
            $table->longText('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_tool_logs');
        Schema::dropIfExists('ai_action_proposals');
        Schema::dropIfExists('ai_messages');
        Schema::dropIfExists('ai_threads');
    }
};

