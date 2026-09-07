<?php

declare(strict_types=1);

namespace Modules\User\Models\Traits;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\Pivot;
>>>>>>> f548be94 (.)
=======
use Illuminate\Database\Eloquent\Relations\Pivot;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;
use Modules\User\Contracts\TeamContract;
use Modules\User\Models\Role;
use Modules\User\Models\TeamUser;
use Modules\User\Models\User;
use Modules\Xot\Contracts\UserContract as XotUserContract;
use Modules\Xot\Datas\XotData;
use Spatie\Permission\Models\Permission;

/**
 * Trait HasTeams.
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\User\Contracts\HasTeamsContract;
use Modules\User\Contracts\TeamContract;
use Modules\User\Models\Membership;
use Modules\User\Models\Role;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Webmozart\Assert\Assert;

/**
 * Trait HasTeams
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;
use Modules\User\Contracts\TeamContract;
use Modules\User\Models\Role;
use Modules\User\Models\TeamUser;
use Modules\User\Models\User;
use Modules\Xot\Contracts\UserContract as XotUserContract;
use Modules\Xot\Datas\XotData;
use Spatie\Permission\Models\Permission;

/**
 * Trait HasTeams.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 *
 * Provides team functionality for User models implementing team-based organization.
 * This trait handles team ownership, membership, permissions, and relationships.
 *
 * @property TeamContract $currentTeam
 * @property int|null $current_team_id
<<<<<<< HEAD
<<<<<<< HEAD
 * @property Collection<int, TeamContract> $membershipTeams
 * @property Collection<int, TeamContract> $ownedTeams
 * @property Collection<int, TeamUser> $teamUsers
 * @property XotUserContract|null $owner
=======
=======
>>>>>>> 87273113 (.)
 * @property Collection<int, TeamContract> $teams
 * @property Collection<int, TeamContract> $ownedTeams
 * @property Collection<int, UserContract> $teamUsers
 * @property UserContract|null $owner
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property Collection<int, TeamContract> $membershipTeams
 * @property Collection<int, TeamContract> $ownedTeams
 * @property Collection<int, TeamUser> $teamUsers
 * @property XotUserContract|null $owner
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 */
trait HasTeams
{
    /**
     * Add a user to the team.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function addTeamMember(Model $user, ?Model $role = null): Model
=======
=======
>>>>>>> 87273113 (.)
     *
     * @param Model $user
     * @param Model|null $role
     * @return Model
     */
    public function addTeamMember($user, $role = null)
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function addTeamMember(Model $user, ?Model $role = null): Model
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        $teamUser = $this->teamUsers()->create([
            'user_id' => $user->getKey(),
            'role_id' => $role ? $role->getKey() : null,
        ]);

        $this->increment('total_members');

        return $teamUser;
    }

    /**
     * Get all teams the user belongs to.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return Collection<int, TeamContract>
     */
    public function allTeams(): Collection
    {
        /** @var Collection<int, TeamContract> $teams */
        $teams = $this->ownedTeams->merge($this->membershipTeams)->sortBy('name');

        return $teams;
=======
=======
>>>>>>> 87273113 (.)
     * @return Collection<TeamContract>
     */
    public function allTeams(): Collection
    {
        return $this->ownedTeams->merge($this->teams)->sortBy('name');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return Collection<int, TeamContract>
     */
    public function allTeams(): Collection
    {
        /** @var Collection<int, TeamContract> $teams */
        $teams = $this->ownedTeams->merge($this->membershipTeams)->sortBy('name');

        return $teams;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Check if the user belongs to any teams.
     */
    public function belongsToTeams(): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return $this->allTeams()->isNotEmpty();
=======
        return true;
>>>>>>> f548be94 (.)
=======
        return true;
=======
        return $this->allTeams()->isNotEmpty();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Check if the user belongs to a specific team.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function belongsToTeam(?TeamContract $team): bool
    {
        if ($team === null) {
            return false;
        }

        return $this->ownsTeam($team) || $this->membershipTeams->contains('id', (string) $team->id);
=======
=======
>>>>>>> 87273113 (.)
    public function belongsToTeam(TeamContract $team): bool
    {
        $found = $this->teams()->where('teams.id', $team->id)->first();
        if ($found === null) {
            return false;
        }
        Assert::isInstanceOf($found, TeamContract::class, 'Team must implement TeamContract.');
        return true;
    }

    /**
     * Boot the HasTeams trait.
     *
     * @return void
     */
    protected static function bootHasTeams()
    {
        /*
         * static::deleting(function ($team) {
         * $team->teamUsers()->delete();
         * $team->teamInvitations()->delete();
         * });
         */
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function belongsToTeam(?TeamContract $team): bool
    {
        if ($team === null) {
            return false;
        }

        return $this->ownsTeam($team) || $this->membershipTeams->contains('id', (string) $team->id);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Check if the user can add a member to a team.
     */
    public function canAddTeamMember(TeamContract $team): bool
    {
        return $this->ownsTeam($team) || $this->hasTeamPermission($team, 'add team member');
    }

    /**
     * Check if the user can create a team.
     */
    public function canCreateTeam(): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return $this->hasPermissionTo('create team'); // @phpstan-ignore method.notFound, return.type
=======
        return $this->hasPermissionTo('create team');
>>>>>>> f548be94 (.)
=======
        return $this->hasPermissionTo('create team');
=======
        return $this->hasPermissionTo('create team'); // @phpstan-ignore method.notFound, return.type
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Check if the user can delete a team.
     */
    public function canDeleteTeam(TeamContract $team): bool
    {
        return $this->ownsTeam($team);
    }

    /**
     * Check if the user can leave a team.
     */
    public function canLeaveTeam(TeamContract $team): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return $this->belongsToTeam($team) && ! $this->ownsTeam($team);
=======
        return $this->belongsToTeam($team) && !$this->ownsTeam($team);
>>>>>>> f548be94 (.)
=======
        return $this->belongsToTeam($team) && !$this->ownsTeam($team);
=======
        return $this->belongsToTeam($team) && ! $this->ownsTeam($team);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Check if the user can manage a team.
     */
    public function canManageTeam(TeamContract $team): bool
    {
        return $this->ownsTeam($team);
    }

    /**
     * Check if the user can remove a member from a team.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function canRemoveTeamMember(TeamContract $team, XotUserContract $_user): bool
=======
    public function canRemoveTeamMember(TeamContract $team, UserContract $_user): bool
>>>>>>> f548be94 (.)
=======
    public function canRemoveTeamMember(TeamContract $team, UserContract $_user): bool
=======
    public function canRemoveTeamMember(TeamContract $team, XotUserContract $_user): bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        return $this->ownsTeam($team) || $this->hasTeamPermission($team, 'remove team member');
    }

    /**
     * Check if the user can update a team.
     */
    public function canUpdateTeam(TeamContract $team): bool
    {
        return $this->ownsTeam($team) || $this->hasTeamPermission($team, 'update team');
    }

    /**
     * Check if the user can update a team member.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function canUpdateTeamMember(TeamContract $team, XotUserContract $_user): bool
=======
    public function canUpdateTeamMember(TeamContract $team, UserContract $_user): bool
>>>>>>> f548be94 (.)
=======
    public function canUpdateTeamMember(TeamContract $team, UserContract $_user): bool
=======
    public function canUpdateTeamMember(TeamContract $team, XotUserContract $_user): bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        return $this->ownsTeam($team) || $this->hasTeamPermission($team, 'update team member');
    }

    /**
     * Check if the user can view a team.
     */
    public function canViewTeam(TeamContract $team): bool
    {
        return $this->belongsToTeam($team) || $this->hasTeamPermission($team, 'view team');
    }

    /**
     * Get all of the team's users including its owner.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return Collection<int, User>
     */
    public function getAllTeamUsersAttribute(): Collection
    {
        // teamUsers are Membership objects, we need to extract the User models
        /** @var Collection<int, User> $users */
        $users = $this->teamUsers->map(static function (TeamUser $membership): ?User {
            // Membership always extends Model, check only if user attribute exists
            return $membership->user;
        })->filter();

        $owner = $this->owner;
        if ($owner !== null && $owner instanceof User) {
            return $users->merge([$owner]);
        }

        return $users;
    }

    /**
     * Get all of the team's users including its owner.
     *
     * @return Collection<int, User>
     */
    public function allTeamUsers(): Collection // @phpstan-ignore return.type
    {/** @var Collection<int, mixed> $teams */
            $teams = $this->membershipTeams; // @phpstan-ignore property.nonObject
        /** @var Collection<int, User> $result */
        $result = $teams->flatMap( // @phpstan-ignore argument.type
            /** @param mixed $team @return array<int,User>|Collection<int,User> */
            static function (mixed $team): array { // @phpstan-ignore return.type
                /** @var array<int,User> $users */
                $users = (array) ($team->users ?? []); // @phpstan-ignore property.nonObject

                return $users;
            })->unique('id');

        return $result;
=======
=======
>>>>>>> 87273113 (.)
     * @return Collection<int, UserContract>
     */
    public function getAllTeamUsersAttribute(): Collection
    {
        $owner = $this->owner;
        if ($owner === null) {
            return $this->teamUsers;
        }
        return $this->teamUsers->merge([$owner]);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return Collection<int, User>
     */
    public function getAllTeamUsersAttribute(): Collection
    {
        // teamUsers are Membership objects, we need to extract the User models
        /** @var Collection<int, User> $users */
        $users = $this->teamUsers->map(static function (TeamUser $membership): ?User {
            // Membership always extends Model, check only if user attribute exists
            return $membership->user;
        })->filter();

        $owner = $this->owner;
        if ($owner !== null && $owner instanceof User) {
            return $users->merge([$owner]);
        }

        return $users;
    }

    /**
     * Get all of the team's users including its owner.
     *
     * @return Collection<int, User>
     */
    public function allTeamUsers(): Collection // @phpstan-ignore return.type
    {/** @var Collection<int, mixed> $teams */
            $teams = $this->membershipTeams; // @phpstan-ignore property.nonObject
        /** @var Collection<int, User> $result */
        $result = $teams->flatMap( // @phpstan-ignore argument.type
            /** @param mixed $team @return array<int,User>|Collection<int,User> */
            static function (mixed $team): array { // @phpstan-ignore return.type
                /** @var array<int,User> $users */
                $users = (array) ($team->users ?? []); // @phpstan-ignore property.nonObject

                return $users;
            })->unique('id');

        return $result;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Determine if the given user is on the team.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function hasTeamMember(XotUserContract $user): bool
    {
        // Check if user is in teamUsers (checking by key since Membership != UserContract)
        $userFound = $this->teamUsers->first(static function (TeamUser $membership) use ($user): bool {
            // Membership always extends Model
            $memberUser = $membership->user;
            if ($memberUser instanceof Model) {
                $memberUserKey = $memberUser->getKey();

                return $memberUserKey !== null && $memberUserKey === $user->getKey();
            }

            return false;
        });

        if ($userFound !== null) {
=======
=======
>>>>>>> 87273113 (.)
     *
     * @param UserContract $user
     * @return bool
     */
    public function hasTeamMember(UserContract $user): bool
    {
        if ($this->teamUsers->contains($user)) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function hasTeamMember(XotUserContract $user): bool
    {
        // Check if user is in teamUsers (checking by key since Membership != UserContract)
        $userFound = $this->teamUsers->first(static function (TeamUser $membership) use ($user): bool {
            // Membership always extends Model
            $memberUser = $membership->user;
            if ($memberUser instanceof Model) {
                $memberUserKey = $memberUser->getKey();

                return $memberUserKey !== null && $memberUserKey === $user->getKey();
            }

            return false;
        });

        if ($userFound !== null) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return true;
        }

        // Check if user can own this team (UserContract sempre ha il metodo ownsTeam)
        if ($this instanceof TeamContract) {
            return $user->ownsTeam($this);
        }

        return false;
    }

    /**
     * Check if the user has teams.
     */
    public function hasTeams(): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return $this->allTeams()->isNotEmpty();
=======
        return true;
>>>>>>> f548be94 (.)
=======
        return true;
=======
        return $this->allTeams()->isNotEmpty();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Check if the user has a specific permission in a team.
     */
    public function hasTeamPermission(TeamContract $team, string $permission): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return $this->ownsTeam($team) || \in_array($permission, $this->teamPermissions($team), true);
=======
        return $this->ownsTeam($team) || in_array($permission, $this->teamPermissions($team), strict: true);
>>>>>>> f548be94 (.)
=======
        return $this->ownsTeam($team) || in_array($permission, $this->teamPermissions($team), strict: true);
=======
        return $this->ownsTeam($team) || \in_array($permission, $this->teamPermissions($team), true);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Check if the user has a specific role in a team.
     */
    public function hasTeamRole(TeamContract $team, string $role): bool
    {
        if ($this->ownsTeam($team)) {
            return true;
        }

        $teamRole = $this->teamRole($team);
<<<<<<< HEAD
<<<<<<< HEAD

        return $teamRole !== null && $teamRole->name === $role;
    }

    /**
     * Get the role name for a specific team.
     */
    public function teamRoleName(TeamContract $team): string
    {
        $role = $this->teamRole($team);

        if ($role === null) {
            return 'Unknown';
        }

        return $role->name;
=======
        return $teamRole !== null && isset($teamRole->name) && $teamRole->name === $role;
>>>>>>> f548be94 (.)
=======
        return $teamRole !== null && isset($teamRole->name) && $teamRole->name === $role;
=======

        return $teamRole !== null && $teamRole->name === $role;
    }

    /**
     * Get the role name for a specific team.
     */
    public function teamRoleName(TeamContract $team): string
    {
        $role = $this->teamRole($team);

        if ($role === null) {
            return 'Unknown';
        }

        return $role->name;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get the current team of the user's context.
     *
     * @return BelongsTo<Model&TeamContract, $this>
     */
    public function currentTeam(): BelongsTo
    {
        $xot = XotData::make();
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
        if ($this->current_team_id === null && $this->id) {
            $this->switchTeam($this->personalTeam());
        }

        if ($this->allTeams()->isEmpty() && $this->getKey() !== null) {
            $this->current_team_id = null;
            $this->save();
        }

<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $teamClass = $xot->getTeamClass();

        return $this->belongsTo($teamClass, 'current_team_id');
    }

    /**
     * Get the teams owned by the user.
     *
     * @return HasMany<Model&TeamContract, $this>
     */
    public function ownedTeams(): HasMany
    {
        $xot = XotData::make();
        $teamClass = $xot->getTeamClass();

        return $this->hasMany($teamClass, 'user_id');
    }

    /**
     * Get all team users.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return HasMany<TeamUser, $this>
     */
    public function teamUsers(): HasMany
    {
        return $this->hasMany(TeamUser::class, 'user_id');
=======
=======
>>>>>>> 87273113 (.)
     * @return HasMany<Membership, $this>
     */
    public function teamUsers(): HasMany
    {
        /** @var HasMany<Membership, $this> $relation */
        $relation = $this->hasMany(Membership::class, 'user_id');
        return $relation;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return HasMany<TeamUser, $this>
     */
    public function teamUsers(): HasMany
    {
        return $this->hasMany(TeamUser::class, 'user_id');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get the role for a specific team.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function teamRole(TeamContract $team): ?Role
    {
        if ($this->ownsTeam($team)) {
            return Role::where('name', 'owner')->first() ?? new Role(['name' => 'owner']);
        }

=======
    public function teamRole(TeamContract $team): null|Role
    {
>>>>>>> f548be94 (.)
=======
    public function teamRole(TeamContract $team): null|Role
    {
=======
    public function teamRole(TeamContract $team): ?Role
    {
        if ($this->ownsTeam($team)) {
            return Role::where('name', 'owner')->first() ?? new Role(['name' => 'owner']);
        }

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        /** @var Model|Pivot|null $teamUser */
        $teamUser = $this->teamUsers()->where('team_id', $team->id)->first();

        if ($teamUser === null) {
            return null;
        }

<<<<<<< HEAD
<<<<<<< HEAD
        $roleValue = $teamUser->getAttribute('role');

        if ($roleValue instanceof Role) {
            return $roleValue;
        }

        if (is_string($roleValue)) {
            return Role::where('name', $roleValue)->first() ?? new Role(['name' => $roleValue]);
        }

        return null;
=======
=======
>>>>>>> 87273113 (.)
        // Accesso sicuro alla proprietà role usando getAttribute
        $role = $teamUser->getAttribute('role');

        return ($role instanceof Role) ? $role : null;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $roleValue = $teamUser->getAttribute('role');

        if ($roleValue instanceof Role) {
            return $roleValue;
        }

        if (is_string($roleValue)) {
            return Role::where('name', $roleValue)->first() ?? new Role(['name' => $roleValue]);
        }

        return null;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get permissions for a specific team.
     *
<<<<<<< HEAD
<<<<<<< HEAD
=======
     * @param TeamContract $team
>>>>>>> f548be94 (.)
=======
     * @param TeamContract $team
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     * @return array<int, string>
     */
    public function teamPermissions(TeamContract $team): array
    {
<<<<<<< HEAD
<<<<<<< HEAD
        /** @var array<int, string> $permissions */
        $permissions = [];

        // Permissions from Role
        $role = $this->teamRole($team);
        if ($role !== null && $role->permissions) {
            /** @var \Illuminate\Database\Eloquent\Collection<int, Permission> $permissionsCollection */
            $permissionsCollection = $role->permissions;
            /** @var array<string> $rolePermissionNames */
            $rolePermissionNames = $permissionsCollection->pluck('name')->toArray();

            $permissions = array_values(array_filter(
                $rolePermissionNames,
                static fn (string $value): bool => $value !== ''
            ));
        }

        // Permissions from Pivot
        /** @var Model|Pivot|null $teamUser */
        $teamUser = $this->teamUsers()->where('team_id', (string) $team->id)->first();
        if ($teamUser !== null) {
            $pivotPermissions = $teamUser->getAttribute('permissions');
            if (is_array($pivotPermissions)) {
                $pivotPermissionNames = array_keys(array_filter($pivotPermissions));

                $permissions = array_merge(
                    $permissions,
                    array_values(array_filter(
                        $pivotPermissionNames,
                        static fn (string $value): bool => $value !== ''
                    ))
                );
            }
        }

        /** @var array<int, string> $result */
        $result = array_values(array_unique($permissions));

        return $result;
=======
=======
>>>>>>> 87273113 (.)
        $role = $this->teamRole($team);

        if ($role === null || !$role->permissions) {
            return [];
        }

        /** @var array<int, string> */
        return $role->permissions->pluck('name')->values()->toArray();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        /** @var array<int, string> $permissions */
        $permissions = [];

        // Permissions from Role
        $role = $this->teamRole($team);
        if ($role !== null && $role->permissions) {
            /** @var \Illuminate\Database\Eloquent\Collection<int, Permission> $permissionsCollection */
            $permissionsCollection = $role->permissions;
            /** @var array<string> $rolePermissionNames */
            $rolePermissionNames = $permissionsCollection->pluck('name')->toArray();

            $permissions = array_values(array_filter(
                $rolePermissionNames,
                static fn (string $value): bool => $value !== ''
            ));
        }

        // Permissions from Pivot
        /** @var Model|Pivot|null $teamUser */
        $teamUser = $this->teamUsers()->where('team_id', (string) $team->id)->first();
        if ($teamUser !== null) {
            $pivotPermissions = $teamUser->getAttribute('permissions');
            if (is_array($pivotPermissions)) {
                $pivotPermissionNames = array_keys(array_filter($pivotPermissions));

                $permissions = array_merge(
                    $permissions,
                    array_values(array_filter(
                        $pivotPermissionNames,
                        static fn (string $value): bool => $value !== ''
                    ))
                );
            }
        }

        /** @var array<int, string> $result */
        $result = array_values(array_unique($permissions));

        return $result;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Remove a user from the team.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function removeTeamMember(Model $user): void
=======
=======
>>>>>>> 87273113 (.)
     *
     * @param Model $user
     * @return void
     */
    public function removeTeamMember($user)
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function removeTeamMember(Model $user): void
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        $this->teamUsers()->where('user_id', $user->getKey())->delete();

        $this->decrement('total_members');
    }

    /**
     * Get the user's personal team.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function personalTeam(): ?TeamContract
    {
        /* @var TeamContract|null */
        return $this->ownedTeams->where('personal_team', true)->first();
    }

    /**
     * Initialize the user's current team.
     */
    public function initializeCurrentTeam(): void
    {
        if ($this->current_team_id !== null) {
            return;
        }

        $team = $this->personalTeam();
        if ($team === null) {
            $teamCandidate = $this->allTeams()->first();
            if ($teamCandidate instanceof TeamContract) {
                $team = $teamCandidate;
            }
        }

        if ($team !== null) {
            $this->switchTeam($team);
        }
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return TeamContract|null
     */
    public function personalTeam(): null|TeamContract
    {
        /** @var TeamContract|null */
        $personalTeam = $this->ownedTeams->where('personal_team', true)->first();

        return $personalTeam;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function personalTeam(): ?TeamContract
    {
        /* @var TeamContract|null */
        return $this->ownedTeams->where('personal_team', true)->first();
    }

    /**
     * Initialize the user's current team.
     */
    public function initializeCurrentTeam(): void
    {
        if ($this->current_team_id !== null) {
            return;
        }

        $team = $this->personalTeam();
        if ($team === null) {
            $teamCandidate = $this->allTeams()->first();
            if ($teamCandidate instanceof TeamContract) {
                $team = $teamCandidate;
            }
        }

        if ($team !== null) {
            $this->switchTeam($team);
        }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Switch the user's context to the given team.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function switchTeam(TeamContract $team): bool
    {
        if (! $this->belongsToTeam($team)) {
            return false;
        }

        $this->forceFill([
            'current_team_id' => $team->id,
        ]);

        return $this->save();
=======
=======
>>>>>>> 87273113 (.)
     *
     * @param TeamContract $team
     */
    public function switchTeam(null|TeamContract $team): bool
    {
        if ($team === null) {
            return false;
        }

        if (!$this->belongsToTeam($team)) {
            return false;
        }

        $this->current_team_id = (string) $team->id;
        $this->save();

        return true;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function switchTeam(TeamContract $team): bool
    {
        if (! $this->belongsToTeam($team)) {
            return false;
        }

        $this->forceFill([
            'current_team_id' => $team->id,
        ]);

        return $this->save();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Determine if the given team is the current team.
     */
    public function isCurrentTeam(TeamContract $team): bool
    {
        if ($this->currentTeam === null) {
            return false;
        }

        return $team->getKey() === $this->currentTeam->getKey();
    }

    /**
     * Determine if the user owns the given team.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    public function ownsTeam(?TeamContract $team): bool
    {
        if ($team === null) {
            return false;
        }

        return $this->id === $team->user_id;
    }

    /**
     * Laraxot team membership (Jetstream-style pivot).
     * Su {@see BaseUser} esposto come {@see membershipTeams()} — {@see HasRoles::teams()} resta Spatie.
     *
     * @return BelongsToMany<Model&TeamContract, Model&static, Pivot, 'pivot'>
=======
=======
>>>>>>> 87273113 (.)
     *
     * @param TeamContract $team
     */
    public function ownsTeam(TeamContract $team): bool
    {
        /** @var ?Model $found */
        $found = $this->ownedTeams()->where('teams.id', $team->id)->first();

        return $found !== null;
    }

    /**
     * Get all of the teams the user belongs to.
     *
     * @return BelongsToMany<Model&TeamContract, Model>
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    public function ownsTeam(?TeamContract $team): bool
    {
        if ($team === null) {
            return false;
        }

        return $this->id === $team->user_id;
    }

    /**
     * Laraxot team membership (Jetstream-style pivot).
     * Su {@see BaseUser} esposto come {@see membershipTeams()} — {@see HasRoles::teams()} resta Spatie.
     *
     * @return BelongsToMany<Model&TeamContract, Model&static, Pivot, 'pivot'>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function teams(): BelongsToMany
    {
        $xot = XotData::make();
        $teamClass = $xot->getTeamClass();

<<<<<<< HEAD
<<<<<<< HEAD
        /** @var BelongsToMany<Model&TeamContract, Model&static, Pivot, 'pivot'> $relation */
        $relation = $this->belongsToManyX($teamClass);
=======
        /** @var BelongsToMany<Model&TeamContract, Model> $relation */
        $relation = $this->belongsToMany($teamClass, 'team_user', 'user_id', 'team_id')->using(Membership::class);
>>>>>>> f548be94 (.)
=======
        /** @var BelongsToMany<Model&TeamContract, Model> $relation */
        $relation = $this->belongsToMany($teamClass, 'team_user', 'user_id', 'team_id')->using(Membership::class);
=======
        /** @var BelongsToMany<Model&TeamContract, Model&static, Pivot, 'pivot'> $relation */
        $relation = $this->belongsToManyX($teamClass);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        return $relation;
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * Get all of the teams that the user owns.
     */
    public function inviteToTeam(XotUserContract $user, TeamContract $team): bool
=======
     * Invite a user to a team.
     */
    public function inviteToTeam(UserContract $user, TeamContract $team): bool
>>>>>>> f548be94 (.)
=======
     * Invite a user to a team.
     */
    public function inviteToTeam(UserContract $user, TeamContract $team): bool
=======
     * Get all of the teams that the user owns.
     */
    public function inviteToTeam(XotUserContract $user, TeamContract $team): bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        if ($this->ownsTeam($team)) {
            $team->members()->attach($user->id, ['role' => 'member']);

            return true;
        }

        return false;
    }

    /**
     * Remove a user from the team.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function removeFromTeam(XotUserContract $user, TeamContract $team): bool
=======
    public function removeFromTeam(UserContract $user, TeamContract $team): bool
>>>>>>> f548be94 (.)
=======
    public function removeFromTeam(UserContract $user, TeamContract $team): bool
=======
    public function removeFromTeam(XotUserContract $user, TeamContract $team): bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        if ($this->ownsTeam($team)) {
            $team->members()->detach($user->id);

            return true;
        }

        return false;
    }

    /**
     * Check if the user is an owner or a member.
     */
    public function isOwnerOrMember(TeamContract $team): bool
    {
        return $this->ownsTeam($team) || $this->belongsToTeam($team);
    }

    /**
     * Promote a member to team admin.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function promoteToAdmin(XotUserContract $user, TeamContract $team): bool
=======
    public function promoteToAdmin(UserContract $user, TeamContract $team): bool
>>>>>>> f548be94 (.)
=======
    public function promoteToAdmin(UserContract $user, TeamContract $team): bool
=======
    public function promoteToAdmin(XotUserContract $user, TeamContract $team): bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        if ($this->ownsTeam($team)) {
            $team->members()->updateExistingPivot($user->id, ['role' => 'admin']);

            return true;
        }

        return false;
    }

    /**
     * Demote a member from team admin.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function demoteFromAdmin(XotUserContract $user, TeamContract $team): bool
=======
    public function demoteFromAdmin(UserContract $user, TeamContract $team): bool
>>>>>>> f548be94 (.)
=======
    public function demoteFromAdmin(UserContract $user, TeamContract $team): bool
=======
    public function demoteFromAdmin(XotUserContract $user, TeamContract $team): bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        if ($this->ownsTeam($team)) {
            $team->members()->updateExistingPivot($user->id, ['role' => 'member']);

            return true;
        }

        return false;
    }

    /**
     * Get all admins of the team.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return Collection<int, Model>
     */
    public function getTeamAdmins(TeamContract $team): Collection
    {
        /** @var Collection<int, Model> $admins */
        $admins = $team->members()->wherePivot('role', 'admin')->get();

        return $admins;
=======
=======
>>>>>>> 87273113 (.)
     */
    public function getTeamAdmins(TeamContract $team): Collection
    {
        return $team->members()->wherePivot('role', 'admin')->get();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return Collection<int, Model>
     */
    public function getTeamAdmins(TeamContract $team): Collection
    {
        /** @var Collection<int, Model> $admins */
        $admins = $team->members()->wherePivot('role', 'admin')->get();

        return $admins;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get all members of the team.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return Collection<int, Model>
     */
    public function getTeamMembers(TeamContract $team): Collection
    {
        /** @var Collection<int, Model> $members */
        $members = $team->members()->wherePivot('role', 'member')->get();

        return $members;
=======
=======
>>>>>>> 87273113 (.)
     */
    public function getTeamMembers(TeamContract $team): Collection
    {
        return $team->members()->wherePivot('role', 'member')->get();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return Collection<int, Model>
     */
    public function getTeamMembers(TeamContract $team): Collection
    {
        /** @var Collection<int, Model> $members */
        $members = $team->members()->wherePivot('role', 'member')->get();

        return $members;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Determine if the user owns the given team.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @param TeamContract $team
>>>>>>> f548be94 (.)
=======
     *
     * @param TeamContract $team
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function checkTeamOwnership(TeamContract $team): bool
    {
        return $this->ownsTeam($team);
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)

    /**
     * Boot the HasTeams trait.
     */
    protected static function bootHasTeams(): void
    {
        /*
         * static::deleting(function ($team) {
         * $team->teamUsers()->delete();
         * $team->teamInvitations()->delete();
         * });
         */
    }
<<<<<<< HEAD
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
