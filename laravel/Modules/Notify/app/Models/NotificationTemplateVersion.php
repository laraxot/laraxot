<?php

declare(strict_types=1);

namespace Modules\Notify\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Media\Models\Media;
=======
use Illuminate\Support\Carbon;
use Modules\Media\Models\Media;
use Modules\Notify\Database\Factories\NotificationTemplateVersionFactory;
use Modules\User\Models\Profile;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Media\Models\Media;
>>>>>>> a988596b (first)
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Traits\Updater;
use Override;
use RuntimeException;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

// BaseModel in same namespace provides common behaviors
/**
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int|null $template_id
=======
 * @property int $id
 * @property int $template_id
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 * @property int|null $template_id
>>>>>>> a988596b (first)
=======
 * @property int|null $template_id
>>>>>>> a377e9e6 (.)
 * @property string|null $subject
 * @property string|null $body_html
 * @property string|null $body_text
 * @property array<int, string>|null $channels
 * @property array<string, mixed>|null $variables
 * @property array<string, mixed>|null $conditions
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
 * @property int|string|null $version
 * @property-read ProfileContract|null $creator
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read NotificationTemplate|null $template
 * @property-read ProfileContract|null $updater
<<<<<<< HEAD
 * @method static Builder<static>|NotificationTemplateVersion newModelQuery()
 * @method static Builder<static>|NotificationTemplateVersion newQuery()
 * @method static Builder<static>|NotificationTemplateVersion query()
 * @property-read \Modules\User\Models\Profile|null $deleter
=======
 * @property int $version
 * @property string|null $created_by
 * @property string|null $change_notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Profile|null $creator
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read NotificationTemplate|null $template
 * @property-read Profile|null $updater
 *
 * @method static NotificationTemplateVersionFactory factory($count = null, $state = [])
=======
 *
>>>>>>> a988596b (first)
 * @method static Builder<static>|NotificationTemplateVersion newModelQuery()
 * @method static Builder<static>|NotificationTemplateVersion newQuery()
 * @method static Builder<static>|NotificationTemplateVersion query()
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
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Modules\Notify\Models\NotificationTemplate|null $template
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Notify\Database\Factories\NotificationTemplateVersionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationTemplateVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationTemplateVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationTemplateVersion query()
>>>>>>> 98d0a12c (.)
=======
 * @property int|string|null $version
 * @property-read ProfileContract|null $creator
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read NotificationTemplate|null $template
 * @property-read ProfileContract|null $updater
 *
 * @method static Builder<static>|NotificationTemplateVersion newModelQuery()
 * @method static Builder<static>|NotificationTemplateVersion newQuery()
 * @method static Builder<static>|NotificationTemplateVersion query()
 *
>>>>>>> a377e9e6 (.)
 * @mixin \Eloquent
 */
class NotificationTemplateVersion extends BaseModel
{
    use Updater;

    protected $fillable = [
        'template_id',
        'subject',
        'body_html',
        'body_text',
        'channels',
        'variables',
        'conditions',
        'version',
        'created_by',
<<<<<<< HEAD
<<<<<<< HEAD
        'change_notes'];
=======
        'change_notes',
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'change_notes'];
>>>>>>> a988596b (first)

    /** @return BelongsTo<NotificationTemplate, $this> */
    public function template(): BelongsTo
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }

    public function restoreTemplate(): NotificationTemplate
    {
        $template = $this->template;

        if (! $template) {
            throw new RuntimeException('Template not found for version '.$this->id);
        }

        $template->update([
            'subject' => $this->subject ?? null,
            'body_html' => $this->body_html ?? null,
            'body_text' => $this->body_text ?? null,
            'channels' => $this->channels ?? null,
            'variables' => $this->variables ?? null,
<<<<<<< HEAD
<<<<<<< HEAD
            'conditions' => $this->conditions ?? null]);
=======
            'conditions' => $this->conditions ?? null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'conditions' => $this->conditions ?? null]);
>>>>>>> a988596b (first)

        return $template;
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
            'channels' => 'array',
            'variables' => 'array',
<<<<<<< HEAD
<<<<<<< HEAD
            'conditions' => 'array'];
=======
            'conditions' => 'array',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'conditions' => 'array'];
>>>>>>> a988596b (first)
    }
}
