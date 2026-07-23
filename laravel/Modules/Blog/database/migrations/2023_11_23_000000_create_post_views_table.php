<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Class CreatePostViewsTable.
 */
return new class extends XotBaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->string('ip_address', 55);
                $table->string('user_agent', 255);
                $table->foreignId('post_id'); // ->references('id')->on('posts')->cascadeOnDelete();
                $table->foreignId('user_id'); // ->nullable()->references('id')->on('users')->cascadeOnDelete();
                $table->timestamps();
            }
        );
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                // if (! $this->hasColumn('parent_id')) {
                //    $table->foreignId('parent_id')->nullable();
                // }
                // if (! $this->hasColumn('profile_photo_path')) {
                //    $table->string('profile_photo_path', 2048)->nullable();
                // }
                $this->updateTimestamps(table: $table, hasSoftDeletes: true);
            }
        );
    }
};
