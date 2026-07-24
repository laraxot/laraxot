<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->morphs('signable');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('disk')->nullable();
            $table->string('path')->nullable();
            $table->string('mime_type')->nullable();
            $table->dateTime('signed_at')->nullable();
            $table->json('payload')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
