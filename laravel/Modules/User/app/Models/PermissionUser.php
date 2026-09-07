<?php

declare(strict_types=1);

namespace Modules\User\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Contracts\ProfileContract;
=======
use Modules\Xot\Contracts\ProfileContract;
use Modules\User\Database\Factories\PermissionUserFactory;
use Illuminate\Database\Eloquent\Builder;
>>>>>>> f548be94 (.)
=======
use Modules\Xot\Contracts\ProfileContract;
use Modules\User\Database\Factories\PermissionUserFactory;
use Illuminate\Database\Eloquent\Builder;
=======
use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Contracts\ProfileContract;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
<<<<<<< HEAD
<<<<<<< HEAD
 * @method static Builder<static>|PermissionUser newModelQuery()
 * @method static Builder<static>|PermissionUser newQuery()
 * @method static Builder<static>|PermissionUser query()
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\PermissionUserFactory factory($count = null, $state = [])
 * @property string $id
 * @property int $permission_id
 * @property string $model_type
 * @property string $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $team_id
 * @method static Builder<static>|PermissionUser whereCreatedAt($value)
 * @method static Builder<static>|PermissionUser whereCreatedBy($value)
 * @method static Builder<static>|PermissionUser whereId($value)
 * @method static Builder<static>|PermissionUser whereModelId($value)
 * @method static Builder<static>|PermissionUser whereModelType($value)
 * @method static Builder<static>|PermissionUser wherePermissionId($value)
 * @method static Builder<static>|PermissionUser whereTeamId($value)
 * @method static Builder<static>|PermissionUser whereUpdatedAt($value)
 * @method static Builder<static>|PermissionUser whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class PermissionUser extends ModelHasPermission {}
=======
=======
>>>>>>> 87273113 (.)
 * @method static PermissionUserFactory factory($count = null, $state = [])
 * @method static Builder<static>|PermissionUser newModelQuery()
 * @method static Builder<static>|PermissionUser newQuery()
 * @method static Builder<static>|PermissionUser query()
 * @mixin IdeHelperPermissionUser
 * @mixin \Eloquent
 */
class PermissionUser extends ModelHasPermission
{
}
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 *
 * @method static Builder<static>|PermissionUser newModelQuery()
 * @method static Builder<static>|PermissionUser newQuery()
 * @method static Builder<static>|PermissionUser query()
 *
 * @property ProfileContract|null $deleter
 *
 * @method static \Modules\User\Database\Factories\PermissionUserFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class PermissionUser extends ModelHasPermission {}
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
