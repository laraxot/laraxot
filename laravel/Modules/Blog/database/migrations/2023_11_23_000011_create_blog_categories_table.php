<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Blog\Models\Category;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Class CreateBlogCategoriesTable.
 */
return new class extends XotBaseMigration {
    protected ?string $model_class = Category::class;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->string('title');
                $table->string('slug');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->timestamps();
            }
        );
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                if (! $this->hasColumn('description')) {
                    $table->text('description')->nullable();
                }
                // if (! $this->hasColumn('profile_photo_path')) {
                //    $table->string('profile_photo_path', 2048)->nullable();
                // }
                if (! $this->hasColumn('parent_id')) {
                    $table->unsignedBigInteger('parent_id')->nullable();
                }

                if (! $this->hasColumn('icon')) {
                    $table->text('icon')->nullable();
                }
                $this->updateTimestamps(table: $table, hasSoftDeletes: true);
            }
        );
    }
};
