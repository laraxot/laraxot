<?php

declare(strict_types=1);

namespace Modules\Comment\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\URL;
use Modules\Comment\Actions\ResolveGravatarUrlAction;
use Modules\Comment\Datas\CommentatorProperties;
use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Models\CommentNotificationSubscription;
use Modules\Xot\Contracts\ProfileContract;

/**
 * CanComment senza RoutesNotifications — BaseUser usa già Notifiable.
 */
trait InteractsWithComments
{
    /** @return MorphMany<CommentNotificationSubscription, $this> */
    public function subscriberNotificationSubscriptions(): MorphMany
    {
        return $this->morphMany(CommentNotificationSubscription::class, 'subscriber');
    }

    public function commentatorProperties(): CommentatorProperties
    {
        // Caller: commentatorProperties() da comment-item FO + Filament comment widget.
        // CMT-022: avatar del profilo (media o Gravatar) — supera D-03 su richiesta esplicita dell'utente.
        $email = is_string($this->email) ? $this->email : '';
        $config = CommentConfigData::make();
        $nameField = (string) ($config->models['name'] ?? 'name');
        $avatarField = (string) ($config->models['avatar'] ?? 'avatar');
        $name = is_string($this->{$nameField} ?? null) ? $this->{$nameField} : '';
        $profile = $this->getRelationValue('profile');
        $avatar = $profile instanceof ProfileContract ? $profile->getAvatarUrl() : ($this->{$avatarField} ?? null);
        $avatarUrl = is_string($avatar) && $avatar !== '' ? $avatar : '';
        if ($avatarUrl === '' && $email !== '') {
            $avatarUrl = app(ResolveGravatarUrlAction::class)->execute($email);
        }

        return CommentatorProperties::email($email)
            ->name($name)
            ->avatar($avatarUrl);
    }

    public function subscribeToCommentNotifications(
        Model $hasComments,
        NotificationSubscriptionType $subscriptionType
    ): self {
        $this->subscriberNotificationSubscriptions()->updateOrCreate([
            'commentable_type' => $hasComments->getMorphClass(),
            'commentable_id' => $hasComments->getKey(),
        ], [
            'type' => $subscriptionType->value,
        ]);

        return $this;
    }

    public function unsubscribeFromCommentNotifications(Model $hasComments): self
    {
        $this->subscribeToCommentNotifications($hasComments, NotificationSubscriptionType::None);

        return $this;
    }

    public function unsubscribeFromCommentNotificationsUrl(Model $hasComments): ?string
    {
        $notifySub = $this->subscriberNotificationSubscriptions()
            ->where('commentable_type', $hasComments->getMorphClass())
            ->where('commentable_id', $hasComments->getKey())
            ->first();

        if (! $notifySub instanceof CommentNotificationSubscription) {
            return null;
        }

        return URL::signedRoute(
            'comment::notifications.unsubscribe',
            [$notifySub],
            now()->addWeek(),
        );
    }

    public function unsubscribeFromAllCommentNotifications(): self
    {
        $this->subscriberNotificationSubscriptions()->delete();

        return $this;
    }

    public function unsubscribeFromAllCommentNotificationsUrl(): string
    {
        return URL::signedRoute(
            'comment::notifications.unsubscribeAll',
            [static::class, $this->getKey()],
            now()->addWeek(),
        );
    }

    public function notificationSubscriptionType(Model $hasComments): ?NotificationSubscriptionType
    {
        $subscription = $this
            ->subscriberNotificationSubscriptions()
            ->where('commentable_type', $hasComments->getMorphClass())
            ->where('commentable_id', $hasComments->getKey())
            ->first();

        if (! $subscription instanceof CommentNotificationSubscription) {
            return null;
        }

        $type = $subscription->type;

        if ($type instanceof NotificationSubscriptionType) {
            return $type;
        }

        if (is_string($type) && $type !== '') {
            return NotificationSubscriptionType::from($type);
        }

        return null;
    }
}
