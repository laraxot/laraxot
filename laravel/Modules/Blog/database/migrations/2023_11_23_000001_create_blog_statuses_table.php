<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Blog\Models\Status;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Class CreateBlogStatusesTable.
 */
return new class extends XotBaseMigration {
    protected ?string $model_class = Status::class;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->text('reason')->nullable();
                $table->morphs('model');
            }
        );
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                if (! $this->hasColumn('name')) {
                    $table->string('name');
                }
                if (! $this->hasColumn('reason')) {
                    $table->string('reason')->nullable();
                }
                if (! $this->hasColumn('model_id')) {
                    $table->morphs('model');
                }

                $this->updateTimestamps(table: $table, hasSoftDeletes: true);
            }
        );
    }
};
