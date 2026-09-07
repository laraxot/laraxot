<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\Traits\RelationX;
use Modules\Xot\Traits\Updater;
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Models\Traits\RelationX;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\Traits\RelationX;
use Modules\Xot\Traits\Updater;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Spatie\Permission\Models\Role as SpatieRole;
use Webmozart\Assert\Assert;

/**
 * Modules\User\Models\Role.
 *
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int $id
=======
 * @property string $id
>>>>>>> f548be94 (.)
=======
 * @property string $id
=======
 * @property int $id
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @property string $uuid
 * @property string|null $team_id
 * @property string $name
 * @property string $guard_name
<<<<<<< HEAD
<<<<<<< HEAD
 * @property string|null $display_name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Collection<int, Permission> $permissions
 * @property int|null $permissions_count
 * @property Team|null $team
 * @property Collection<int, Model&UserContract> $users
 * @property int|null $users_count
 * @property PermissionRole|null $pivot
=======
=======
>>>>>>> 87273113 (.)
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection<int, Permission> $permissions
 * @property int|null $permissions_count
 * @property Team|null $team
 * @property EloquentCollection<int, Model&UserContract> $users
 * @property int|null $users_count
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property string|null $display_name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Collection<int, Permission> $permissions
 * @property int|null $permissions_count
 * @property Team|null $team
 * @property Collection<int, Model&UserContract> $users
 * @property int|null $users_count
 * @property PermissionRole|null $pivot
 *
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role permission($permissions)
 * @method static Builder|Role query()
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereGuardName($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereTeamId($value)
 * @method static Builder|Role whereUpdatedAt($value)
<<<<<<< HEAD
<<<<<<< HEAD
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereCreatedBy($value)
 * @method static Builder|Role whereUpdatedBy($value)
 * @method static Builder|Role withoutPermission($permissions)
 * @method static Builder|Role whereDescription($value)
 * @method static Builder|Role whereDisplayName($value)
 * @method static static firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static \Modules\User\Database\Factories\RoleFactory factory($count = null, $state = [])
 * @method static Builder<static>|Role whereUuid($value)
=======
=======
>>>>>>> 87273113 (.)
 * @method static Builder|Role whereUuid($value)
 * @property int $id
 * @method static Builder|Role whereId($value)
 * @property string|null $updated_by
 * @property string|null $created_by
 * @method static Builder|Role whereCreatedBy($value)
 * @method static Builder|Role whereUpdatedBy($value)
 * @mixin Eloquent
 * @method static Builder|Role withoutPermission($permissions)
 * @property PermissionRole|null $pivot
 * @mixin IdeHelperRole
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereCreatedBy($value)
 * @method static Builder|Role whereUpdatedBy($value)
 * @method static Builder|Role withoutPermission($permissions)
 * @method static Builder|Role whereDescription($value)
 * @method static Builder|Role whereDisplayName($value)
 * @method static static firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 *
 * @method static \Modules\User\Database\Factories\RoleFactory factory($count = null, $state = [])
 * @method static Builder<static>|Role whereUuid($value)
 *
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @mixin \Eloquent
 */
class Role extends SpatieRole
{
<<<<<<< HEAD
<<<<<<< HEAD
    use HasXotFactory;

    use RelationX;
    use Updater;

    // use HasUuids;

    final public const int ROLE_ADMINISTRATOR = 1;

    final public const int ROLE_OWNER = 2;

    final public const int ROLE_USER = 3;

    protected $connection = 'user';

    protected $keyType = 'int';

    /** @var list<string> */
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'team_id',
        'created_by',
        'updated_by',
    ];
=======
=======
>>>>>>> 87273113 (.)
    use HasFactory;
    use RelationX;

    // use HasUuids;

    final public const ROLE_ADMINISTRATOR = 1;

    final public const ROLE_OWNER = 2;

    final public const ROLE_USER = 3;

    /** @var string */
    protected $connection = 'user';

    /** @var string */
    protected $keyType = 'string';

    // protected $fillable=['id','']
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    use HasXotFactory;

    use RelationX;
    use Updater;

    // use HasUuids;

    final public const int ROLE_ADMINISTRATOR = 1;

    final public const int ROLE_OWNER = 2;

    final public const int ROLE_USER = 3;

    protected $connection = 'user';

    protected $keyType = 'int';

    /** @var list<string> */
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'team_id',
        'created_by',
        'updated_by',
    ];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    public function getTable(): string
    {
        Assert::string($table = config('permission.table_names.roles'));

        return $table;
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return BelongsTo<Model, $this>
=======
=======
>>>>>>> 87273113 (.)
    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'uuid' => 'string',
            'name' => 'string',
            'guard_name' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get all of the teams the user belongs to.
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return BelongsTo<Model, $this>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function team(): BelongsTo
    {
        $xotData = XotData::make();
        /** @var class-string<Model> */
        $teamClass = $xotData->getTeamClass();

        return $this->belongsTo($teamClass);
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * @return BelongsToMany<Permission, $this, Pivot, 'pivot'>
=======
     * A role may be given various permissions.
>>>>>>> f548be94 (.)
=======
     * A role may be given various permissions.
=======
     * @return BelongsToMany<Permission, $this, Pivot, 'pivot'>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToManyX(Permission::class);
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'int',
            'name' => 'string',
            'guard_name' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
<<<<<<< HEAD
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
