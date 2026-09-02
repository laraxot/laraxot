<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(
            function (Blueprint $table): void {
                $table->id();
                $this->nullableMorphs($table, 'commentator', 'commentator_comments');
                $table->morphs('commentable');
                $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
                $table->longText('original_text');
                $table->longText('text');
                $json = Schema::getConnection()->getConfig('driver') === 'pgsql' ? 'jsonb' : 'json';
                $table->{$json}('extra')->nullable();
                $table->timestamp('approved_at')->nullable();
            }
        );
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                if (! $this->hasColumn('commentable_type')) {
                    $table->string('commentable_type')->nullable()->index();
                }
                if (! $this->hasColumn('commentable_id')) {
                    $table->string('commentable_id')->nullable()->index();
                }
                if (! $this->hasColumn('commentator_type')) {
                    $table->string('commentator_type')->nullable()->index();
                }
                if (! $this->hasColumn('commentator_id')) {
                    $table->string('commentator_id')->nullable()->index();
                }
                if (! $this->hasColumn('original_text')) {
                    $table->longText('original_text');
                }
                if (! $this->hasColumn('text')) {
                    $table->longText('text');
                }
                if (! $this->hasColumn('extra')) {
                    $json = Schema::getConnection()->getConfig('driver') === 'pgsql' ? 'jsonb' : 'json';
                    $table->{$json}('extra')->nullable();
                }
                if (! $this->hasColumn('approved_at')) {
                    $table->timestamp('approved_at')->nullable();
                }

                // Legacy Blog — nullable per insert morph Spatie (STORY-160)
                if ($this->hasColumn('comment')) {
                    $table->text('comment')->nullable()->change();
                }
                if ($this->hasColumn('post_id')) {
                    $table->integer('post_id')->nullable()->change();
                }
                if ($this->hasColumn('user_id')) {
                    $table->integer('user_id')->nullable()->change();
                }

                $this->updateTimestamps(table: $table, hasSoftDeletes: true);
            }
        );
    }

    protected function nullableMorphs(Blueprint $table, string $name, string $indexName): void
    {
        $table->string("{$name}_type")->nullable();
        $table->unsignedBigInteger("{$name}_id")->nullable();
        $table->index(["{$name}_type", "{$name}_id"], $indexName);
    }
};
