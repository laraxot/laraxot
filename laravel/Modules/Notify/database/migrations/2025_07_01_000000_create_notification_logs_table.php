<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Notify\Models\NotificationLog;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = NotificationLog::class;

    public function up(): void
    {
        if (! $this->hasTable('notification_logs')) {
            $this->tableCreate(function (Blueprint $table): void {
                $table->id();
                $table->string('notifiable_type');
                $table->unsignedBigInteger('notifiable_id');
                $table->string('type');
                $table->string('channel');
                $table->string('recipient');
                $table->string('subject')->nullable();
                $table->text('message')->nullable();
                $table->string('status')->default('pending');
                $table->timestamp('sent_at')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->text('error_message')->nullable();
                $table->json('metadata')->nullable();

                $table->index(['notifiable_type', 'notifiable_id']);
                $table->index('channel');
                $table->index('status');
                $table->index('sent_at');
            });

            $this->tableUpdate(function (Blueprint $table): void {
                $this->updateTimestamps($table, false);
            });
        }
    }
};
