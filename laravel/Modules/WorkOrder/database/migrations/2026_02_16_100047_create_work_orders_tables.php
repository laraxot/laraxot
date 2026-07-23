<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_order_statuses', static function (Blueprint $table): void {
            $table->id();
            $table->timestamps();

            $table->string('code')->unique();
            $table->string('name');
            $table->string('color')->nullable();
            $table->boolean('is_terminal')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
        });

        Schema::create('work_orders', static function (Blueprint $table): void {
            $table->id();

            $table->string('code')->nullable()->unique();

            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_site_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('primary_contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('work_order_statuses')->nullOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            $table->dateTime('opened_at')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('closed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id']);
            $table->index(['status_id']);
            $table->index(['scheduled_at']);
        });

        Schema::create('work_order_status_events', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('from_status_id')->nullable()->constrained('work_order_statuses')->nullOnDelete();
            $table->foreignId('to_status_id')->nullable()->constrained('work_order_statuses')->nullOnDelete();
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->dateTime('changed_at')->nullable();
            $table->timestamps();

            $table->index(['work_order_id']);
            $table->index(['changed_at']);
        });

        Schema::create('work_order_activities', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->default('note');
            $table->text('body');
            $table->dateTime('occurred_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['work_order_id']);
            $table->index(['type']);
        });

        Schema::create('work_order_assignments', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->nullable();
            $table->foreignId('assigned_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('assigned_at')->nullable();
            $table->timestamps();

            $table->unique(['work_order_id', 'user_id']);
            $table->index(['work_order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_assignments');
        Schema::dropIfExists('work_order_activities');
        Schema::dropIfExists('work_order_status_events');
        Schema::dropIfExists('work_orders');
        Schema::dropIfExists('work_order_statuses');
    }
};

