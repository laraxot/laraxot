<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Blog\Models\Article;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
    protected ?string $model_class = Article::class;

    public function up(): void
    {
        $this->tableUpdate(function (Blueprint $table): void {
            if ($this->hasColumn('category_id')) {
                $table->string('category_id')->nullable()->change();
            }
        });
    }
};
