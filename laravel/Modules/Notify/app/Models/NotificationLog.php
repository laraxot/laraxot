<?php

declare(strict_types=1);

namespace Modules\Notify\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Media\Models\Media;
use Modules\Xot\Contracts\ProfileContract;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
 * @property-read ProfileContract|null $creator
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Model $notifiable
 * @property-read NotificationTemplate|null $template
 * @property-read ProfileContract|null $updater
<<<<<<< HEAD
=======
 * @property string|null $template_id
 * @property string|null $notifiable_type
 * @property string|null $notifiable_id
 * @property string|null $channel
 * @property string|null $status
 * @property string|null $status_message
 * @property array<string, mixed>|null $data
 * @property array<string, mixed>|null $metadata
 *
 * @method static Builder<static> where(string $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static static|null find(mixed $id, array<int, string>|string $columns = ['*'])
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property string $channels
 * @property Carbon $sent_at
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $deleter
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Model|\Eloquent $notifiable
 * @property-read NotificationTemplate|null $template
 * @property-read ProfileContract|null $updater
 *
 * @method static \Modules\Notify\Database\Factories\NotificationLogFactory factory($count = null, $state = [])
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 *
>>>>>>> a988596b (first)
 * @method static Builder<static>|NotificationLog forChannel(string $channel)
 * @method static Builder<static>|NotificationLog forNotifiable(\Illuminate\Database\Eloquent\Model $notifiable)
 * @method static Builder<static>|NotificationLog newModelQuery()
 * @method static Builder<static>|NotificationLog newQuery()
 * @method static Builder<static>|NotificationLog query()
<<<<<<< HEAD
<<<<<<< HEAD
 * @method static Builder<static>|NotificationLog withStatus(string $status)
=======
 * @method static Builder<static>|NotificationLog withStatus(string $status)
 *
>>>>>>> a988596b (first)
 * @property string $id
 * @property string|null $template_id
 * @property string $notifiable_type
 * @property string $notifiable_id
 * @property string $channel
 * @property string $status
 * @property string|null $status_message
 * @property array<array-key, mixed>|null $data
 * @property array<array-key, mixed>|null $metadata
 * @property string|null $tenant_id
 * @property Carbon|null $sent_at
 * @property Carbon|null $delivered_at
 * @property Carbon|null $failed_at
 * @property Carbon|null $opened_at
 * @property Carbon|null $clicked_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
<<<<<<< HEAD
=======
 *
>>>>>>> a988596b (first)
 * @method static Builder<static>|NotificationLog whereChannel($value)
 * @method static Builder<static>|NotificationLog whereClickedAt($value)
 * @method static Builder<static>|NotificationLog whereCreatedAt($value)
 * @method static Builder<static>|NotificationLog whereCreatedBy($value)
 * @method static Builder<static>|NotificationLog whereData($value)
 * @method static Builder<static>|NotificationLog whereDeliveredAt($value)
 * @method static Builder<static>|NotificationLog whereFailedAt($value)
 * @method static Builder<static>|NotificationLog whereId($value)
 * @method static Builder<static>|NotificationLog whereMetadata($value)
 * @method static Builder<static>|NotificationLog whereNotifiableId($value)
 * @method static Builder<static>|NotificationLog whereNotifiableType($value)
 * @method static Builder<static>|NotificationLog whereOpenedAt($value)
 * @method static Builder<static>|NotificationLog whereSentAt($value)
 * @method static Builder<static>|NotificationLog whereStatus($value)
 * @method static Builder<static>|NotificationLog whereStatusMessage($value)
 * @method static Builder<static>|NotificationLog whereTemplateId($value)
 * @method static Builder<static>|NotificationLog whereTenantId($value)
 * @method static Builder<static>|NotificationLog whereUpdatedAt($value)
 * @method static Builder<static>|NotificationLog whereUpdatedBy($value)
<<<<<<< HEAD
 * @property-read \Modules\User\Models\Profile|null $deleter
=======
 * @method static Builder<static>|NotificationLog whereChannels($value)
 * @method static Builder<static>|NotificationLog whereContent($value)
 * @method static Builder<static>|NotificationLog whereCreatedAt($value)
 * @method static Builder<static>|NotificationLog whereData($value)
 * @method static Builder<static>|NotificationLog whereError($value)
 * @method static Builder<static>|NotificationLog whereId($value)
 * @method static Builder<static>|NotificationLog whereNotifiableId($value)
 * @method static Builder<static>|NotificationLog whereNotifiableType($value)
 * @method static Builder<static>|NotificationLog whereSentAt($value)
 * @method static Builder<static>|NotificationLog whereStatus($value)
 * @method static Builder<static>|NotificationLog whereTitle($value)
 * @method static Builder<static>|NotificationLog whereUpdatedAt($value)
 * @method static Builder<static>|NotificationLog withStatus(string $status)
 *
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 *
>>>>>>> a988596b (first)
=======
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Model $notifiable
 * @property-read \Modules\Notify\Models\NotificationTemplate|null $template
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Notify\Database\Factories\NotificationLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationLog forChannel(string $channel)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationLog forNotifiable(\Illuminate\Database\Eloquent\Model $notifiable)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationLog withStatus(string $status)
>>>>>>> 98d0a12c (.)
=======
 * @property-read ProfileContract|null $creator
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Model $notifiable
 * @property-read NotificationTemplate|null $template
 * @property-read ProfileContract|null $updater
 *
 * @method static Builder<static>|NotificationLog forChannel(string $channel)
 * @method static Builder<static>|NotificationLog forNotifiable(\Illuminate\Database\Eloquent\Model $notifiable)
 * @method static Builder<static>|NotificationLog newModelQuery()
 * @method static Builder<static>|NotificationLog newQuery()
 * @method static Builder<static>|NotificationLog query()
 * @method static Builder<static>|NotificationLog withStatus(string $status)
 *
 * @property string $id
 * @property string|null $template_id
 * @property string $notifiable_type
 * @property string $notifiable_id
 * @property string $channel
 * @property string $status
 * @property string|null $status_message
 * @property array<array-key, mixed>|null $data
 * @property array<array-key, mixed>|null $metadata
 * @property string|null $tenant_id
 * @property Carbon|null $sent_at
 * @property Carbon|null $delivered_at
 * @property Carbon|null $failed_at
 * @property Carbon|null $opened_at
 * @property Carbon|null $clicked_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 *
 * @method static Builder<static>|NotificationLog whereChannel($value)
 * @method static Builder<static>|NotificationLog whereClickedAt($value)
 * @method static Builder<static>|NotificationLog whereCreatedAt($value)
 * @method static Builder<static>|NotificationLog whereCreatedBy($value)
 * @method static Builder<static>|NotificationLog whereData($value)
 * @method static Builder<static>|NotificationLog whereDeliveredAt($value)
 * @method static Builder<static>|NotificationLog whereFailedAt($value)
 * @method static Builder<static>|NotificationLog whereId($value)
 * @method static Builder<static>|NotificationLog whereMetadata($value)
 * @method static Builder<static>|NotificationLog whereNotifiableId($value)
 * @method static Builder<static>|NotificationLog whereNotifiableType($value)
 * @method static Builder<static>|NotificationLog whereOpenedAt($value)
 * @method static Builder<static>|NotificationLog whereSentAt($value)
 * @method static Builder<static>|NotificationLog whereStatus($value)
 * @method static Builder<static>|NotificationLog whereStatusMessage($value)
 * @method static Builder<static>|NotificationLog whereTemplateId($value)
 * @method static Builder<static>|NotificationLog whereTenantId($value)
 * @method static Builder<static>|NotificationLog whereUpdatedAt($value)
 * @method static Builder<static>|NotificationLog whereUpdatedBy($value)
 *
>>>>>>> a377e9e6 (.)
 * @mixin \Eloquent
 */
class NotificationLog extends BaseModel
{
    public const string STATUS_PENDING = 'pending';

    public const string STATUS_PROCESSING = 'processing';

    public const string STATUS_SENT = 'sent';

    public const string STATUS_DELIVERED = 'delivered';

    public const string STATUS_FAILED = 'failed';

    public const string STATUS_OPENED = 'opened';

    public const string STATUS_CLICKED = 'clicked';

    protected $table = 'notification_logs';

    protected $fillable = [
        'template_id',
        'notifiable_type',
        'notifiable_id',
        'channel',
        'status',
        'status_message',
        'data',
        'metadata',
        'sent_at',
        'delivered_at',
        'failed_at',
        'opened_at',
        'clicked_at',
<<<<<<< HEAD
<<<<<<< HEAD
        'tenant_id'];
=======
        'tenant_id',
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'tenant_id'];
>>>>>>> a988596b (first)

    /** @return MorphTo<Model, $this> */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<NotificationTemplate, $this> */
    public function template(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForChannel(Builder $query, string $channel): Builder
    {
        return $query->where('channel', $channel);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForNotifiable(Builder $query, Model $notifiable): Builder
    {
        return $query
            ->where('notifiable_type', $notifiable::class)
            ->where('notifiable_id', $notifiable->getKey());
    }

    public function markAsOpened(): self
    {
        $this->update([
            'status' => self::STATUS_OPENED,
<<<<<<< HEAD
<<<<<<< HEAD
            'opened_at' => now()]);
=======
            'opened_at' => now(),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'opened_at' => now()]);
>>>>>>> a988596b (first)

        return $this;
    }

    public function markAsClicked(): self
    {
        $this->update([
            'status' => self::STATUS_CLICKED,
<<<<<<< HEAD
<<<<<<< HEAD
            'clicked_at' => now()]);
=======
            'clicked_at' => now(),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'clicked_at' => now()]);
>>>>>>> a988596b (first)

        return $this;
    }

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'data' => 'array',
            'metadata' => 'array',
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
            'failed_at' => 'datetime',
            'opened_at' => 'datetime',
<<<<<<< HEAD
<<<<<<< HEAD
            'clicked_at' => 'datetime']);
=======
            'clicked_at' => 'datetime',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'clicked_at' => 'datetime']);
>>>>>>> a988596b (first)
    }
}
