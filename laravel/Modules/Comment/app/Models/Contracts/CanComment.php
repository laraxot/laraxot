<?php

declare(strict_types=1);

namespace Modules\Comment\Models\Contracts;

use Illuminate\Database\Eloquent\Model;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Datas\CommentatorProperties;

/**
 * Capacità commentator — owner **Comment** (dominio moderazione/FO).
 *
 * User (e altri moduli upstream) **implementano** questo contratto; Comment non dipende da User nei type hint.
 *
 * @see Modules/Comment/docs/wiki/concepts/can-comment-contract-owner.md
 */
interface CanComment
{
    public function commentatorProperties(): CommentatorProperties;

    /** @return int|string|null */
    public function getKey();

    /** @return string */
    public function getMorphClass();

    /**
     * @param  mixed  $instance
     * @return void
     */
    public function notify($instance);

    /**
     * @param  Model&Commentable  $hasComments
     */
    public function subscribeToCommentNotifications(
        Model $hasComments,
        NotificationSubscriptionType $subscriptionType,
    ): self;

    /**
     * @param  Model&Commentable  $hasComments
     */
    public function unsubscribeFromCommentNotifications(
        Model $hasComments,
    ): self;

    public function unsubscribeFromAllCommentNotifications(): self;

    /**
     * @param  Model&Commentable  $hasComments
     */
    public function notificationSubscriptionType(
        Model $hasComments,
    ): ?NotificationSubscriptionType;
}
