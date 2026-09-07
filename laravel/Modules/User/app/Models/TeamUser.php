<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Builder;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
=======
use Modules\Xot\Contracts\ProfileContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
>>>>>>> f548be94 (.)
=======
use Modules\Xot\Contracts\ProfileContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
=======
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * Modules\User\Models\TeamUser.
 *
 * @method static Builder|TeamUser newModelQuery()
 * @method static Builder|TeamUser newQuery()
 * @method static Builder|TeamUser query()
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int         $id
 * @property string      $uuid
=======
 * @property int $id
 * @property string $uuid
>>>>>>> f548be94 (.)
=======
 * @property int $id
 * @property string $uuid
=======
 *
 * @property int         $id
 * @property string      $uuid
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @property string|null $team_id
 * @property string|null $user_id
 * @property string|null $role
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $customer_id
<<<<<<< HEAD
=======
 *
>>>>>>> 2024e2e7 (.)
 * @method static Builder|TeamUser whereCreatedAt($value)
 * @method static Builder|TeamUser whereCreatedBy($value)
 * @method static Builder|TeamUser whereCustomerId($value)
 * @method static Builder|TeamUser whereId($value)
 * @method static Builder|TeamUser whereRole($value)
 * @method static Builder|TeamUser whereTeamId($value)
 * @method static Builder|TeamUser whereUpdatedAt($value)
 * @method static Builder|TeamUser whereUpdatedBy($value)
 * @method static Builder|TeamUser whereUserId($value)
 * @method static Builder|TeamUser whereUuid($value)
<<<<<<< HEAD
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static Builder|TeamUser whereDeletedAt($value)
 * @method static Builder|TeamUser whereDeletedBy($value)
<<<<<<< HEAD
 * @property ProfileContract|null         $creator
 * @property ProfileContract|null         $updater
 * @property ProfileContract|null         $deleter
 * @property Team|null                    $team
 * @property User|null                    $user
 * @property array<array-key, mixed>|null $permissions
 * @property string|null                  $joined_at
 * @method static Builder<static>|TeamUser                         childrenWith(array<int|string, mixed> $relations)
 * @method static Builder<static>|TeamUser                         childrenWithCount(array<int|string, mixed> $relations)
 * @method static \Modules\User\Database\Factories\TeamUserFactory factory($count = null, $state = [])
 * @method static Builder<static>|TeamUser                         whereJoinedAt($value)
 * @method static Builder<static>|TeamUser                         wherePermissions($value)
=======
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @mixin IdeHelperTeamUser
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 *
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder|TeamUser whereDeletedAt($value)
 * @method static Builder|TeamUser whereDeletedBy($value)
 *
 * @property ProfileContract|null         $creator
 * @property ProfileContract|null         $updater
 * @property ProfileContract|null         $deleter
 * @property Team|null                    $team
 * @property User|null                    $user
 * @property array<array-key, mixed>|null $permissions
 * @property string|null                  $joined_at
 *
 * @method static Builder<static>|TeamUser                         childrenWith(array<int|string, mixed> $relations)
 * @method static Builder<static>|TeamUser                         childrenWithCount(array<int|string, mixed> $relations)
 * @method static \Modules\User\Database\Factories\TeamUserFactory factory($count = null, $state = [])
 * @method static Builder<static>|TeamUser                         whereJoinedAt($value)
 * @method static Builder<static>|TeamUser                         wherePermissions($value)
 *
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @mixin \Eloquent
 */
class TeamUser extends BaseTeamUser
{
<<<<<<< HEAD
<<<<<<< HEAD
    protected $connection = 'user';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'permissions' => 'array',
        ];
    }
=======
    use HasFactory;

    protected $connection = 'user';
>>>>>>> f548be94 (.)
=======
    use HasFactory;

    protected $connection = 'user';
=======
    protected $connection = 'user';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'permissions' => 'array',
        ];
    }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
