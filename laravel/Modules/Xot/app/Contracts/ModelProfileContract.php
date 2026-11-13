<?php

declare(strict_types=1);

namespace Modules\Xot\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

/**
 * Modules\Xot\Contracts\ModelProfileContract.
 *
 * @phpstan-require-extends Model
 *
<<<<<<< HEAD
 * @mixin Model
=======
 * @mixin \Eloquent
>>>>>>> 77b1e2748 (first)
 */
interface ModelProfileContract extends ModelContract
{
    /**
     * Grant the given permission(s) to a role.
     *
<<<<<<< HEAD
     * @param  string|int|array<int, string|int|Permission>|Permission|Collection<int, Permission>  $permissions
=======
<<<<<<< .merge_file_Tj90Et
     * @param  string|int|array<int, string|int|Permission>|Permission|Collection<int, Permission>  $permissions
=======
     * <<<<<<< HEAD
     *
     * @param string|int|array<int, string|int|Permission>|Permission|Collection<int, Permission> $permissions
     *                                                                                                         =======
     *                                                                                                         <<<<<<< HEAD
     *                                                                                                         <<<<<<< HEAD
     *                                                                                                         <<<<<<< .merge_file_7zavuJ
     * @param string|int|array<int, string|int|Permission>|Permission|Collection<int, Permission> $permissions
     *                                                                                                         =======
     *                                                                                                         <<<<<<< .merge_file_x9kVYp
     * @param string|int|array<int, string|int|Permission>|Permission|Collection<int, Permission> $permissions
     *                                                                                                         =======
     *                                                                                                         <<<<<<< HEAD
     * @param string|int|array<int, string|int|Permission>|Permission|Collection<int, Permission> $permissions
     *                                                                                                         =======
     * @param string|int|array<int, string|int|Permission>|Permission|Collection<int, Permission> $permissions
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_an0rj9
     *
     * >>>>>>> .merge_file_PlLTO3
     *
     * =======
     * <<<<<<< HEAD
     * @param string|int|array<int, string|int|Permission>|Permission|Collection<int, Permission> $permissions
     *                                                                                                         =======
     *                                                                                                         <<<<<<< .merge_file_DMxry3
     *                                                                                                         =======
     *                                                                                                         <<<<<<< HEAD
     *                                                                                                         <<<<<<< .merge_file_7zavuJ
     *                                                                                                         >>>>>>> .merge_file_55oZz4
     * @param string|int|array<int, string|int|Permission>|Permission|Collection<int, Permission> $permissions
     *
     * <<<<<<< .merge_file_DMxry3
     * =======
     * >>>>>>> laraxot/dev
     * =======
     * @param string|int|array<int, string|int|Permission>|Permission|Collection<int, Permission> $permissions
     *                                                                                                         >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_55oZz4
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string|int|array<int, string|int|Permission>|Permission|Collection<int, Permission> $permissions
     *                                                                                                         >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_6BkZVO
>>>>>>> 77b1e2748 (first)
     * @return $this
     */
    public function givePermissionTo(string|int|array|Permission|Collection $permissions = []);

    /**
     * Assign the given role to the model.
     *
<<<<<<< HEAD
     * @param  array<int, string|int|Role>|string|int|Role|Collection<int, Role>  $roles
=======
<<<<<<< .merge_file_Tj90Et
     * @param  array<int, string|int|Role>|string|int|Role|Collection<int, Role>  $roles
=======
     * <<<<<<< HEAD
     *
     * @param array<int, string|int|Role>|string|int|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< HEAD
     *                                                                                 <<<<<<< HEAD
     *                                                                                 <<<<<<< .merge_file_7zavuJ
     * @param array<int, string|int|Role>|string|int|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< .merge_file_x9kVYp
     * @param array<int, string|int|Role>|string|int|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< HEAD
     * @param array<int, string|int|Role>|string|int|Role|Collection<int, Role> $roles
     *                                                                                 =======
     * @param array<int, string|int|Role>|string|int|Role|Collection<int, Role> $roles
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_an0rj9
     *
     * >>>>>>> .merge_file_PlLTO3
     *
     * =======
     * <<<<<<< HEAD
     * @param array<int, string|int|Role>|string|int|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< .merge_file_DMxry3
     *                                                                                 =======
     *                                                                                 <<<<<<< HEAD
     *                                                                                 <<<<<<< .merge_file_7zavuJ
     *                                                                                 >>>>>>> .merge_file_55oZz4
     * @param array<int, string|int|Role>|string|int|Role|Collection<int, Role> $roles
     *
     * <<<<<<< .merge_file_DMxry3
     * =======
     * >>>>>>> laraxot/dev
     * =======
     * @param array<int, string|int|Role>|string|int|Role|Collection<int, Role> $roles
     *                                                                                 >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_55oZz4
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param array<int, string|int|Role>|string|int|Role|Collection<int, Role> $roles
     *                                                                                 >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_6BkZVO
>>>>>>> 77b1e2748 (first)
     * @return $this
     */
    public function assignRole(array|string|int|Role|Collection $roles = [
    ]);

    /**
     * Determine if the model has (one of) the given role(s).
     *
<<<<<<< HEAD
     * @param  string|int|array<int, string|int|Role>|Role|Collection<int, Role>  $roles
=======
<<<<<<< .merge_file_Tj90Et
     * @param  string|int|array<int, string|int|Role>|Role|Collection<int, Role>  $roles
=======
     * <<<<<<< HEAD
     *
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< HEAD
     *                                                                                 <<<<<<< HEAD
     *                                                                                 <<<<<<< .merge_file_7zavuJ
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 =======
     *                                                                                 <<<<<<< HEAD
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< .merge_file_DMxry3
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< HEAD
     *                                                                                 <<<<<<< .merge_file_7zavuJ
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 >>>>>>> laraxot/dev
     *                                                                                 <<<<<<< .merge_file_x9kVYp
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< HEAD
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 >>>>>>> laraxot/dev
     *                                                                                 >>>>>>> .merge_file_an0rj9
     *                                                                                 >>>>>>> .merge_file_PlLTO3
     *                                                                                 =======
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                                                                 <<<<<<< HEAD
     *                                                                                 =======
     *                                                                                 >>>>>>> .merge_file_55oZz4
     *                                                                                 >>>>>>> laraxot/dev
     *                                                                                 =======
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 >>>>>>> laraxot/dev
     *                                                                                 >>>>>>> laraxot/dev
     *                                                                                 >>>>>>> laraxot/dev
>>>>>>> .merge_file_6BkZVO
>>>>>>> 77b1e2748 (first)
     */
    public function hasRole(
        string|int|array|Role|Collection $roles,
        ?string $guard = null,
    ): bool;

    /**
     * Determine if the model has any of the given role(s).
     *
     * Alias to hasRole() but without Guard controls
     *
<<<<<<< HEAD
     * @param  string|int|array<int, string|int|Role>|Role|Collection<int, Role>  $roles
=======
<<<<<<< .merge_file_Tj90Et
     * @param  string|int|array<int, string|int|Role>|Role|Collection<int, Role>  $roles
=======
     * <<<<<<< HEAD
     *
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< HEAD
     *                                                                                 <<<<<<< HEAD
     *                                                                                 <<<<<<< .merge_file_7zavuJ
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 =======
     *                                                                                 <<<<<<< HEAD
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< .merge_file_DMxry3
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< HEAD
     *                                                                                 <<<<<<< .merge_file_7zavuJ
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 >>>>>>> laraxot/dev
     *                                                                                 <<<<<<< .merge_file_x9kVYp
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     *                                                                                 <<<<<<< HEAD
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 =======
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 >>>>>>> laraxot/dev
     *                                                                                 >>>>>>> .merge_file_an0rj9
     *                                                                                 >>>>>>> .merge_file_PlLTO3
     *                                                                                 =======
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *                                                                                 <<<<<<< HEAD
     *                                                                                 =======
     *                                                                                 >>>>>>> .merge_file_55oZz4
     *                                                                                 >>>>>>> laraxot/dev
     *                                                                                 =======
     * @param string|int|array<int, string|int|Role>|Role|Collection<int, Role> $roles
     *                                                                                 >>>>>>> laraxot/dev
     *                                                                                 >>>>>>> laraxot/dev
     *                                                                                 >>>>>>> laraxot/dev
>>>>>>> .merge_file_6BkZVO
>>>>>>> 77b1e2748 (first)
     */
    public function hasAnyRole(string|int|array|Role|Collection $roles = [
    ]): bool;

    /**
     * Determine if the model may perform the given permission.
     *
     * @throws PermissionDoesNotExist
     */
    public function hasPermissionTo(string|int|Permission $permission, ?string $guardName = null): bool;

    /**
     * Create a new Eloquent query builder for the model.
     *
<<<<<<< HEAD
     * @param  Builder<Model>  $query
=======
<<<<<<< .merge_file_Tj90Et
     * @param  Builder<Model>  $query
=======
     * <<<<<<< HEAD
     *
     * @param Builder<Model> $query
     *                              =======
     *                              <<<<<<< HEAD
     *                              <<<<<<< HEAD
     *                              <<<<<<< .merge_file_7zavuJ
     * @param Builder<Model> $query
     *                              =======
     *                              <<<<<<< .merge_file_x9kVYp
     * @param Builder<Model> $query
     *                              =======
     *                              <<<<<<< HEAD
     * @param Builder<Model> $query
     *                              =======
     * @param Builder<Model> $query
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_an0rj9
     *
     * >>>>>>> .merge_file_PlLTO3
     *
     * =======
     * <<<<<<< HEAD
     * @param Builder<Model> $query
     *                              =======
     *                              <<<<<<< .merge_file_DMxry3
     *                              =======
     *                              <<<<<<< HEAD
     *                              <<<<<<< .merge_file_7zavuJ
     *                              >>>>>>> .merge_file_55oZz4
     * @param Builder<Model> $query
     *
     * <<<<<<< .merge_file_DMxry3
     * =======
     * >>>>>>> laraxot/dev
     * =======
     * @param Builder<Model> $query
     *                              >>>>>>> 9e11d472 (Fix merge conflicts in PHPDoc comments across multiple action classes and contracts, ensuring consistent parameter annotations and removing redundant lines.)
     *
     * <<<<<<< HEAD
     * =======
     * >>>>>>> .merge_file_55oZz4
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param Builder<Model> $query
     *                              >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_6BkZVO
>>>>>>> 77b1e2748 (first)
     * @return Builder<Model>
     */
    public function newEloquentBuilder(Builder $query): Builder;
}
