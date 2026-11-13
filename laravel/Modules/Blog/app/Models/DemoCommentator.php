<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Modules\Comment\Models\Concerns\InteractsWithComments;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\User\Models\User;

/**
 * Demo-only commentator adapter for the shared User table.
 *
 * User remains an upstream authentication model and does not need to depend on
 * Comment. The adapter adds the consumer-side comment contract while keeping
 * the existing User morph identity for persisted comment records.
 */
final class DemoCommentator extends User implements CanComment
{
    use InteractsWithComments;

    public static function fromUser(User $user): self
    {
        $commentator = new self;
        $commentator->setRawAttributes($user->getAttributes(), true);
        $commentator->exists = true;
        $commentator->setConnection($user->getConnectionName());

        return $commentator;
    }

    public function getKey(): mixed
    {
        return parent::getKey();
    }

    public function getMorphClass(): string
    {
        return User::class;
    }

    public function notify(mixed $instance): void
    {
        parent::notify($instance);
    }
}
