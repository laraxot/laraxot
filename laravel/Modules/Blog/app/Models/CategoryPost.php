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
 * Modules\Blog\Models\CategoryPost.
 *
 * @property string      $id
 * @property int         $category_id
 * @property int         $post_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 *
 * @method static Builder|CategoryPost newModelQuery()
 * @method static Builder|CategoryPost newQuery()
 * @method static Builder|CategoryPost onlyTrashed()
 * @method static Builder|CategoryPost query()
 * @method static Builder|CategoryPost whereCategoryId($value)
 * @method static Builder|CategoryPost whereCreatedAt($value)
 * @method static Builder|CategoryPost whereCreatedBy($value)
 * @method static Builder|CategoryPost whereId($value)
 * @method static Builder|CategoryPost wherePostId($value)
 * @method static Builder|CategoryPost whereUpdatedAt($value)
 * @method static Builder|CategoryPost whereUpdatedBy($value)
 * @method static Builder|CategoryPost withTrashed()
 * @method static Builder|CategoryPost withoutTrashed()
 *
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder|CategoryPost whereDeletedAt($value)
 * @method static Builder|CategoryPost whereDeletedBy($value)
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 *
 * @method static CategoryPost|null             first()
 * @method static Collection<int, CategoryPost> get()
 * @method static CategoryPost                  create(array<string, mixed> $attributes = [])
 * @method static CategoryPost                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|CategoryPost  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|CategoryPost  whereNotNull((string|Expression) $columns)
 * @method static int                           count(string $columns = '*')
 *
 * @property ProfileContract|null $deleter
 *
 * @method static \Modules\Blog\Database\Factories\CategoryPostFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class CategoryPost extends BasePivot
{
    protected $fillable = ['category_id', 'post_id'];
}
