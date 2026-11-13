<?php

declare(strict_types=1);

namespace Modules\Comment\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Comment\Database\Factories\CommentNotificationSubscriptionFactory;
use Modules\Comment\Enums\NotificationSubscriptionType;

/**
 * @property int $id
 * @property string $commentable_type
 * @property int|string $commentable_id
 * @property string $subscriber_type
 * @property int|string $subscriber_id
 * @property NotificationSubscriptionType $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read BaseMorphPivot|null $commentable
 * @property-read BaseMorphPivot|null $subscriber
 *
 * @method static Builder<static> newModelQuery()
 * @method static Builder<static> newQuery()
 * @method static Builder<static> query()
 */
class CommentNotificationSubscription extends BaseMorphPivot
{
    /** @var list<string> */
    protected $fillable = [
        'id',
        'commentable_type',
        'commentable_id',
        'subscriber_type',
        'subscriber_id',
        'type',
    ];

    protected $casts = [
        'type' => NotificationSubscriptionType::class,
    ];

    /**
     * @return MorphTo<Model, $this>
     *
     * Polymorphic: can relate to any Eloquent model.
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo<Model, $this>
     *
     * Polymorphic: can relate to any Eloquent model.
     */
    public function subscriber(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory(): CommentNotificationSubscriptionFactory
    {
        return CommentNotificationSubscriptionFactory::new();
    }
}
