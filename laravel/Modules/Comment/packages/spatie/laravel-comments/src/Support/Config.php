<?php

namespace Spatie\Comments\Support;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Spatie\Comments\Actions\ApproveCommentAction;
use Spatie\Comments\Actions\ProcessCommentAction;
use Spatie\Comments\Actions\RejectCommentAction;
use Spatie\Comments\Actions\ResolveMentionsAutocompleteAction;
use Spatie\Comments\Actions\SendNotificationsForApprovedCommentAction;
use Spatie\Comments\Actions\SendNotificationsForPendingCommentAction;
use Spatie\Comments\CommentTransformers\CommentTransformer;
use Spatie\Comments\Models\Comment;
use Spatie\Comments\Models\CommentNotificationSubscription;
use Spatie\Comments\Models\Concerns\Interfaces\CanComment;
use Spatie\Comments\Models\Reaction;

class Config
{
    public static function allowedReactions(): array
    {
        return config('comments.allowed_reactions', []);
    }

    public static function allowAnonymousComments(): bool
    {
        return config('comments.allow_anonymous_comments', false);
    }

    public static function automaticallyApproveAllComments(): bool
    {
        return config('comments.automatically_approve_all_comments');
    }

    /** @return class-string<CanComment>|null */
    public static function commentatorModelClass(): ?string
    {
        return config('comments.models.commentator', null);
    }

    public static function commentatorModelNameField(): string
    {
        return config('comments.models.name', 'name');
    }

    public static function commentatorModelAvatarField(): string
    {
        return config('comments.models.avatar', 'avatar');
    }

    /** @return class-string<Comment> */
    public static function commentModelClass(): string
    {
        return config('comments.models.comment', Comment::class);
    }

    /** @return class-string<CommentNotificationSubscription> */
    public static function commentNotificationSubscriptionModelClass(): string
    {
        return config('comments.models.comment_notification_subscription', CommentNotificationSubscription::class);
    }

    public static function gravatarDefaultImage(): string
    {
        return config('comments.gravatar.default_image', 'mp');
    }

    /** @return class-string<Reaction> */
    public static function reactionModelClass(): string
    {
        return config('comments.models.reaction', Reaction::class);
    }

    public static function processCommentAction(): ProcessCommentAction
    {
        $actionClass = config('comments.actions.process_comment', ProcessCommentAction::class);

        return app($actionClass);
    }

    public static function approveCommentAction(): ApproveCommentAction
    {
        $actionClass = config('comments.actions.approve_comment', ApproveCommentAction::class);

        return app($actionClass);
    }

    public static function rejectCommentAction(): RejectCommentAction
    {
        $actionClass = config('comments.actions.reject_comment', RejectCommentAction::class);

        return app($actionClass);
    }

    public static function sendNotificationsForPendingCommentAction(): SendNotificationsForPendingCommentAction
    {
        $actionClass = config('comments.actions.send_notifications_for_pending_comment', SendNotificationsForPendingCommentAction::class);

        return app($actionClass);
    }

    public static function sendNotificationsForApprovedCommentAction(): SendNotificationsForApprovedCommentAction
    {
        $actionClass = config('comments.actions.send_notifications_for_approved_comment', SendNotificationsForApprovedCommentAction::class);

        return app($actionClass);
    }

    public static function resolveMentionsAutocompleteAction(): ResolveMentionsAutocompleteAction
    {
        $actionClass = config('comments.actions.resolve_mentions_autocomplete', ResolveMentionsAutocompleteAction::class);

        return app($actionClass);
    }

    /** @return Collection<CommentTransformer> */
    public static function commentTransformers(): Collection
    {
        return collect(config('comments.comment_transformers'))
            ->map(fn (string $commentProcessorClass) => app($commentProcessorClass));
    }

    public static function commentSanitizer(): CommentSanitizer
    {
        $className = config('comments.comment_sanitizer', CommentSanitizer::class);

        return app($className);
    }

    public static function allowedAttributes(): array
    {
        return config('comments.allowed_attributes', []);
    }

    public static function notificationsEnabled(): bool
    {
        return config('comments.notifications.enabled', true);
    }

    public static function pendingCommentNotification(Comment $comment): Notification
    {
        $notificationClass = config('comments.notifications.notifications.pending_comment');

        return app($notificationClass, ['comment' => $comment]);
    }

    public static function approvedCommentNotification(Comment $comment, CanComment $commentator): Notification
    {
        $notificationClass = config('comments.notifications.notifications.approved_comment');

        return app($notificationClass, compact('comment', 'commentator'));
    }

    public static function notificationMailFromAddress(): string
    {
        return config('comments.notifications.mail.from.address', config('mail.from.address', ''));
    }

    public static function notificationMailFromName(): string
    {
        return config('comments.notifications.mail.from.name', config('mail.from.name', ''));
    }

    public static function mentionsEnabled(): bool
    {
        return config('comments.mentions.enabled');
    }
}
