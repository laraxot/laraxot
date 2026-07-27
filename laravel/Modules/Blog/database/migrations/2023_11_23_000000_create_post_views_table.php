<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Blog\Models\Article;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

/*
 * Class CreatePostViewsTable.
 */
return new class() extends XotBaseMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        // -- CREATE --
        $this->tableCreate(
            static function (Blueprint $table) use ($userClass): void {
                $table->id();
                $table->string('ip_address', 55);
                $table->string('user_agent', 255);
                $table->foreignIdFor(Article::class, 'post_id');
                $table->foreignIdFor($userClass, 'user_id')->nullable()->index();
                $table->timestamps();
            }
        );
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                $this->updateTimestamps(table: $table, hasSoftDeletes: true);
            }
        );
    }
};
