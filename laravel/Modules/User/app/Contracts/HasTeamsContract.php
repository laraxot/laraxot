<?php

/**
 * --- Artmin.
 */

declare(strict_types=1);

namespace Modules\User\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
<<<<<<< HEAD
<<<<<<< HEAD
use Laravel\Passport\Token;
=======
>>>>>>> f548be94 (.)
=======
=======
use Laravel\Passport\Token;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Models\Role;

/**
 * Modules\User\Contracts\HasTeamsContract.
 *
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int                    $id
 * @property string                 $name
 * @property string                 $two_factor_secret
 * @property TeamContract|null      $currentTeam
 * @property Collection<int, Token> $tokens
 * @property Carbon|null            $two_factor_confirmed_at
 * @property int                    $current_team_id
=======
=======
>>>>>>> 87273113 (.)
 * @property int $id
 * @property string $name
 * @property string $two_factor_secret
 * @property TeamContract|null $currentTeam
 * @property Collection $tokens
 * @property Carbon|null $two_factor_confirmed_at
 * @property int $current_team_id
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property int                    $id
 * @property string                 $name
 * @property string                 $two_factor_secret
 * @property TeamContract|null      $currentTeam
 * @property Collection<int, Token> $tokens
 * @property Carbon|null            $two_factor_confirmed_at
 * @property int                    $current_team_id
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 *
 * @phpstan-require-extends Model
 *
 * @mixin \Eloquent
 */
interface HasTeamsContract
{
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
    // extends
    // HasApiTokens, //no sanctum ma passport
    // PassportHasApiTokensContract,
    // HasProfilePhotoContract,
    // TwoFactorAuthenticatableContract,
    // MustVerifyEmail,
    // CanResetPassword,
    // ModelContract
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    /**
     * Determine if the given team is the current team.
     */
    public function isCurrentTeam(TeamContract $teamContract): bool;

    /**
     * Get the current team of the user's context.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return BelongsTo<Model, Model>
=======
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return BelongsTo<Model, Model>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function currentTeam(): BelongsTo;

    /**
     * Switch the user's context to the given team.
     */
    public function switchTeam(TeamContract $teamContract): bool;

    /**
     * Get all of the teams the user owns or belongs to.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return \Illuminate\Support\Collection<int, Model>
=======
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return \Illuminate\Support\Collection<int, Model>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function allTeams(): \Illuminate\Support\Collection;

    /**
     * Get all of the teams the user owns.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return HasMany<Model, Model>
=======
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return HasMany<Model, Model>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function ownedTeams(): HasMany;

    /**
     * Get all of the teams the user belongs to.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return BelongsToMany<Model, Model>
=======
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return BelongsToMany<Model, Model>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function teams(): BelongsToMany;

    /**
     * Get the user's "personal" team.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function personalTeam(): ?TeamContract;
=======
    public function personalTeam(): null|TeamContract;
>>>>>>> f548be94 (.)
=======
    public function personalTeam(): null|TeamContract;
=======
    public function personalTeam(): ?TeamContract;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Determine if the user owns the given team.
     */
    public function ownsTeam(TeamContract $teamContract): bool;

    /**
     * Determine if the user belongs to the given team.
     */
    public function belongsToTeam(TeamContract $teamContract): bool;

    /**
     * Get the role that the user has on the team.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function teamRole(TeamContract $teamContract): ?Role;
=======
    public function teamRole(TeamContract $teamContract): null|Role;
>>>>>>> f548be94 (.)
=======
    public function teamRole(TeamContract $teamContract): null|Role;
=======
    public function teamRole(TeamContract $teamContract): ?Role;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Determine if the user has the given role on the given team.
     */
    public function hasTeamRole(TeamContract $teamContract, string $role): bool;

    /**
     * Get the user's permissions for the given team.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return array<string>
=======
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return array<string>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function teamPermissions(TeamContract $teamContract): array;

    /**
     * Determine if the user has the given permission on the given team.
     */
    public function hasTeamPermission(TeamContract $teamContract, string $permission): bool;
}
