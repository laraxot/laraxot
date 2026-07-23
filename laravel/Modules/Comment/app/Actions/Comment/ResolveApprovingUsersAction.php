<?php

declare(strict_types=1);

namespace Modules\Comment\Actions\Comment;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Modules\Comment\Exceptions\CannotSendPendingCommentNotification;
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Notifications\PendingCommentNotification;
use Spatie\QueueableAction\QueueableAction;

class ResolveApprovingUsersAction
{
    use QueueableAction;

    /** @return Collection<int, CanComment> */
    public function execute(Comment $comment): Collection
    {
        $sendToClosure = PendingCommentNotification::$sendTo;

        if (! is_callable($sendToClosure)) {
            return $this->emptyCanCommentCollection();
        }

        $users = once(fn () => $sendToClosure($comment));

        return $this->normalizeUsers($users);
    }

    /** @return Collection<int, CanComment> */
    private function normalizeUsers(mixed $users): Collection
    {
        if (is_iterable($users)) {
            return $this->collectionFromIterable($users);
        }

        if (is_object($users)) {
            return $this->collectionFromObject($users);
        }

        throw CannotSendPendingCommentNotification::doesNotImplementNotifiable();
    }

    /** @param iterable<mixed> $users
     * @return Collection<int, CanComment>
     */
    private function collectionFromIterable(iterable $users): Collection
    {
        /** @var Collection<int, CanComment> $collection */
        $collection = new Collection();
        foreach ($users as $user) {
            if (! is_object($user) || ! $this->isNotifiable($user)) {
                throw CannotSendPendingCommentNotification::doesNotImplementNotifiable();
            }
            if ($user instanceof CanComment) {
                $collection->push($user);
            }
        }

        return $collection;
    }

    /** @return Collection<int, CanComment> */
    private function collectionFromObject(object $users): Collection
    {
        if (! $this->isNotifiable($users)) {
            throw CannotSendPendingCommentNotification::doesNotImplementNotifiable();
        }

        if ($users instanceof CanComment) {
            return new Collection([$users]);
        }

        return $this->emptyCanCommentCollection();
    }

    /** @return Collection<int, CanComment> */
    private function emptyCanCommentCollection(): Collection
    {
        return new Collection();
    }

    private function isNotifiable(object $object): bool
    {
        $traitsUsed = trait_uses_recursive($object);

        return in_array(Notifiable::class, $traitsUsed, true);
    }
}
