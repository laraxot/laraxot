<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Modules\Blog\Database\Factories\UpvoteDownvoteFactory;
use Modules\Media\Models\Media;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
 * Modules\Blog\Models\UpvoteDownvote.
 *
 * @property int         $id
 * @property int         $is_upvote
 * @property int         $post_id
 * @property int         $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static UpvoteDownvoteFactory  factory($count = null, $state = [])
 * @method static Builder|UpvoteDownvote newModelQuery()
 * @method static Builder|UpvoteDownvote newQuery()
 * @method static Builder|UpvoteDownvote onlyTrashed()
 * @method static Builder|UpvoteDownvote query()
 * @method static Builder|UpvoteDownvote whereCreatedAt($value)
 * @method static Builder|UpvoteDownvote whereId($value)
 * @method static Builder|UpvoteDownvote whereIsUpvote($value)
 * @method static Builder|UpvoteDownvote wherePostId($value)
 * @method static Builder|UpvoteDownvote whereUpdatedAt($value)
 * @method static Builder|UpvoteDownvote whereUserId($value)
 * @method static Builder|UpvoteDownvote withTrashed()
 * @method static Builder|UpvoteDownvote withoutTrashed()
 *
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder|UpvoteDownvote whereCreatedBy($value)
 * @method static Builder|UpvoteDownvote whereDeletedAt($value)
 * @method static Builder|UpvoteDownvote whereDeletedBy($value)
 * @method static Builder|UpvoteDownvote whereUpdatedBy($value)
 *
 * @property ProfileContract|null        $creator
 * @property ProfileContract|null        $updater
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 *
 * @method static UpvoteDownvote|null             first()
 * @method static Collection<int, UpvoteDownvote> get()
 * @method static UpvoteDownvote                  create(array<string, mixed> $attributes = [])
 * @method static UpvoteDownvote                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|UpvoteDownvote  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|UpvoteDownvote  whereNotNull((string|Expression) $columns)
 * @method static int                             count(string $columns = '*')
 *
 * @property ProfileContract|null $deleter
 *
 * @mixin \Eloquent
 */
class UpvoteDownvote extends BaseModel
{
    protected $fillable = ['is_upvote', 'post_id', 'user_id'];
}
