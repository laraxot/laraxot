<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Blog\Database\Factories\CommentFactory;
use Modules\Media\Models\Media;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
 * Modules\Blog\Models\Comment.
 *
 * @property int                      $id
 * @property string                   $comment
 * @property int                      $post_id
 * @property int                      $user_id
 * @property Carbon|null              $created_at
 * @property Carbon|null              $updated_at
 * @property int|null                 $parent_id
 * @property Article|null             $article
 * @property Profile|null             $author
 * @property Collection<int, Comment> $childrens
 * @property int|null                 $childrens_count
 * @property Collection<int, Comment> $comments
 * @property int|null                 $comments_count
 * @property Comment|null             $parentComment
 * @property UserContract|null        $user
 *
 * @method static CommentFactory  factory($count = null, $state = [])
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment onlyTrashed()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereComment($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereParentId($value)
 * @method static Builder|Comment wherePostId($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @method static Builder|Comment withTrashed()
 * @method static Builder|Comment withoutTrashed()
 *
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder|Comment whereCreatedBy($value)
 * @method static Builder|Comment whereDeletedAt($value)
 * @method static Builder|Comment whereDeletedBy($value)
 * @method static Builder|Comment whereUpdatedBy($value)
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property string|null          $commentable_type
 * @property string|null          $commentable_id
 * @property string|null          $commentator_type
 * @property string|null          $commentator_id
 * @property string               $text
 * @property string|null          $extra
 * @property string|null          $approved_at
 * @property string               $original_text
 *
 * @method static Builder|Comment whereApprovedAt($value)
 * @method static Builder|Comment whereCommentableId($value)
 * @method static Builder|Comment whereCommentableType($value)
 * @method static Builder|Comment whereCommentatorId($value)
 * @method static Builder|Comment whereCommentatorType($value)
 * @method static Builder|Comment whereExtra($value)
 * @method static Builder|Comment whereOriginalText($value)
 * @method static Builder|Comment whereText($value)
 *
 * @property ProfileContract|null        $creator
 * @property ProfileContract|null        $updater
 * @property MediaCollection<int, Media> $media
 * @property int|null                    $media_count
 *
 * @method static Comment|null             first()
 * @method static Collection<int, Comment> get()
 * @method static Comment                  create(array<string, mixed> $attributes = [])
 * @method static Comment                  firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static Builder<static>|Comment  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder<static>|Comment  whereNotNull((string|Expression) $columns)
 * @method static int                      count(string $columns = '*')
 *
 * @property ProfileContract|null $deleter
 *
 * @mixin \Eloquent
 */
class Comment extends BaseModel
{
    protected $fillable = [
        'comment',
        'post_id',
        'user_id',
        'parent_id',
        'author_id',
        'article_id',
    ];

    /** @return BelongsTo<Model&UserContract, $this> */
    public function user(): BelongsTo
    {
        $userClass = XotData::make()->getUserClass();

        return $this->belongsTo($userClass);
    }

    /**
     * The comment that belong to the author.
     *
     * @return BelongsTo<Profile, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'user_id'); // ->withTrashed();
    }

    /**
     * The comment that belong to the article.
     *
     * @return BelongsTo<Article, $this>
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /** @return BelongsTo<Comment, $this> */
    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * The childrens of a comment(reply).
     *
     * @return HasMany<Comment, $this>
     */
    public function childrens(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /* -- to comment module
    public function comments(): HasMany
    {
        // return $this->hasMany(Comment::class, function ($query) {
        //     $query->whereNotNull('parent_id')->orderBy('created_at', 'desc');
        // });

        return $this->hasMany(self::class)
            ->whereNotNull('parent_id')
            ->orderBy('created_at', 'desc');
    }
    */
}
