<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', static function (Blueprint $table): void {
            $table->id();

            $table->timestamp('created_at')->useCurrent();

            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');

            $table->string('subject_type');
            $table->string('subject_id');

            $table->string('panel_id')->nullable();
            $table->string('route')->nullable();
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();

            $table->json('before')->nullable();
            $table->json('after')->nullable();

            $table->index(['created_at']);
            $table->index(['actor_user_id', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

