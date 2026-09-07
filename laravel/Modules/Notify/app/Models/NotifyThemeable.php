<?php

declare(strict_types=1);

namespace Modules\Notify\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;

/**
 * Modules\Notify\Models\NotifyThemeable.
 *
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @method static Builder<static>|NotifyThemeable newModelQuery()
 * @method static Builder<static>|NotifyThemeable newQuery()
 * @method static Builder<static>|NotifyThemeable query()
=======
=======
>>>>>>> a377e9e6 (.)
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 *
 * @method static Builder<static>|NotifyThemeable newModelQuery()
 * @method static Builder<static>|NotifyThemeable newQuery()
 * @method static Builder<static>|NotifyThemeable query()
 *
<<<<<<< HEAD
>>>>>>> a988596b (first)
=======
>>>>>>> a377e9e6 (.)
 * @property string $id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property int|null $notify_theme_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
<<<<<<< HEAD
<<<<<<< HEAD
=======
 *
>>>>>>> a988596b (first)
=======
 *
>>>>>>> a377e9e6 (.)
 * @method static Builder<static>|NotifyThemeable whereCreatedAt($value)
 * @method static Builder<static>|NotifyThemeable whereCreatedBy($value)
 * @method static Builder<static>|NotifyThemeable whereDeletedAt($value)
 * @method static Builder<static>|NotifyThemeable whereDeletedBy($value)
 * @method static Builder<static>|NotifyThemeable whereId($value)
 * @method static Builder<static>|NotifyThemeable whereModelId($value)
 * @method static Builder<static>|NotifyThemeable whereModelType($value)
 * @method static Builder<static>|NotifyThemeable whereNotifyThemeId($value)
 * @method static Builder<static>|NotifyThemeable whereUpdatedAt($value)
 * @method static Builder<static>|NotifyThemeable whereUpdatedBy($value)
<<<<<<< HEAD
<<<<<<< HEAD
 * @property-read \Modules\User\Models\Profile|null $deleter
=======
 * @property int $id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property Carbon|null $created_at
 * @property string|null $created_by
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property int|null $notify_theme_id
 *
 * @method static Builder|NotifyThemeable newModelQuery()
 * @method static Builder|NotifyThemeable newQuery()
 * @method static Builder|NotifyThemeable query()
 * @method static Builder|NotifyThemeable whereCreatedAt($value)
 * @method static Builder|NotifyThemeable whereCreatedBy($value)
 * @method static Builder|NotifyThemeable whereId($value)
 * @method static Builder|NotifyThemeable whereModelId($value)
 * @method static Builder|NotifyThemeable whereModelType($value)
 * @method static Builder|NotifyThemeable whereNotifyThemeId($value)
 * @method static Builder|NotifyThemeable whereUpdatedAt($value)
 * @method static Builder|NotifyThemeable whereUpdatedBy($value)
 *
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $updater
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder<static>|NotifyThemeable whereDeletedAt($value)
 * @method static Builder<static>|NotifyThemeable whereDeletedBy($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotifyThemeable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotifyThemeable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|\Modules\Notify\Models\NotifyThemeable query()
>>>>>>> 98d0a12c (.)
=======
 *
>>>>>>> a377e9e6 (.)
 * @mixin \Eloquent
 */
class NotifyThemeable extends BaseMorphPivot
{
    // ...
}
