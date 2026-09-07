<?php

declare(strict_types=1);

namespace Modules\User\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\Traits\RelationX;
use Modules\Xot\Traits\Updater;
=======
=======
>>>>>>> 87273113 (.)
use Modules\Xot\Actions\Factory\GetFactoryAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\User\Database\Factories\PermissionFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Models\Traits\RelationX;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\Traits\RelationX;
use Modules\Xot\Traits\Updater;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Spatie\Permission\Models\Permission as SpatiePermission;
use Webmozart\Assert\Assert;

/**
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int                         $id
 * @property string                      $name
 * @property string                      $guard_name
 * @property Carbon|null                 $created_at
 * @property Carbon|null                 $updated_at
 * @property string|null                 $updated_by
 * @property string|null                 $created_by
 * @property Collection<int, Permission> $permissions
 * @property int|null                    $permissions_count
 * @property Collection<int, Role>       $roles
 * @property int|null                    $roles_count
 * @property Collection<int, User>       $users
 * @property int|null                    $users_count
 * @method static Builder<static>|Permission newModelQuery()
 * @method static Builder<static>|Permission newQuery()
 * @method static Builder<static>|Permission permission($permissions, $without = false)
 * @method static Builder<static>|Permission query()
 * @method static Builder<static>|Permission role($roles, $guard = null, $without = false)
 * @method static Builder<static>|Permission whereCreatedAt($value)
 * @method static Builder<static>|Permission whereCreatedBy($value)
 * @method static Builder<static>|Permission whereGuardName($value)
 * @method static Builder<static>|Permission whereId($value)
 * @method static Builder<static>|Permission whereName($value)
 * @method static Builder<static>|Permission whereUpdatedAt($value)
 * @method static Builder<static>|Permission whereUpdatedBy($value)
 * @method static Builder<static>|Permission withoutPermission($permissions)
 * @method static Builder<static>|Permission withoutRole($roles, $guard = null)
 * @method static static                     firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                     updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 * @method static \Modules\User\Database\Factories\PermissionFactory factory($count = null, $state = [])
 * @property Collection<int, Team> $teams
 * @property int|null              $teams_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutTeam($teams)
=======
=======
>>>>>>> 87273113 (.)
 * Class Permission.
 *
 * Extends Spatie's Permission model to interact with the permission system.
 *
 * @property string                                                                    $id
 * @property string                                                                    $name
 * @property string                                                                    $guard_name
 * @property Carbon|null                                                               $created_at
 * @property Carbon|null                                                               $updated_at
 * @property string|null                                                               $created_by
 * @property string|null                                                               $updated_by
 * @property Collection<int, Role>                                                     $roles
 * @property int|null                                                                  $roles_count
 * @property EloquentCollection<int, Model&UserContract> $users
 * @property int|null                                                                  $users_count
 * @method static Builder|Permission newModelQuery()
 * @method static Builder|Permission newQuery()
 * @method static Builder|Permission query()
 * @method static Builder|Permission whereCreatedAt($value)
 * @method static Builder|Permission whereUpdatedAt($value)
 * @method static Builder|Permission whereCreatedBy($value)
 * @method static Builder|Permission whereUpdatedBy($value)
 * @method static Builder|Permission whereGuardName($value)
 * @method static Builder|Permission whereId($value)
 * @method static Builder|Permission whereName($value)
 * @method static Builder|Permission role($roles, $guard = null)
 * @method static Builder|Permission permission($permissions)
 * @property EloquentCollection<int, Permission> $permissions
 * @property int|null                            $permissions_count
 * @method static Builder|Permission withoutPermission($permissions)
 * @method static Builder|Permission withoutRole($roles, $guard = null)
 * @property PermissionRole|null $pivot
 * @mixin IdeHelperPermission
 * @method static PermissionFactory factory($count = null, $state = [])
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property int                         $id
 * @property string                      $name
 * @property string                      $guard_name
 * @property Carbon|null                 $created_at
 * @property Carbon|null                 $updated_at
 * @property string|null                 $updated_by
 * @property string|null                 $created_by
 * @property Collection<int, Permission> $permissions
 * @property int|null                    $permissions_count
 * @property Collection<int, Role>       $roles
 * @property int|null                    $roles_count
 * @property Collection<int, User>       $users
 * @property int|null                    $users_count
 *
 * @method static Builder<static>|Permission newModelQuery()
 * @method static Builder<static>|Permission newQuery()
 * @method static Builder<static>|Permission permission($permissions, $without = false)
 * @method static Builder<static>|Permission query()
 * @method static Builder<static>|Permission role($roles, $guard = null, $without = false)
 * @method static Builder<static>|Permission whereCreatedAt($value)
 * @method static Builder<static>|Permission whereCreatedBy($value)
 * @method static Builder<static>|Permission whereGuardName($value)
 * @method static Builder<static>|Permission whereId($value)
 * @method static Builder<static>|Permission whereName($value)
 * @method static Builder<static>|Permission whereUpdatedAt($value)
 * @method static Builder<static>|Permission whereUpdatedBy($value)
 * @method static Builder<static>|Permission withoutPermission($permissions)
 * @method static Builder<static>|Permission withoutRole($roles, $guard = null)
 * @method static static                     firstOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 * @method static static                     updateOrCreate(array<string, mixed> $attributes, array<string, mixed> $values = [])
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $deleter
 * @property ProfileContract|null $updater
 *
 * @method static \Modules\User\Database\Factories\PermissionFactory factory($count = null, $state = [])
 *
 * @property Collection<int, Team> $teams
 * @property int|null              $teams_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutTeam($teams)
 *
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @mixin \Eloquent
 */
class Permission extends SpatiePermission
{
<<<<<<< HEAD
<<<<<<< HEAD
    use HasXotFactory;

    use RelationX;
    use Updater;

    protected $connection = 'user';

=======
=======
>>>>>>> 87273113 (.)
    use HasFactory;
    use RelationX;

    /** @var string */
    protected $connection = 'user';

    /** @var string */
    protected $keyType = 'string';

    /** @var list<string> */
    protected $fillable = [
        'id',
        'name',
        'guard_name',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

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

<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    use HasXotFactory;

    use RelationX;
    use Updater;

    protected $connection = 'user';

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function getTable(): string
    {
        Assert::string($table = config('permission.table_names.permissions'));

        return $table;
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /** @var list<string> */
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'created_by',
        'updated_by',
    ];
=======
=======
>>>>>>> 87273113 (.)
    /**
     * The roles associated with the permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToManyX(Role::class);
    }

    /**
     * The users associated with the permission.
     */
    public function users(): BelongsToMany
    {
        $userClass = XotData::make()->getUserClass();

        return $this->belongsToManyX($userClass);
    }

    /**
     * @see vendor/ laravel / framework / src / Illuminate / Database / Eloquent / Factories / HasFactory.php
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return app(GetFactoryAction::class)->execute(static::class);
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /** @var list<string> */
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'created_by',
        'updated_by',
    ];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
