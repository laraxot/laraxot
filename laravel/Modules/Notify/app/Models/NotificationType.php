<?php

declare(strict_types=1);

namespace Modules\Notify\Models;

use Illuminate\Database\Eloquent\Builder;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Illuminate\Support\Carbon;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Modules\Notify\Database\Factories\NotificationTypeFactory;
=======
>>>>>>> f67f5638 (fix(notify): UserContract narrowing + stale test assert fix, PHPStan L10 verified clean)
=======
>>>>>>> ab6a976d (fix(notify): Theme/EmailTemplate/NotificationType extend BaseModel, not Model directly)
=======
use Illuminate\Database\Eloquent\Model;
>>>>>>> 48f28c29 (.)
use Override;

/**
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int $id
 * @property string|null $name
=======
 * @method static Builder<static>|NotificationType newModelQuery()
 * @method static Builder<static>|NotificationType newQuery()
 * @method static Builder<static>|NotificationType query()
 *
 * @property int $id
 * @property string $name
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
 * @property int $id
 * @property string|null $name
>>>>>>> a988596b (first)
=======
 * @property int $id
 * @property string|null $name
>>>>>>> a377e9e6 (.)
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $category
 * @property bool $is_active
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
 * @property array<string, mixed>|null $channels
 * @property array<string, mixed>|null $settings
 * @property string|null $template
 * @method static Builder<static>|NotificationType newModelQuery()
 * @method static Builder<static>|NotificationType newQuery()
 * @method static Builder<static>|NotificationType query()
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
=======
 * @property array<string, array<string, mixed>>|null $channels
 * @property array<string, mixed>|null $settings
 * @property array<string, mixed>|null $metrics
 * @property array<string, mixed>|null $scheduling
 * @property array<string, mixed>|null $rules
 * @property array<string, mixed>|null $permissions
 * @property string|null $display_name
 * @property array<string, mixed>|null $templates
 * @property array<string, mixed>|null $integrations
 * @property array<string, mixed>|null $delivery_rules
 * @property string|null $template
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 *
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
 * @method static Builder<static>|NotificationType whereCreatedAt($value)
 * @method static Builder<static>|NotificationType whereCreatedBy($value)
 * @method static Builder<static>|NotificationType whereDescription($value)
 * @method static Builder<static>|NotificationType whereId($value)
 * @method static Builder<static>|NotificationType whereName($value)
 * @method static Builder<static>|NotificationType whereTemplate($value)
 * @method static Builder<static>|NotificationType whereUpdatedAt($value)
 * @method static Builder<static>|NotificationType whereUpdatedBy($value)
<<<<<<< HEAD
=======
 *
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
=======
>>>>>>> a377e9e6 (.)
 * @property array<string, mixed>|null $channels
 * @property array<string, mixed>|null $settings
 * @property string|null $template
 *
 * @method static Builder<static>|NotificationType newModelQuery()
 * @method static Builder<static>|NotificationType newQuery()
 * @method static Builder<static>|NotificationType query()
 *
<<<<<<< HEAD
>>>>>>> a988596b (first)
=======
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotificationType query()
>>>>>>> 98d0a12c (.)
=======
>>>>>>> a377e9e6 (.)
 * @mixin \Eloquent
 */
class NotificationType extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'is_active',
        'channels',
        'settings',
<<<<<<< HEAD
<<<<<<< HEAD
        'template'];
=======
        'template',
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'template'];
>>>>>>> a988596b (first)

    /** @return array<string, string> */
    #[Override]
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'channels' => 'array',
<<<<<<< HEAD
<<<<<<< HEAD
            'settings' => 'array'];
=======
            'settings' => 'array',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'settings' => 'array'];
>>>>>>> a988596b (first)
    }
}
