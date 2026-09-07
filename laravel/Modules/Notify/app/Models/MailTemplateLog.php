<?php

declare(strict_types=1);

namespace Modules\Notify\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Media\Models\Media;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\Notify\Database\Factories\MailTemplateLogFactory;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Modules\Xot\Contracts\ProfileContract;
use Override;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
 * @property int|string $id
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
 * @property int|null $template_id
 * @property string|null $mailable_type
 * @property int|string|null $mailable_id
 * @property string|null $status
 * @property string|null $status_message
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
 * @property array<string, mixed>|null $data
 * @property array<string, mixed>|null $metadata
=======
 * @property array<string, mixed> $data
 * @property array<string, mixed> $metadata
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 * @property array<string, mixed>|null $data
 * @property array<string, mixed>|null $metadata
>>>>>>> a988596b (first)
=======
 * @property array<string, mixed>|null $data
 * @property array<string, mixed>|null $metadata
>>>>>>> a377e9e6 (.)
 * @property Carbon|null $sent_at
 * @property Carbon|null $delivered_at
 * @property Carbon|null $failed_at
 * @property Carbon|null $opened_at
 * @property Carbon|null $clicked_at
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
 * @property-read ProfileContract|null $creator
 * @property-read Model $mailable
=======
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ProfileContract|null $creator
 * @property-read Model|\Eloquent $mailable
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 * @property-read ProfileContract|null $creator
 * @property-read Model $mailable
>>>>>>> a988596b (first)
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read MailTemplate|null $template
 * @property-read ProfileContract|null $updater
<<<<<<< HEAD
<<<<<<< HEAD
 * @method static Builder<static>|MailTemplateLog newModelQuery()
 * @method static Builder<static>|MailTemplateLog newQuery()
 * @method static Builder<static>|MailTemplateLog query()
 * @property-read \Modules\User\Models\Profile|null $deleter
=======
 *
 * @method static MailTemplateLogFactory factory($count = null, $state = [])
=======
 *
>>>>>>> a988596b (first)
 * @method static Builder<static>|MailTemplateLog newModelQuery()
 * @method static Builder<static>|MailTemplateLog newQuery()
 * @method static Builder<static>|MailTemplateLog query()
 *
<<<<<<< HEAD
 * @property-read ProfileContract|null $deleter
 *
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Illuminate\Database\Eloquent\Model $mailable
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Modules\Notify\Models\MailTemplate|null $template
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Notify\Database\Factories\MailTemplateLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\MailTemplateLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\MailTemplateLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\MailTemplateLog query()
>>>>>>> 98d0a12c (.)
=======
 * @property-read ProfileContract|null $creator
 * @property-read Model $mailable
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read MailTemplate|null $template
 * @property-read ProfileContract|null $updater
 *
 * @method static Builder<static>|MailTemplateLog newModelQuery()
 * @method static Builder<static>|MailTemplateLog newQuery()
 * @method static Builder<static>|MailTemplateLog query()
 *
>>>>>>> a377e9e6 (.)
 * @mixin \Eloquent
 */
class MailTemplateLog extends BaseModel
{
    protected $fillable = [
        'template_id',
        'mailable_type',
        'mailable_id',
        'status',
        'status_message',
        'data',
        'metadata',
        'sent_at',
        'delivered_at',
        'failed_at',
        'opened_at',
<<<<<<< HEAD
<<<<<<< HEAD
        'clicked_at'];
=======
        'clicked_at',
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'clicked_at'];
>>>>>>> a988596b (first)

    /** @return BelongsTo<MailTemplate, $this> */
    public function template(): BelongsTo
    {
        return $this->belongsTo(MailTemplate::class, 'template_id');
    }

    /** @return MorphTo<Model, $this> */
    public function mailable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'metadata' => 'array',
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
            'failed_at' => 'datetime',
            'opened_at' => 'datetime',
<<<<<<< HEAD
<<<<<<< HEAD
            'clicked_at' => 'datetime'];
=======
            'clicked_at' => 'datetime',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'clicked_at' => 'datetime'];
>>>>>>> a988596b (first)
    }
}
