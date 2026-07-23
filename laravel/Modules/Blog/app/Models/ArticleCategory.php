<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;

/**
 * Modules\Blog\Models\ArticleCategory.
 *
 * @property string      $id
 * @property int         $category_id
 * @property int         $article_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 *
 * @method static Builder|ArticleCategory newModelQuery()
 * @method static Builder|ArticleCategory newQuery()
 * @method static Builder|ArticleCategory onlyTrashed()
 * @method static Builder|ArticleCategory query()
 * @method static Builder|ArticleCategory whereArticleId($value)
 * @method static Builder|ArticleCategory whereCategoryId($value)
 * @method static Builder|ArticleCategory whereCreatedAt($value)
 * @method static Builder|ArticleCategory whereCreatedBy($value)
 * @method static Builder|ArticleCategory whereId($value)
 * @method static Builder|ArticleCategory whereUpdatedAt($value)
 * @method static Builder|ArticleCategory whereUpdatedBy($value)
 * @method static Builder|ArticleCategory withTrashed()
 * @method static Builder|ArticleCategory withoutTrashed()
 *
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder|ArticleCategory whereDeletedAt($value)
 * @method static Builder|ArticleCategory whereDeletedBy($value)
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 *
 * @method static ArticleCategory|null             first()
 * @method static Collection<int, ArticleCategory> get()
 * @method static ArticleCategory                  create(array<string, mixed> $attributes = [])
 * @method static ArticleCategory                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|ArticleCategory  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|ArticleCategory  whereNotNull((string|Expression) $columns)
 * @method static int                              count(string $columns = '*')
 *
 * @mixin \Eloquent
 */
class ArticleCategory extends BasePivot
{
    protected $fillable = ['category_id', 'article_id'];
}
