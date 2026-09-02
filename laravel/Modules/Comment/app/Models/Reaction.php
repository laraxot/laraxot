<?php

declare(strict_types=1);

namespace Modules\Comment\Models;

use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Comment\Database\Factories\ReactionFactory;
use Modules\Comment\Models\Collections\ReactionCollection;
use Modules\Comment\Models\Comment;
use Modules\Xot\Contracts\ProfileContract;

/**
 * @property int $id
 * @property string|null $commentator_type
 * @property int|null $commentator_id
 * @property int $comment_id
 * @property string $reaction
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 *
 * @method static ReactionFactory factory($count = null, $state = [])
 * @method static Builder|Reaction newModelQuery()
 * @method static Builder|Reaction newQuery()
 * @method static Builder|Reaction query()
 * @method static Builder|Reaction whereCommentId($value)
 * @method static Builder|Reaction whereCommentatorId($value)
 * @method static Builder|Reaction whereCommentatorType($value)
 * @method static Builder|Reaction whereCreatedAt($value)
 * @method static Builder|Reaction whereCreatedBy($value)
 * @method static Builder|Reaction whereDeletedAt($value)
 * @method static Builder|Reaction whereDeletedBy($value)
 * @method static Builder|Reaction whereId($value)
 * @method static Builder|Reaction whereReaction($value)
 * @method static Builder|Reaction whereUpdatedAt($value)
 * @method static Builder|Reaction whereUpdatedBy($value)
 *
 * @property-read Comment|null $comment
 * @property-read Model|\Eloquent $commentator
 *
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection<int, static> get($columns = ['*'])
 * @method static Reaction|null first()
 * @method static Reaction create(array<string, mixed> $attributes = [])
 * @method static Reaction firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Reaction where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Reaction whereNotNull((string|Expression) $columns)
 * @method static int count(string $columns = '*')
 *
 * @mixin \Eloquent
 */
class Reaction extends BaseModel
{
    /** @var class-string<ReactionCollection> */
    protected static string $collectionClass = ReactionCollection::class;

    protected $guarded = [];

    /** @return BelongsTo<Comment, $this> */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    /** @return MorphTo<Model, $this> */
    public function commentator(): MorphTo
    {
        return $this->morphTo();
    }

}
