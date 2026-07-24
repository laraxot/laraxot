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
            $table->morphs('mediable');
            // No FK to users (cross-connection risk); Eloquent relation.
            $table->unsignedBigInteger('uploaded_by_user_id')->nullable()->index();
            $table->string('kind'); // document|photo|signature|sketch|other
            $table->string('title')->nullable();
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->json('meta')->nullable();
            $table->index(['kind']);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });
    }
};
