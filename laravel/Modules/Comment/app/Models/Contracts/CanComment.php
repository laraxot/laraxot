<?php

declare(strict_types=1);

namespace Modules\Comment\Models\Contracts;

use Illuminate\Database\Eloquent\Model;
use Modules\Comment\Datas\CommentatorProperties;
use Modules\Comment\Enums\NotificationSubscriptionType;

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

    /**
     * Chiave primaria — delega a `Model::getKey()`.
     *
     * Il tipo nativo e' obbligatorio: i modelli che implementano questo contratto
     * (Predict\\User, Blog\\DemoCommentator, CommentatorUser dei test) lo re-dichiarano
     * con lo stesso tipo per la covariance PHP.
     */
    public function getKey(): mixed;

    /**
     * Morph class — delega a `Model::getMorphClass()`.
     */
    public function getMorphClass(): string;

    /**
     * Notifica — compatibile con `Notifiable::notify($instance)` del framework.
     */
    public function notify(mixed $instance): void;

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
