<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
    /**
     * db up..
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->increments('id');
                $table->string('title')->unique();
                $table->text('content')->nullable();
                $table->string('picture')->nullable();
                $table->unsignedInteger('category_id')->nullable()->index();
                $table->unsignedInteger('author_id')->nullable()->index();
                $table->string('status')->nullable();
                $table->boolean('show_on_homepage')->default(0);
                $table->integer('read_time')->nullable();
                $table->string('slug');
                $table->text('excerpt')->nullable()->default(null);
                $table->date('published_at')->nullable();
                $table->timestamps();
                $table->softDeletes();
                // $table->foreign('category_id'); // ->references('id')->on('categories');
                // $table->foreign('author_id'); // ->references('id')->on('users');
            }
        );
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                /*
                if ($this->hasColumn('auth_user_id')) {
                    $table->dropColumn('user_id');
                    $table->renameColumn('auth_user_id', 'user_id');
                }
                */
                if (! $this->hasColumn('published_at')) {
                    $table->dateTime('published_at')->nullable();
                }
                if (! $this->hasColumn('slug')) {
                    $table->string('slug')->unique();
                }
                if (! $this->hasColumn('title')) {
                    $table->string('title');
                }
                if (! $this->hasColumn('content_blocks')) {
                    // $table->text('content_blocks')->nullable();
                    $table->json('content_blocks'); // ->default(new Expression('(JSON_ARRAY())'));
                }
                if (! $this->hasColumn('sidebar_blocks')) {
                    // $table->text('footer_blocks')->nullable();
                    $table->json('sidebar_blocks'); // ->default(new Expression('(JSON_ARRAY())'));
                }
                if (! $this->hasColumn('footer_blocks')) {
                    // $table->text('footer_blocks')->nullable();
                    $table->json('footer_blocks'); // ->default(new Expression('(JSON_ARRAY())'));
                }
                if (! $this->hasColumn('main_image_url')) {
                    $table->text('main_image_url')->nullable();
                }
                if (! $this->hasColumn('main_image_upload')) {
                    $table->text('main_image_upload')->nullable();
                }
                if (! $this->hasColumn('is_featured')) {
                    $table->boolean('is_featured')->default(false);
                }
                if (! $this->hasColumn('description')) {
                    $table->string('description');
                }
                if (! $this->hasColumn('uuid')) {
                    $table->uuid('uuid')->nullable()->after('id');
                }

                if (! $this->hasColumn('closed_at')) {
                    $table->dateTime('closed_at')->nullable();
                }

                if (! $this->hasColumn('status')) {
                    $table->string('status')->nullable();
                }

                if (! $this->hasColumn('status_display')) {
                    $table->boolean('status_display')->default(false);
                }

                if (! $this->hasColumn('bet_end_date')) {
                    $table->dateTime('bet_end_date')->nullable();
                    $table->dateTime('event_start_date')->nullable();
                    $table->dateTime('event_end_date')->nullable();
                    $table->boolean('is_wagerable')->default(false);
                    $table->integer('wagers_count')->nullable();
                    $table->integer('wagers_count_canonical')->nullable();
                    $table->integer('wagers_count_total')->nullable();
                }

                if (! $this->hasColumn('wagers')) {
                    $table->boolean('wagers')->nullable();
                }

                if (! $this->hasColumn('brier_score')) {
                    $table->string('brier_score')->nullable();
                    $table->string('brier_score_play_money')->nullable();
                    $table->string('brier_score_real_money')->nullable();
                    $table->float('volume_play_money')->nullable();
                    $table->float('volume_real_money')->nullable();
                    $table->boolean('is_following')->default(false);
                }

                if (! $this->hasColumn('rewarded_at')) {
                    $table->datetimetz('rewarded_at')->nullable();
                }

                if (! $this->hasColumn('type')) {
                    $table->string('type')->nullable();
                }

                $this->updateTimestamps(table: $table, hasSoftDeletes: true);
            }
        );
    }
};
