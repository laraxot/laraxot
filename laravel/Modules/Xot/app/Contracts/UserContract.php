<?php

declare(strict_types=1);

namespace Modules\Xot\Contracts;

use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use Laravel\Passport\Contracts\OAuthenticatable;
<<<<<<< HEAD
use Laravel\Passport\Contracts\ScopeAuthorizable;
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\Token;
=======
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\Token;
use Laravel\Passport\TransientToken;
>>>>>>> 77b1e2748 (first)
use Modules\User\Contracts\TeamContract;
use Modules\User\Models\Role as UserRole;
use Modules\User\Models\Team;
use Modules\User\Models\Tenant;
use Nwidart\Modules\Laravel\Module;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modules\Xot\Contracts\UserContract.
 *
<<<<<<< HEAD
=======
<<<<<<< .merge_file_9IpbkJ
>>>>>>> 77b1e2748 (first)
 * @property string|null $id
 * @property string|null $email
 * @property Carbon|null $email_verified_at
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $full_name
 * @property string|null $name
 * @property string|null $phone
 * @property string|null $type
 * @property string|null $current_team_id
 * @property TeamContract $currentTeam
 * @property ProfileContract|null $profile
 * @property Collection<int, UserRole> $roles
 * @property Collection<int, Team> $membershipTeams
 * @property Collection<int, Team> $teams
 * @property Collection<int, Tenant> $tenants
<<<<<<< HEAD
 *
 * @phpstan-require-extends Model
 *
 * @mixin Model
=======
=======
 * <<<<<<< HEAD
 *
 * @property string|null               $id
 * @property string|null               $email
 * @property Carbon|null               $email_verified_at
 * @property string|null               $first_name
 * @property string|null               $last_name
 * @property string|null               $full_name
 * @property string|null               $name
 * @property string|null               $phone
 * @property string|null               $type
 * @property string|null               $current_team_id
 * @property TeamContract              $currentTeam
 * @property ProfileContract|null      $profile
 * @property Collection<int, UserRole> $roles
 * @property Collection<int, Team>     $membershipTeams
 * @property Collection<int, Team>     $teams
 * @property Collection<int, Tenant>   $tenants
 *                                                        =======
 *                                                        <<<<<<< HEAD
 *                                                        =======
 *                                                        <<<<<<< HEAD
 *                                                        <<<<<<< HEAD
 *
 * >>>>>>> laraxot/dev
 * @property string|null               $id
 * @property string|null               $email
 * @property Carbon|null               $email_verified_at
 * @property string|null               $first_name
 * @property string|null               $last_name
 * @property string|null               $full_name
 * @property string|null               $name
 * @property string|null               $phone
 * @property string|null               $type
 * @property string|null               $current_team_id
 * @property TeamContract              $currentTeam
 * @property ProfileContract|null      $profile
 * @property Collection<int, UserRole> $roles
 * @property Collection<int, Team>     $membershipTeams
 * @property Collection<int, Team>     $teams
 * @property Collection<int, Tenant>   $tenants
 *                                                        <<<<<<< HEAD
 *                                                        =======
 *                                                        =======
 * @property string|null               $id
 * @property string|null               $email
 * @property Carbon|null               $email_verified_at
 * @property string|null               $first_name
 * @property string|null               $last_name
 * @property string|null               $full_name
 * @property string|null               $name
 * @property string|null               $phone
 * @property string|null               $type
 * @property string|null               $current_team_id
 * @property TeamContract              $currentTeam
 * @property ProfileContract|null      $profile
 * @property Collection<int, UserRole> $roles
 * @property Collection<int, Team>     $membershipTeams
 * @property Collection<int, Team>     $teams
 * @property Collection<int, Tenant>   $tenants
 *                                                        >>>>>>> laraxot/dev
 *                                                        =======
 * @property string|null               $id
 * @property string|null               $email
 * @property Carbon|null               $email_verified_at
 * @property string|null               $first_name
 * @property string|null               $last_name
 * @property string|null               $full_name
 * @property string|null               $name
 * @property string|null               $phone
 * @property string|null               $type
 * @property string|null               $current_team_id
 * @property TeamContract              $currentTeam
 * @property ProfileContract|null      $profile
 * @property Collection<int, UserRole> $roles
 * @property Collection<int, Team>     $membershipTeams
 * @property Collection<int, Team>     $teams
 * @property Collection<int, Tenant>   $tenants
 *                                                        >>>>>>> laraxot/dev
 *                                                        >>>>>>> laraxot/dev
 *                                                        >>>>>>> laraxot/dev
>>>>>>> .merge_file_gL9NsQ
 *
 * @phpstan-require-extends Model
 *
 * @mixin \Eloquent
>>>>>>> 77b1e2748 (first)
 */
interface UserContract extends Authenticatable, HasMedia, HasName, HasTenants, MustVerifyEmail, OAuthenticatable
{
    /*
     * public function isSuperAdmin();
     * public function name();
     * public function areas();
     * public function avatar();
     */
    /**
     * @return HasOne<Model&ProfileContract, Model&static>
     */
    public function profile(): HasOne;

    /**
     * Get the access token currently associated with the user.
<<<<<<< HEAD
     */
    public function token(): ?ScopeAuthorizable;
=======
     *
     * @return Token|TransientToken|null
     */
    public function token();
>>>>>>> 77b1e2748 (first)

    /**
     * Create a new personal access token for the user.
     *
<<<<<<< HEAD
     * @param  array<int, string>  $scopes
=======
<<<<<<< .merge_file_9IpbkJ
     * @param  array<int, string>  $scopes
=======
     * <<<<<<< HEAD
     *
     * @param array<int, string> $scopes
     *                                   =======
     *                                   <<<<<<< HEAD
     * @param array<int, string> $scopes
     *                                   =======
     *                                   <<<<<<< HEAD
     *                                   <<<<<<< HEAD
     * @param array<int, string> $scopes
     *                                   =======
     * @param array<int, string> $scopes
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param array<int, string> $scopes
     *                                   >>>>>>> laraxot/dev
     *                                   >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_gL9NsQ
>>>>>>> 77b1e2748 (first)
     * @return PersonalAccessTokenResult<Token>
     */
    public function createToken(string $name, array $scopes = []): PersonalAccessTokenResult;

    /**
     * Passport API tokens support.
     */
    /**
     * Determine if the model has (one of) the given role(s).
     */
    /**
<<<<<<< HEAD
     * @param  string|int|array<int|string>|UserRole|Collection<int, UserRole>  $roles
=======
<<<<<<< .merge_file_9IpbkJ
     * @param  string|int|array<int|string>|UserRole|Collection<int, UserRole>  $roles
=======
     * <<<<<<< HEAD.
     *
     * @param string|int|array<int|string>|UserRole|Collection<int, UserRole> $roles
     *                                                                               =======
     *                                                                               <<<<<<< HEAD
     * @param string|int|array<int|string>|UserRole|Collection<int, UserRole> $roles
     *                                                                               =======
     *                                                                               <<<<<<< HEAD
     *                                                                               <<<<<<< HEAD
     * @param string|int|array<int|string>|UserRole|Collection<int, UserRole> $roles
     *                                                                               =======
     * @param string|int|array<int|string>|UserRole|Collection<int, UserRole> $roles
     *                                                                               >>>>>>> laraxot/dev
     *                                                                               =======
     * @param string|int|array<int|string>|UserRole|Collection<int, UserRole> $roles
     *                                                                               >>>>>>> laraxot/dev
     *                                                                               >>>>>>> laraxot/dev
     *                                                                               >>>>>>> laraxot/dev
>>>>>>> .merge_file_gL9NsQ
>>>>>>> 77b1e2748 (first)
     */
    public function hasRole(
        string|int|array|UserRole|Collection $roles,
        ?string $guard = null,
    ): bool;

    /**
     * Assign the given role to the model.
     *
<<<<<<< HEAD
     * @param  array<int|string>|string|int|UserRole|Collection<int, UserRole>  $roles
=======
<<<<<<< .merge_file_9IpbkJ
     * @param  array<int|string>|string|int|UserRole|Collection<int, UserRole>  $roles
=======
     * <<<<<<< HEAD
     *
     * @param array<int|string>|string|int|UserRole|Collection<int, UserRole> $roles
     *                                                                               =======
     *                                                                               <<<<<<< HEAD
     * @param array<int|string>|string|int|UserRole|Collection<int, UserRole> $roles
     *                                                                               =======
     *                                                                               <<<<<<< HEAD
     *                                                                               <<<<<<< HEAD
     * @param array<int|string>|string|int|UserRole|Collection<int, UserRole> $roles
     *                                                                               =======
     * @param array<int|string>|string|int|UserRole|Collection<int, UserRole> $roles
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param array<int|string>|string|int|UserRole|Collection<int, UserRole> $roles
     *                                                                               >>>>>>> laraxot/dev
     *                                                                               >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_gL9NsQ
>>>>>>> 77b1e2748 (first)
     * @return $this
     */
    public function assignRole(array|string|int|UserRole|Collection $roles = []): static;

    /**
     * Remove all current roles and set the given ones.
     *
<<<<<<< HEAD
     * @param  array<int|string>|string|int|UserRole|Collection<int, UserRole>  $roles
=======
<<<<<<< .merge_file_9IpbkJ
     * @param  array<int|string>|string|int|UserRole|Collection<int, UserRole>  $roles
=======
     * <<<<<<< HEAD
     *
     * @param array<int|string>|string|int|UserRole|Collection<int, UserRole> $roles
     *                                                                               =======
     *                                                                               <<<<<<< HEAD
     * @param array<int|string>|string|int|UserRole|Collection<int, UserRole> $roles
     *                                                                               =======
     *                                                                               <<<<<<< HEAD
     *                                                                               <<<<<<< HEAD
     * @param array<int|string>|string|int|UserRole|Collection<int, UserRole> $roles
     *                                                                               =======
     * @param array<int|string>|string|int|UserRole|Collection<int, UserRole> $roles
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param array<int|string>|string|int|UserRole|Collection<int, UserRole> $roles
     *                                                                               >>>>>>> laraxot/dev
     *                                                                               >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_gL9NsQ
>>>>>>> 77b1e2748 (first)
     * @return $this
     */
    public function syncRoles(array|string|int|UserRole|Collection $roles = []): static;

    /**
     * Determine if the model has (one of) the given permission(s).
     *
     * @throws PermissionDoesNotExist
     */
    public function hasPermissionTo(string|int|Permission $permission, ?string $guardName = null): bool;

    /**
     * Come hasPermissionTo(), ma se il permesso non esiste ancora in DB lo
     * crea al volo invece di lasciare esplodere PermissionDoesNotExist.
     */
    public function hasPermissionToOrCreate(string $permission, ?string $guardName = null): bool;

    /**
     * Check if the user can access Socialite.
     */
    public function canAccessSocialite(): bool;

    /**
     * Get the user's roles.
     */
    /** @return BelongsToMany<Model, Model> */
    public function roles(): BelongsToMany;

    /**
     * Spatie Permission — team pivot for role scoping ({@see HasRoles::teams()}).
     *
     * @return BelongsToMany<Model, Model&static>
     */
    public function teams(): BelongsToMany;

    /**
     * Laraxot team membership (Jetstream-style pivot).
     *
     * @return BelongsToMany<Model&TeamContract, Model&static, Pivot, 'pivot'>
     */
    public function membershipTeams(): BelongsToMany;

    /**
     * Get the user's tenants.
     *
     * @return BelongsToMany<Model, Model&static>
     */
    public function tenants(): BelongsToMany;

    /**
     * Revoke the given role from the model.
     *
<<<<<<< HEAD
     * @param  string|int|array<int|string>|UserRole|Collection<int, UserRole>|\BackedEnum  ...$role
     * @return $this
     */
    public function removeRole(...$role): static;
=======
<<<<<<< .merge_file_9IpbkJ
     * @param  string|int|array<int|string>|UserRole|Collection<int, UserRole>|\BackedEnum  ...$role
=======
     * <<<<<<< HEAD
     *
     * @param string|int|array<int|string>|UserRole|Collection<int, UserRole>|\BackedEnum ...$role
     *                                                                                             =======
     *                                                                                             <<<<<<< HEAD
     * @param string|int|array<int|string>|UserRole|Collection<int, UserRole>|\BackedEnum ...$role
     *                                                                                             =======
     *                                                                                             <<<<<<< HEAD
     *                                                                                             <<<<<<< HEAD
     * @param string|int|array<int|string>|UserRole|Collection<int, UserRole>|\BackedEnum ...$role
     *                                                                                             =======
     * @param string|int|array<int|string>|UserRole|Collection<int, UserRole>|\BackedEnum ...$role
     *
     * >>>>>>> laraxot/dev
     *
     * =======
     * @param string|int|array<int|string>|UserRole|Collection<int, UserRole>|\BackedEnum ...$role
     *                                                                                             >>>>>>> laraxot/dev
     *                                                                                             >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_gL9NsQ
     * @return $this
     */
    public function removeRole(...$role);
>>>>>>> 77b1e2748 (first)

    /**
     * Determine if the user owns the given team.
     */
    public function ownsTeam(TeamContract $team): bool;

    /**
     * Determine if the user belongs to the given team.
     */
    public function belongsToTeam(TeamContract $team): bool;

    /**
     * Determine if the user has the given permission on the given team.
     */
    public function hasTeamPermission(TeamContract $team, string $permission): bool;

    /**
     * Switch the user's context to the given team.
     */
    public function switchTeam(TeamContract $team): bool;

    /**
     * @return array<string, Module>
     */
    public function getModules(): array;

    /**
     * Find the user instance for the given username (Passport).
     */
    public static function findForPassport(string $username): ?self;

    /**
     * Validate the password of the user for the given password (Passport).
     */
    public function validateForPassportPasswordGrant(string $password): bool;
}
