<?php

declare(strict_types=1);

namespace Modules\Comment\Tests\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Models\CommentNotificationSubscription;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Datas\CommentatorProperties;

/**
 * Stub CanComment per test unitari — evita Mockery (PHPStan argument.type).
 */
final class ParityCommentatorStub implements CanComment
{
    use Notifiable;

    public function __construct(private int|string $id) {}

    public function getKey(): int|string
    {
        return $this->id;
    }

    public function getMorphClass(): string
    {
        return 'parity-user';
    }

    public function commentatorProperties(): CommentatorProperties
    {
        return CommentatorProperties::email('parity@example.com')->name('Parity User');
    }

    public function subscribeToCommentNotifications(Model $hasComments, NotificationSubscriptionType $subscriptionType): self
    {
        CommentNotificationSubscription::query()->updateOrCreate([
            'commentable_type' => $hasComments->getMorphClass(),
            'commentable_id' => $hasComments->getKey(),
            'subscriber_type' => $this->getMorphClass(),
            'subscriber_id' => $this->getKey(),
        ], [
            'type' => $subscriptionType->value,
        ]);

        return $this;
    }

    public function unsubscribeFromCommentNotifications(Model $hasComments): self
    {
        return $this->subscribeToCommentNotifications($hasComments, NotificationSubscriptionType::None);
    }

    public function unsubscribeFromAllCommentNotifications(): self
    {
        CommentNotificationSubscription::query()
            ->where('subscriber_type', $this->getMorphClass())
            ->where('subscriber_id', $this->getKey())
            ->delete();

        return $this;
    }

    public function notificationSubscriptionType(Model $hasComment): ?NotificationSubscriptionType
    {
        $subscription = CommentNotificationSubscription::query()
            ->where('commentable_type', $hasComment->getMorphClass())
            ->where('commentable_id', $hasComment->getKey())
            ->where('subscriber_type', $this->getMorphClass())
            ->where('subscriber_id', $this->getKey())
            ->first();

        if (! $subscription instanceof CommentNotificationSubscription) {
            return null;
        }

        return $subscription->type;
    }
}
