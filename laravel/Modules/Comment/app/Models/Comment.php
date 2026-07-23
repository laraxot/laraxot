<?php

declare(strict_types=1);

namespace Modules\Comment\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Modules\Comment\Actions\Comment\ApproveCommentAction;
use Modules\Comment\Actions\Comment\PrepareCommentForSavingAction;
use Modules\Comment\Actions\Comment\RejectCommentAction;
use Modules\Comment\Actions\Comment\ResolveApprovingUsersAction;
use Modules\Comment\Actions\Comment\ResolveMentioneesAction;
use Modules\Comment\Actions\Comment\ResolveParticipatingCommentatorsAction;
use Modules\Comment\Actions\Reaction\DeleteReactionAction;
use Modules\Comment\Actions\Reaction\FindReactionAction;
use Modules\Comment\Actions\Reaction\GetReactionCountsAction;
use Modules\Comment\Actions\Reaction\ReactToCommentAction;
use Modules\Comment\Database\Factories\CommentFactory;
use Modules\Comment\Datas\CommentatorProperties;
use Modules\Comment\Models\Concerns\HasComments;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Contracts\Commentable;
use Modules\Comment\Models\Contracts\SupportsCommentNotifications;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Models\Traits\HasXotFactory;

/**
 * @property int $id
 * @property string $original_text
 * @property string|null $text
 * @property string|null $commentator_type
 * @property int|string|null $commentator_id
 * @property string|null $commentable_type
 * @property int|string|null $commentable_id
 * @property int|null $parent_id
 * @property Carbon|null $approved_at
 * @property array<mixed, mixed>|null $extra
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Model|null $commentable
 * @property-read Model|null $commentator
 *
 * @method static CommentFactory factory($count = null, $state = [])
 * @method static Builder<Comment> newModelQuery()
 * @method static Builder<Comment> newQuery()
 * @method static Builder<Comment> query()
 * @method static Builder<Comment> approved()
 * @method static Builder<Comment> pending()
 */
class Comment extends Model implements Commentable, SupportsCommentNotifications
{
    use HasComments;

    /** @phpstan-use HasXotFactory<CommentFactory> */
    use HasXotFactory;

    protected $connection = 'comment';

    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'extra' => 'array',
            'approved_at' => 'datetime',
        ];
    }

    public static function booted(): void
    {
        static::saving(function (Comment $comment): void {
            app(PrepareCommentForSavingAction::class)->execute($comment);
        });
    }

    /** @return MorphTo<Model, $this> */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return MorphTo<Model, $this> */
    public function commentator(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<Comment, $this> */
    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /** @return HasMany<Comment, $this> */
    public function nestedComments(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function topLevel(): self
    {
        if ($this->isTopLevel()) {
            return $this;
        }

        $commentable = $this->commentable;

        if ($commentable instanceof Comment) {
            return $commentable->topLevel();
        }

        return $this;
    }

    public function isTopLevel(): bool
    {
        return $this->parent_id === null;
    }

    /** @param Builder<Comment> $builder */
    public function scopeTopLevel(Builder $builder): void
    {
        $builder->whereNull('parent_id');
    }

    /** @param Builder<Comment> $query */
    public function scopePending(Builder $query): void
    {
        $query->whereNull('approved_at');
    }

    /** @param Builder<Comment> $query */
    public function scopeApproved(Builder $query): void
    {
        $query->whereNotNull('approved_at');
    }

    /** @return HasMany<Reaction, $this> */
    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    public function findReaction(string $reaction, ?CanComment $commentator = null): ?Reaction
    {
        return app(FindReactionAction::class)->execute($this, $reaction, $commentator);
    }

    /** @return list<array{reaction: string, count: int}> */
    public function reactionCounts(): array
    {
        return app(GetReactionCountsAction::class)->execute($this);
    }

    public function react(string $reaction, ?CanComment $commentator = null): self
    {
        app(ReactToCommentAction::class)->execute($this, $reaction, $commentator);

        return $this;
    }

    public function deleteReaction(string $reaction, ?CanComment $commentator = null): self
    {
        app(DeleteReactionAction::class)->execute($this, $reaction, $commentator);

        return $this;
    }

    /**
     * @return Collection<int, CanComment>
     */
    public function participatingCommentators(): Collection
    {
        return app(ResolveParticipatingCommentatorsAction::class)->execute($this);
    }

    public function commentableName(): string
    {
        $commentable = $this->commentable;
        if (is_object($commentable) && method_exists($commentable, 'commentableName')) {
            return SafeStringCastAction::cast($commentable->commentableName());
        }

        return '';
    }

    public function commentUrl(): string
    {
        $top = $this->topLevel();
        $commentable = $top->commentable;
        $base = '';
        if (is_object($commentable) && method_exists($commentable, 'commentUrl')) {
            $base = SafeStringCastAction::cast($commentable->commentUrl());
        }

        return $base."#comment-{$this->id}";
    }

    public function commentatorProperties(): ?CommentatorProperties
    {
        $commentator = $this->commentator;

        if (! $commentator instanceof CanComment) {
            return null;
        }

        return $commentator->commentatorProperties();
    }

    public function madeBy(?CanComment $commentator): bool
    {
        $ownCommentator = $this->commentator;
        if (! $ownCommentator instanceof CanComment || ! $commentator instanceof CanComment) {
            return false;
        }

        return $commentator->getMorphClass() === $ownCommentator->getMorphClass()
            && $commentator->getKey() === $ownCommentator->getKey();
    }

    public function wasMadeByDeletedCommentator(): bool
    {
        if ($this->wasMadeAnonymously()) {
            return false;
        }

        return $this->commentator === null;
    }

    public function wasMadeAnonymously(): bool
    {
        return $this->commentator_id === null;
    }

    public function approve(): self
    {
        app(ApproveCommentAction::class)->execute($this);

        return $this;
    }

    public function reject(): self
    {
        app(RejectCommentAction::class)->execute($this);

        return $this;
    }

    public function isPending(): bool
    {
        return ! $this->isApproved();
    }

    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    public function approveUrl(): string
    {
        return URL::signedRoute('comment::comment.approve', $this, now()->addWeek());
    }

    public function rejectUrl(): string
    {
        return URL::signedRoute('comment::comment.reject', $this, now()->addWeek());
    }

    public function shouldBeAutomaticallyApproved(): bool
    {
        if ((bool) config('comments.automatically_approve_all_comments', false)) {
            return true;
        }

        $commentator = $this->commentator;
        if (! $commentator instanceof CanComment) {
            return false;
        }

        return $this->getApprovingUsers()->contains(
            static fn (CanComment $user): bool => $user->getMorphClass() === $commentator->getMorphClass()
                && $user->getKey() === $commentator->getKey(),
        );
    }

    /** @return Collection<int, CanComment> */
    public function getApprovingUsers(): Collection
    {
        return app(ResolveApprovingUsersAction::class)->execute($this);
    }

    /** @return Collection<int, CanComment> */
    public function getMentionees(): Collection
    {
        return app(ResolveMentioneesAction::class)->execute($this);
    }

    protected static function newFactory(): CommentFactory
    {
        return CommentFactory::new();
    }
}
