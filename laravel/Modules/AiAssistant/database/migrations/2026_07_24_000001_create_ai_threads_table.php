<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $table_name = 'ai_threads';

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $user_class = XotData::make()->getUserClass();

            $table->id();
            $table->uuid('public_id')->unique();
            $table->foreignIdFor($user_class, 'created_by_user_id')->index();
            $table->string('panel_id')->default('operator');
            $table->timestamp('last_message_at')->nullable();
            $table->json('meta')->nullable();
            $this->updateTimestamps($table);
        });
    }
};
