<?php

declare(strict_types=1);

namespace Modules\Comment\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Comment\Database\Factories\CommentNotificationSubscriptionFactory;
use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Xot\Models\Traits\HasXotFactory;

/**
 * @property int $id
 * @property string $commentable_type
 * @property int|string $commentable_id
 * @property string $subscriber_type
 * @property int|string $subscriber_id
 * @property NotificationSubscriptionType $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Model|null $commentable
 * @property-read Model|null $subscriber
 *
 * @method static CommentNotificationSubscriptionFactory factory($count = null, $state = [])
 * @method static Builder<static> newModelQuery()
 * @method static Builder<static> newQuery()
 * @method static Builder<static> query()
 */
class CommentNotificationSubscription extends Model
{
    /** @use HasXotFactory<CommentNotificationSubscriptionFactory> */
    use HasXotFactory;

    protected $connection = 'comment';

    protected $guarded = [];

    protected $casts = [
        'type' => NotificationSubscriptionType::class,
    ];

    /** @return MorphTo<Model, $this> */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return MorphTo<Model, $this> */
    public function subscriber(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory(): CommentNotificationSubscriptionFactory
    {
        return CommentNotificationSubscriptionFactory::new();
    }
}
