<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Reaction;
use Modules\Xot\Actions\Cast\SafeStringCastAction;

/**
 * Facade unica per logiche estratte dal modello Comment (PHPMD coupling/complexity).
 */
class CommentModelSupport
{
    public static function onSaving(Comment $comment): void
    {
        CommentSavingPipeline::handle($comment);
    }

    public static function findReaction(Comment $comment, string $reaction, ?CanComment $commentator = null): ?Reaction
    {
        return CommentReactionHelper::findReaction($comment, $reaction, $commentator);
    }

    /** @return list<array{reaction: string, count: int}> */
    public static function reactionCounts(Comment $comment): array
    {
        return CommentReactionHelper::reactionCounts($comment);
    }

    public static function react(Comment $comment, string $reaction, ?CanComment $commentator = null): void
    {
        CommentReactionHelper::react($comment, $reaction, $commentator);
    }

    public static function deleteReaction(Comment $comment, string $reaction, ?CanComment $commentator = null): void
    {
        CommentReactionHelper::deleteReaction($comment, $reaction, $commentator);
    }

    /** @return Collection<int, CanComment> */
    public static function participatingCommentators(Comment $comment): Collection
    {
        return CommentParticipatingCommentatorsResolver::resolve($comment);
    }

    public static function commentableName(Comment $comment): string
    {
        $commentable = $comment->commentable;
        if (is_object($commentable) && method_exists($commentable, 'commentableName')) {
            return SafeStringCastAction::cast($commentable->commentableName());
        }

        return '';
    }

    public static function commentUrl(Comment $comment): string
    {
        $top = $comment->topLevel();
        $commentable = $top->commentable;
        $base = '';
        if (is_object($commentable) && method_exists($commentable, 'commentUrl')) {
            $base = SafeStringCastAction::cast($commentable->commentUrl());
        }

        return $base."#comment-{$comment->id}";
    }

    public static function approve(Comment $comment): void
    {
        CommentConfigActions::approveCommentAction()->execute($comment);
    }

    public static function reject(Comment $comment): void
    {
        CommentConfigActions::rejectCommentAction()->execute($comment);
    }

    public static function approveUrl(Comment $comment): string
    {
        return URL::signedRoute('comment::comment.approve', $comment, now()->addWeek());
    }

    public static function rejectUrl(Comment $comment): string
    {
        return URL::signedRoute('comment::comment.reject', $comment, now()->addWeek());
    }

    public static function shouldBeAutomaticallyApproved(Comment $comment): bool
    {
        if ((bool) config('comments.automatically_approve_all_comments', false)) {
            return true;
        }

        $commentator = $comment->commentator;
        if (! $commentator instanceof CanComment) {
            return false;
        }

        return self::approvingUsers($comment)->contains(
            static fn (CanComment $user): bool => $user->getMorphClass() === $commentator->getMorphClass()
                && $user->getKey() === $commentator->getKey(),
        );
    }

    /** @return Collection<int, CanComment> */
    public static function approvingUsers(Comment $comment): Collection
    {
        return CommentApprovingUsersResolver::resolve($comment);
    }

    /** @return Collection<int, CanComment> */
    public static function mentionees(Comment $comment): Collection
    {
        return CommentMentioneesResolver::resolve($comment);
    }
}
