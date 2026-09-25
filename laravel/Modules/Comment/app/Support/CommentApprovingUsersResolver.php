<?php

declare(strict_types=1);

namespace Modules\Comment\Support;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Modules\Comment\Exceptions\CannotSendPendingCommentNotification;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Notifications\PendingCommentNotification;

class CommentApprovingUsersResolver
{
    /** @return Collection<int, CanComment> */
    public static function resolve(Comment $comment): Collection
    {
        $sendToClosure = PendingCommentNotification::$sendTo;

        if (! is_callable($sendToClosure)) {
            return self::emptyCanCommentCollection();
        }

        $users = once(fn () => $sendToClosure($comment));

        return self::normalizeUsers($users);
    }

    /** @return Collection<int, CanComment> */
    private static function normalizeUsers(mixed $users): Collection
    {
        if (is_iterable($users)) {
            return self::collectionFromIterable($users);
        }

        if (is_object($users)) {
            return self::collectionFromObject($users);
        }

        throw CannotSendPendingCommentNotification::doesNotImplementNotifiable();
    }

    /** @param iterable<mixed> $users
     * @return Collection<int, CanComment>
     */
    private static function collectionFromIterable(iterable $users): Collection
    {
        /** @var Collection<int, CanComment> $collection */
        $collection = new Collection;
        foreach ($users as $user) {
            if (! is_object($user) || ! self::isNotifiable($user)) {
                throw CannotSendPendingCommentNotification::doesNotImplementNotifiable();
            }
            if ($user instanceof CanComment) {
                $collection->push($user);
            }
        }

        return $collection;
    }

    /** @return Collection<int, CanComment> */
    private static function collectionFromObject(object $users): Collection
    {
        if (! self::isNotifiable($users)) {
            throw CannotSendPendingCommentNotification::doesNotImplementNotifiable();
        }

        if ($users instanceof CanComment) {
            return new Collection([$users]);
        }

        return self::emptyCanCommentCollection();
    }

    /** @return Collection<int, CanComment> */
    private static function emptyCanCommentCollection(): Collection
    {
        return new Collection;
    }

    private static function isNotifiable(object $object): bool
    {
        $traitsUsed = trait_uses_recursive($object);

        return in_array(Notifiable::class, $traitsUsed, true);
    }
}
