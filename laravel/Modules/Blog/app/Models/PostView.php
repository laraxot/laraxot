<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Modules\Blog\Database\Factories\PostViewFactory;
use Modules\Media\Models\Media;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
 * Modules\Blog\Models\PostView.
 *
 * @property int         $id
 * @property string      $ip_address
 * @property string      $user_agent
 * @property int         $post_id
 * @property int         $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static PostViewFactory  factory($count = null, $state = [])
 * @method static Builder|PostView newModelQuery()
 * @method static Builder|PostView newQuery()
 * @method static Builder|PostView onlyTrashed()
 * @method static Builder|PostView query()
 * @method static Builder|PostView whereCreatedAt($value)
 * @method static Builder|PostView whereId($value)
 * @method static Builder|PostView whereIpAddress($value)
 * @method static Builder|PostView wherePostId($value)
 * @method static Builder|PostView whereUpdatedAt($value)
 * @method static Builder|PostView whereUserAgent($value)
 * @method static Builder|PostView whereUserId($value)
 * @method static Builder|PostView withTrashed()
 * @method static Builder|PostView withoutTrashed()
 *
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder|PostView whereCreatedBy($value)
 * @method static Builder|PostView whereDeletedAt($value)
 * @method static Builder|PostView whereDeletedBy($value)
 * @method static Builder|PostView whereUpdatedBy($value)
 *
 * @property ProfileContract|null        $creator
 * @property ProfileContract|null        $updater
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 *
 * @method static PostView|null             first()
 * @method static Collection<int, PostView> get()
 * @method static PostView                  create(array<string, mixed> $attributes = [])
 * @method static PostView                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|PostView  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|PostView  whereNotNull((string|Expression) $columns)
 * @method static int                       count(string $columns = '*')
 *
 * @property ProfileContract|null $deleter
 *
 * @mixin \Eloquent
 */
class PostView extends BaseModel
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'post_id',
        'user_id',
    ];
}
