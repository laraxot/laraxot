<?php

declare(strict_types=1);

namespace Modules\Notify\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> a988596b (first)
=======
>>>>>>> 98d0a12c (.)
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Media\Models\Media;
use Modules\Notify\Database\Factories\NotifyThemeFactory;
use Modules\Xot\Contracts\ProfileContract;
use Override;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

/**
 * Modules\Notify\Models\NotifyTheme.
 *
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
 * @method static NotifyThemeFactory factory($count = null, $state = [])
=======
 * @method static NotifyThemeFactory factory($count = null, $state = [])
 *
>>>>>>> a988596b (first)
=======
 * @method static NotifyThemeFactory factory($count = null, $state = [])
 *
>>>>>>> a377e9e6 (.)
 * @property-read ProfileContract|null $creator
 * @property-read array{path: string, width: int, height: int} $logo
 * @property-read Model $linkable
 * @property-read MediaCollection<int, Media> $media
<<<<<<< HEAD
 * @property-read int|null $media_count
 * @property-read ProfileContract|null $updater
<<<<<<< HEAD
 * @method static Builder<static>|NotifyTheme newModelQuery()
 * @method static Builder<static>|NotifyTheme newQuery()
 * @method static Builder<static>|NotifyTheme query()
 * @property string $id
=======
 * @property int $id
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 *
 * @method static Builder<static>|NotifyTheme newModelQuery()
 * @method static Builder<static>|NotifyTheme newQuery()
 * @method static Builder<static>|NotifyTheme query()
 *
 * @property string $id
>>>>>>> a988596b (first)
 * @property string|null $lang
 * @property string|null $type
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $from
<<<<<<< HEAD
<<<<<<< HEAD
=======
 * @property Carbon|null $created_at
 * @property string|null $created_by
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
 * @property string|null $post_type
 * @property int|null $post_id
 * @property string|null $body_html
 * @property string|null $theme
 * @property string|null $from_email
 * @property string|null $logo_src
 * @property int|null $logo_width
 * @property int|null $logo_height
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
 * @property array<array-key, mixed>|null $view_params
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
<<<<<<< HEAD
=======
 *
>>>>>>> a988596b (first)
 * @method static Builder<static>|NotifyTheme whereBody($value)
 * @method static Builder<static>|NotifyTheme whereBodyHtml($value)
 * @method static Builder<static>|NotifyTheme whereCreatedAt($value)
 * @method static Builder<static>|NotifyTheme whereCreatedBy($value)
 * @method static Builder<static>|NotifyTheme whereDeletedAt($value)
 * @method static Builder<static>|NotifyTheme whereDeletedBy($value)
 * @method static Builder<static>|NotifyTheme whereFrom($value)
 * @method static Builder<static>|NotifyTheme whereFromEmail($value)
 * @method static Builder<static>|NotifyTheme whereId($value)
 * @method static Builder<static>|NotifyTheme whereLang($value)
 * @method static Builder<static>|NotifyTheme whereLogoHeight($value)
 * @method static Builder<static>|NotifyTheme whereLogoSrc($value)
 * @method static Builder<static>|NotifyTheme whereLogoWidth($value)
 * @method static Builder<static>|NotifyTheme wherePostId($value)
 * @method static Builder<static>|NotifyTheme wherePostType($value)
 * @method static Builder<static>|NotifyTheme whereSubject($value)
 * @method static Builder<static>|NotifyTheme whereTheme($value)
 * @method static Builder<static>|NotifyTheme whereType($value)
 * @method static Builder<static>|NotifyTheme whereUpdatedAt($value)
 * @method static Builder<static>|NotifyTheme whereUpdatedBy($value)
 * @method static Builder<static>|NotifyTheme whereViewParams($value)
<<<<<<< HEAD
 * @property-read \Modules\User\Models\Profile|null $deleter
 * @mixin Eloquent
=======
 * @property array<string, mixed> $view_params
 * @property array<string, mixed> $logo
 * @property Model|Eloquent $linkable
 * @property MediaCollection<int, Media> $media
 * @property int|null $media_count
 *
 * @method static NotifyThemeFactory factory($count = null, $state = [])
 * @method static Builder|NotifyTheme newModelQuery()
 * @method static Builder|NotifyTheme newQuery()
 * @method static Builder|NotifyTheme query()
 * @method static Builder|NotifyTheme whereBody($value)
 * @method static Builder|NotifyTheme whereBodyHtml($value)
 * @method static Builder|NotifyTheme whereCreatedAt($value)
 * @method static Builder|NotifyTheme whereCreatedBy($value)
 * @method static Builder|NotifyTheme whereFrom($value)
 * @method static Builder|NotifyTheme whereFromEmail($value)
 * @method static Builder|NotifyTheme whereId($value)
 * @method static Builder|NotifyTheme whereLang($value)
 * @method static Builder|NotifyTheme whereLogoHeight($value)
 * @method static Builder|NotifyTheme whereLogoSrc($value)
 * @method static Builder|NotifyTheme whereLogoWidth($value)
 * @method static Builder|NotifyTheme wherePostId($value)
 * @method static Builder|NotifyTheme wherePostType($value)
 * @method static Builder|NotifyTheme whereSubject($value)
 * @method static Builder|NotifyTheme whereTheme($value)
 * @method static Builder|NotifyTheme whereType($value)
 * @method static Builder|NotifyTheme whereUpdatedAt($value)
 * @method static Builder|NotifyTheme whereUpdatedBy($value)
 * @method static Builder|NotifyTheme whereViewParams($value)
 *
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder<static>|NotifyTheme whereDeletedAt($value)
 * @method static Builder<static>|NotifyTheme whereDeletedBy($value)
 *
 * @property-read ProfileContract|null $deleter
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
 */
class NotifyTheme extends BaseModel
{
=======
 *
 * @mixin Eloquent
=======
 * @property-read \Modules\WorkOrder\Models\Profile|null $creator
 * @property-read \Modules\WorkOrder\Models\Profile|null $deleter
 * @property-read \Modules\Notify\Models\array{path: $logo
 * @property-read \Illuminate\Database\Eloquent\Model $linkable
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Modules\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Modules\WorkOrder\Models\Profile|null $updater
 * @method static \Modules\Notify\Database\Factories\NotifyThemeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotifyTheme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotifyTheme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotifyTheme query()
 * @mixin \Eloquent
>>>>>>> 98d0a12c (.)
=======
 * @property-read int|null $media_count
 * @property-read ProfileContract|null $updater
 *
 * @method static Builder<static>|NotifyTheme newModelQuery()
 * @method static Builder<static>|NotifyTheme newQuery()
 * @method static Builder<static>|NotifyTheme query()
 *
 * @property string $id
 * @property string|null $lang
 * @property string|null $type
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $from
 * @property string|null $post_type
 * @property int|null $post_id
 * @property string|null $body_html
 * @property string|null $theme
 * @property string|null $from_email
 * @property string|null $logo_src
 * @property int|null $logo_width
 * @property int|null $logo_height
 * @property array<array-key, mixed>|null $view_params
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder<static>|NotifyTheme whereBody($value)
 * @method static Builder<static>|NotifyTheme whereBodyHtml($value)
 * @method static Builder<static>|NotifyTheme whereCreatedAt($value)
 * @method static Builder<static>|NotifyTheme whereCreatedBy($value)
 * @method static Builder<static>|NotifyTheme whereDeletedAt($value)
 * @method static Builder<static>|NotifyTheme whereDeletedBy($value)
 * @method static Builder<static>|NotifyTheme whereFrom($value)
 * @method static Builder<static>|NotifyTheme whereFromEmail($value)
 * @method static Builder<static>|NotifyTheme whereId($value)
 * @method static Builder<static>|NotifyTheme whereLang($value)
 * @method static Builder<static>|NotifyTheme whereLogoHeight($value)
 * @method static Builder<static>|NotifyTheme whereLogoSrc($value)
 * @method static Builder<static>|NotifyTheme whereLogoWidth($value)
 * @method static Builder<static>|NotifyTheme wherePostId($value)
 * @method static Builder<static>|NotifyTheme wherePostType($value)
 * @method static Builder<static>|NotifyTheme whereSubject($value)
 * @method static Builder<static>|NotifyTheme whereTheme($value)
 * @method static Builder<static>|NotifyTheme whereType($value)
 * @method static Builder<static>|NotifyTheme whereUpdatedAt($value)
 * @method static Builder<static>|NotifyTheme whereUpdatedBy($value)
 * @method static Builder<static>|NotifyTheme whereViewParams($value)
 *
 * @mixin Eloquent
>>>>>>> a377e9e6 (.)
 */
class NotifyTheme extends BaseModel
{

>>>>>>> a988596b (first)
    /** @var list<string> */
    protected $fillable = [
        'id',
        'lang',
        'type',
        'subject',
        'body',
        'body_html',
        'from',
        'from_email',
        'post_type',
        'post_id',
        'theme',
        'logo_src',
        'logo_width',
        'logo_height',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        'view_params'];

    /** @var list<string> */
    protected $appends = [
        'logo'];
<<<<<<< HEAD
=======
        'view_params',
    ];

    /** @var list<string> */
    protected $appends = [
        'logo',
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

    /**
     * @param  array<string, mixed>|null  $value
     * @return array{path: string, width: int, height: int}
     */
    public function getLogoAttribute(?array $value): array
    {
        return [
            // 'path' => asset(strval($this->logo_src)),
            'path' => url($this->getFirstMediaUrl()),
            'width' => $this->logo_width ?? 50,
<<<<<<< HEAD
<<<<<<< HEAD
            'height' => $this->logo_height ?? 50];
=======
            'height' => $this->logo_height ?? 50,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'height' => $this->logo_height ?? 50];
>>>>>>> a988596b (first)
    }

    /**
     * Get the parent linkable model (user or post).
     */
    /** @return MorphTo<Model, $this> */
    public function linkable(): MorphTo
    {
        return $this->morphTo('post');
    }

    /** @return array<string, string> */
    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'uuid' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'updated_by' => 'string',
            'created_by' => 'string',
            'deleted_by' => 'string',
            // 'published_at' => 'datetime:Y-m-d', // da verificare
<<<<<<< HEAD
<<<<<<< HEAD
            'view_params' => 'array'];
=======
            'view_params' => 'array',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'view_params' => 'array'];
>>>>>>> a988596b (first)
    }
}
