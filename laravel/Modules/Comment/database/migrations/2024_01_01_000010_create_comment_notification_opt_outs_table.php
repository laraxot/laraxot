<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
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
                $this->nullableMorphs($table, 'commentator', 'cn_opt_outs_commentator');
                $table->morphs('commentable', 'cn_opt_outs_commentable');
                $table->unsignedBigInteger('user_id')->nullable();
            }
        );
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
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
