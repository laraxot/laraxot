<?php

declare(strict_types=1);

namespace Modules\Notify\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\Notify\Database\Factories\NotificationFactory;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Models\BaseModel;
use Override;

/**
 * Notification model for the Notify module.
 *
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
=======
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 *
>>>>>>> a988596b (first)
 * @method static \Modules\Notify\Database\Factories\NotificationFactory factory($count = null, $state = [])
 * @method static Builder<static>|Notification newModelQuery()
 * @method static Builder<static>|Notification newQuery()
 * @method static Builder<static>|Notification query()
<<<<<<< HEAD
=======
 *
>>>>>>> a988596b (first)
 * @property string $id
 * @property string|null $message
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string|int|null $tenant_id
 * @property string|int|null $user_id
 * @property string|null $subject_type
 * @property string|int|null $subject_id
 * @property array<int, string>|array<string, mixed>|null $channels
 * @property string|null $status
 * @property array<array-key, mixed> $data
 * @property Carbon|null $read_at
 * @property Carbon|null $sent_at
<<<<<<< HEAD
=======
 * @property string $id
 * @property string $type
 * @property string|null $subject
 * @property string|null $content
 * @property string|null $priority
 * @property array<string, mixed>|null $custom_headers
 * @property array<int, mixed>|null $attachments
 * @property string|null $message
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array<string, mixed> $data
 * @property Carbon|null $read_at
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
<<<<<<< HEAD
<<<<<<< HEAD
=======
 * @property int|null $tenant_id
 * @property int|null $user_id
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property list<string>|null $channels
 * @property string|null $status
 * @property Carbon|null $sent_at
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 *
 * @method static NotificationFactory factory($count = null, $state = [])
 * @method static Builder<static>|Notification newModelQuery()
 * @method static Builder<static>|Notification newQuery()
 * @method static Builder<static>|Notification query()
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 *
>>>>>>> a988596b (first)
 * @method static Builder<static>|Notification whereCreatedAt($value)
 * @method static Builder<static>|Notification whereCreatedBy($value)
 * @method static Builder<static>|Notification whereData($value)
 * @method static Builder<static>|Notification whereDeletedAt($value)
 * @method static Builder<static>|Notification whereDeletedBy($value)
 * @method static Builder<static>|Notification whereId($value)
 * @method static Builder<static>|Notification whereNotifiableId($value)
 * @method static Builder<static>|Notification whereNotifiableType($value)
 * @method static Builder<static>|Notification whereReadAt($value)
 * @method static Builder<static>|Notification whereType($value)
 * @method static Builder<static>|Notification whereUpdatedAt($value)
 * @method static Builder<static>|Notification whereUpdatedBy($value)
<<<<<<< HEAD
<<<<<<< HEAD
 * @property-read \Modules\User\Models\Profile|null $deleter
=======
 *
 * @property-read ProfileContract|null $deleter
 *
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 *
>>>>>>> a988596b (first)
=======
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Notify\Database\Factories\NotificationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\Notification query()
>>>>>>> 98d0a12c (.)
=======
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 *
 * @method static \Modules\Notify\Database\Factories\NotificationFactory factory($count = null, $state = [])
 * @method static Builder<static>|Notification newModelQuery()
 * @method static Builder<static>|Notification newQuery()
 * @method static Builder<static>|Notification query()
 *
 * @property string $id
 * @property string|null $message
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string|int|null $tenant_id
 * @property string|int|null $user_id
 * @property string|null $subject_type
 * @property string|int|null $subject_id
 * @property array<int, string>|array<string, mixed>|null $channels
 * @property string|null $status
 * @property array<array-key, mixed> $data
 * @property Carbon|null $read_at
 * @property Carbon|null $sent_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder<static>|Notification whereCreatedAt($value)
 * @method static Builder<static>|Notification whereCreatedBy($value)
 * @method static Builder<static>|Notification whereData($value)
 * @method static Builder<static>|Notification whereDeletedAt($value)
 * @method static Builder<static>|Notification whereDeletedBy($value)
 * @method static Builder<static>|Notification whereId($value)
 * @method static Builder<static>|Notification whereNotifiableId($value)
 * @method static Builder<static>|Notification whereNotifiableType($value)
 * @method static Builder<static>|Notification whereReadAt($value)
 * @method static Builder<static>|Notification whereType($value)
 * @method static Builder<static>|Notification whereUpdatedAt($value)
 * @method static Builder<static>|Notification whereUpdatedBy($value)
 *
>>>>>>> a377e9e6 (.)
 * @mixin \Eloquent
 */
class Notification extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'message',
        'type',
        'read_at',
        'tenant_id',
        'user_id',
        'subject_type',
        'subject_id',
        'channels',
        'status',
        'sent_at',
<<<<<<< HEAD
<<<<<<< HEAD
        'data'];
=======
        'data',
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'data'];
>>>>>>> a988596b (first)

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'sent_at' => 'datetime',
            'data' => 'array',
            'channels' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
<<<<<<< HEAD
<<<<<<< HEAD
            'deleted_at' => 'datetime'];
=======
            'deleted_at' => 'datetime',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'deleted_at' => 'datetime'];
>>>>>>> a988596b (first)
    }
}
