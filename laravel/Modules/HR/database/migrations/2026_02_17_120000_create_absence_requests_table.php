<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $user_class = XotData::make()->getUserClass();

            $table->id();
            $table->foreignIdFor($user_class, 'user_id')->index();

            $table->string('type');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->text('notes')->nullable();

            $table->string('status')->default('pending');
            $table->foreignIdFor($user_class, 'decided_by_user_id')->nullable()->index();
            $table->dateTime('decided_at')->nullable();

            $table->index(['user_id', 'starts_at']);
            $table->index(['status']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
