<?php

declare(strict_types=1);

namespace Modules\User\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\User\Models\TeamInvitation;
use Modules\User\Models\TeamUser;
use Modules\Xot\Contracts\ModelContract;
=======
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
>>>>>>> f548be94 (.)
=======
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
=======
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\User\Models\TeamInvitation;
use Modules\User\Models\TeamUser;
use Modules\Xot\Contracts\ModelContract;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Contracts\UserContract;

/**
 * Modules\User\Contracts\TeamContract.
 *
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int               $id
 * @property int               $user_id
 * @property string            $name
 * @property int               $personal_team
 * @property Carbon|null       $created_at
 * @property Carbon|null       $updated_at
 * @property string            $role
 * @property UserContract|null $owner
 * @property int|null          $team_invitations_count
 * @property int|null          $users_count
 *
 * @method static Builder<Model> newModelQuery()
 * @method static Builder<Model> newQuery()
 * @method static Builder<Model> query()
 * @method static Builder<Model> whereCreatedAt($value)
 * @method static Builder<Model> whereId($value)
 * @method static Builder<Model> whereName($value)
 * @method static Builder<Model> wherePersonalTeam($value)
 * @method static Builder<Model> whereUpdatedAt($value)
 * @method static Builder<Model> whereUserId($value)
=======
=======
>>>>>>> 87273113 (.)
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $personal_team
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $role
 * @property UserContract|null $owner
 * @property int|null $team_invitations_count
 * @property int|null $users_count
 *
 * @method static Builder|TeamContract newModelQuery()
 * @method static Builder|TeamContract newQuery()
 * @method static Builder|TeamContract query()
 * @method static Builder|TeamContract whereCreatedAt($value)
 * @method static Builder|TeamContract whereId($value)
 * @method static Builder|TeamContract whereName($value)
 * @method static Builder|TeamContract wherePersonalTeam($value)
 * @method static Builder|TeamContract whereUpdatedAt($value)
 * @method static Builder|TeamContract whereUserId($value)
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property int               $id
 * @property int               $user_id
 * @property string            $name
 * @property int               $personal_team
 * @property Carbon|null       $created_at
 * @property Carbon|null       $updated_at
 * @property string            $role
 * @property UserContract|null $owner
 * @property int|null          $team_invitations_count
 * @property int|null          $users_count
 *
 * @method static Builder<Model> newModelQuery()
 * @method static Builder<Model> newQuery()
 * @method static Builder<Model> query()
 * @method static Builder<Model> whereCreatedAt($value)
 * @method static Builder<Model> whereId($value)
 * @method static Builder<Model> whereName($value)
 * @method static Builder<Model> wherePersonalTeam($value)
 * @method static Builder<Model> whereUpdatedAt($value)
 * @method static Builder<Model> whereUserId($value)
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 *
 * @phpstan-require-extends Model
 *
 * @mixin \Eloquent
 */
interface TeamContract extends ModelContract
{
    /**
     * Get the owner of the team.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return BelongsTo<Model&UserContract, Model>
=======
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return BelongsTo<Model&UserContract, Model>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function owner(): BelongsTo;

    /**
     * Get all of the team's users including its owner.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return Collection<int, Model&UserContract>
=======
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return Collection<int, Model&UserContract>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function allUsers(): Collection;

    /**
     * Get all of the users that belong to the team.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return BelongsToMany<Model&UserContract, Model, TeamUser, 'pivot'>
=======
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return BelongsToMany<Model&UserContract, Model, TeamUser, 'pivot'>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function users(): BelongsToMany;

    /**
     * Determine if the given user belongs to the team.
     */
    public function hasUser(UserContract $userContract): bool;

    /**
     * Determine if the given email address belongs to a user on the team.
     */
    public function hasUserWithEmail(string $email): bool;

    /**
     * Determine if the given user has the given permission on the team.
     */
    public function userHasPermission(UserContract $userContract, string $permission): bool;

    /**
     * Get all of the pending user invitations for the team.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return HasMany<TeamInvitation, Model>
=======
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return HasMany<TeamInvitation, Model>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function teamInvitations(): HasMany;

    /**
     * Remove the given user from the team.
     */
    public function removeUser(UserContract $userContract): void;

    /**
     * Purge all of the team's resources.
     */
    public function purge(): void;

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return BelongsToMany<Model&UserContract, Model, TeamUser, 'pivot'>
     */
=======
=======
>>>>>>> 87273113 (.)
    /* --non qui
     * Get the disk that profile photos should be stored on.
     *
     * public function profilePhotoDisk(): string;
     */

    /**
     * Reload a fresh model instance from the database.
     *
     * @param  array|string $with
     * @return static|null
     */
    public function fresh($with = []);

<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return BelongsToMany<Model&UserContract, Model, TeamUser, 'pivot'>
     */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function members(): BelongsToMany;
}
