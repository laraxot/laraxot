<?php

declare(strict_types=1);

namespace Modules\User\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Builder;
=======
=======
>>>>>>> 87273113 (.)
use Override;
use Illuminate\Support\Carbon;
use Modules\User\Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Contracts\ProfileContract;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Builder;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\User\Contracts\TeamContract;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Support\Collection;
use Modules\User\Contracts\TeamContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Models\Traits\HasExtraTrait;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\User\Contracts\TeamContract;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * Modules\User\Models\Team.
 *
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int                                         $id
 * @property int                                         $user_id
 * @property string                                      $name
 * @property int                                         $personal_team
 * @property Carbon|null                                 $created_at
 * @property Carbon|null                                 $updated_at
 * @property EloquentCollection<int, Model&UserContract> $members
 * @property int|null                                    $members_count
 * @property UserContract|null                           $owner
 * @property EloquentCollection<int, TeamInvitation>     $teamInvitations
 * @property int|null                                    $team_invitations_count
 * @property EloquentCollection<int, Model&UserContract> $users
 * @property int|null                                    $users_count
 *
=======
=======
>>>>>>> 87273113 (.)
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $personal_team
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property EloquentCollection<int, Model&UserContract> $members
 * @property int|null $members_count
 * @property UserContract|null $owner
 * @property EloquentCollection<int, TeamInvitation> $teamInvitations
 * @property int|null $team_invitations_count
 * @property EloquentCollection<int, Model&UserContract> $users
 * @property int|null $users_count
 *
 * @method static TeamFactory factory($count = null, $state = [])
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property int                                         $id
 * @property int                                         $user_id
 * @property string                                      $name
 * @property int                                         $personal_team
 * @property Carbon|null                                 $created_at
 * @property Carbon|null                                 $updated_at
 * @property EloquentCollection<int, Model&UserContract> $members
 * @property int|null                                    $members_count
 * @property UserContract|null                           $owner
 * @property EloquentCollection<int, TeamInvitation>     $teamInvitations
 * @property int|null                                    $team_invitations_count
 * @property EloquentCollection<int, Model&UserContract> $users
 * @property int|null                                    $users_count
 *
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @method static Builder|Team newModelQuery()
 * @method static Builder|Team newQuery()
 * @method static Builder|Team query()
 * @method static Builder|Team whereCreatedAt($value)
 * @method static Builder|Team whereId($value)
 * @method static Builder|Team whereName($value)
 * @method static Builder|Team wherePersonalTeam($value)
 * @method static Builder|Team whereUpdatedAt($value)
 * @method static Builder|Team whereUserId($value)
 *
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property Carbon|null $deleted_at
 * @property string|null $deleted_by
 *
 * @method static Builder|Team whereCreatedBy($value)
 * @method static Builder|Team whereDeletedAt($value)
 * @method static Builder|Team whereDeletedBy($value)
 * @method static Builder|Team whereUpdatedBy($value)
 *
<<<<<<< HEAD
<<<<<<< HEAD
 * @property Membership           $membership
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property string               $uuid
=======
=======
>>>>>>> 87273113 (.)
 * @property Membership $membership
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property string $uuid
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property Membership           $membership
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property string               $uuid
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 *
 * @method static Builder|Team whereUuid($value)
 *
 * @mixin \Eloquent
 */
abstract class BaseTeam extends BaseModel implements TeamContract
{
    // Se ho bisogno di extra in customer aggiungo extra in customer
    // use HasExtraTrait;

    /** @var list<string> */
    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'personal_team',
    ];

    /** @var list<string> */
    protected $with = [
        // 'extra',
    ];

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * @return BelongsTo<Model&UserContract, Model>
     */
    #[\Override]
=======
     * Get the owner of the team.
     */
    #[Override]
>>>>>>> f548be94 (.)
=======
     * Get the owner of the team.
     */
    #[Override]
=======
     * @return BelongsTo<Model&UserContract, Model>
     */
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function owner(): BelongsTo
    {
        $xotData = XotData::make();
        /** @var class-string<Model> */
        $user_class = $xotData->getUserClass();

<<<<<<< HEAD
<<<<<<< HEAD
        /** @var BelongsTo<Model&UserContract, Model> $relation */
        $relation = $this->belongsTo($user_class, 'user_id');

        return $relation;
=======
        return $this->belongsTo($user_class, 'user_id');
>>>>>>> f548be94 (.)
=======
        return $this->belongsTo($user_class, 'user_id');
=======
        /** @var BelongsTo<Model&UserContract, Model> $relation */
        $relation = $this->belongsTo($user_class, 'user_id');

        return $relation;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get all of the team's users including its owner.
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return Collection<int, Model&UserContract>
     */
    #[\Override]
    public function allUsers(): Collection
    {
        if (! $this->owner instanceof User) {
=======
=======
>>>>>>> 87273113 (.)
     */
    #[Override]
    public function allUsers(): Collection
    {
        if (!($this->owner instanceof User)) {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return Collection<int, Model&UserContract>
     */
    #[\Override]
    public function allUsers(): Collection
    {
        if (! $this->owner instanceof User) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $this->users;
        }

        return $this->users->merge([$this->owner]);
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * @return BelongsToMany<Model&UserContract, Model, TeamUser, 'pivot'>
     */
    #[\Override]
=======
     * Get all of the users that belong to the team.
     */
    #[Override]
>>>>>>> f548be94 (.)
=======
     * Get all of the users that belong to the team.
     */
    #[Override]
=======
     * @return BelongsToMany<Model&UserContract, Model, TeamUser, 'pivot'>
     */
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function users(): BelongsToMany
    {
        $xotData = XotData::make();
        /** @var class-string<Model> */
        $userClass = $xotData->getUserClass();

<<<<<<< HEAD
<<<<<<< HEAD
        /** @var BelongsToMany<Model&UserContract, Model, TeamUser, 'pivot'> $relation */
        $relation = $this->belongsToManyX($userClass)
            ->using(TeamUser::class)
            ->withPivot(['role', 'permissions']);

        return $relation;
    }

    /**
     * Get the team users (memberships) relationship.
     *
     * @return HasMany<TeamUser, $this>
     */
    public function teamUsers(): HasMany
    {
        return $this->hasMany(TeamUser::class);
    }

    /**
     * @return BelongsToMany<Model&UserContract, Model, TeamUser, 'pivot'>
     */
    #[\Override]
=======
=======
>>>>>>> 87273113 (.)
        return $this->belongsToManyX($userClass);
    }

    /**
     * Ottiene tutti i membri del team (alias di users).
     *
     * @return BelongsToMany<Model, \Modules\User\Models\BaseTeam>
     */
    #[Override]
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        /** @var BelongsToMany<Model&UserContract, Model, TeamUser, 'pivot'> $relation */
        $relation = $this->belongsToManyX($userClass)
            ->using(TeamUser::class)
            ->withPivot(['role', 'permissions']);

        return $relation;
    }

    /**
     * Get the team users (memberships) relationship.
     *
     * @return HasMany<TeamUser, $this>
     */
    public function teamUsers(): HasMany
    {
        return $this->hasMany(TeamUser::class);
    }

    /**
     * @return BelongsToMany<Model&UserContract, Model, TeamUser, 'pivot'>
     */
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function members(): BelongsToMany
    {
        return $this->users();
    }

    /**
     * Determina se l'utente specificato appartiene al team.
     *
     * @param UserContract $user L'utente da verificare
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return bool True se l'utente appartiene al team, false altrimenti
     */
    #[\Override]
=======
     * @return bool True se l'utente appartiene al team, false altrimenti
     */
    #[Override]
>>>>>>> f548be94 (.)
=======
     * @return bool True se l'utente appartiene al team, false altrimenti
     */
    #[Override]
=======
     *
     * @return bool True se l'utente appartiene al team, false altrimenti
     */
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function hasUser(UserContract $user): bool
    {
        // Corretto l'errore di tipo per il metodo contains
        // Verifico se l'ID dell'utente è presente nella collection degli utenti del team
        if ($this->users->contains('id', $user->getKey())) {
            return true;
        }

        return $user->ownsTeam($this);
    }

    /**
     * Determina se l'indirizzo email specificato appartiene a un utente del team.
     *
     * @param string $email Indirizzo email da verificare
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return bool True se un utente con quell'email appartiene al team, false altrimenti
     */
    #[\Override]
    public function hasUserWithEmail(string $email): bool
    {
        return $this->allUsers()->contains(static function (Model&UserContract $user) use ($email): bool {
            return ($user->email ?? null) === $email;
        });
=======
=======
>>>>>>> 87273113 (.)
     * @return bool True se un utente con quell'email appartiene al team, false altrimenti
     */
    #[Override]
    public function hasUserWithEmail(string $email): bool
    {
        return $this->allUsers()->contains(static fn($user): bool => $user->email === $email);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     *
     * @return bool True se un utente con quell'email appartiene al team, false altrimenti
     */
    #[\Override]
    public function hasUserWithEmail(string $email): bool
    {
        return $this->allUsers()->contains(static function (Model&UserContract $user) use ($email): bool {
            return ($user->email ?? null) === $email;
        });
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Determina se l'utente specificato ha il permesso indicato sul team.
     *
     * @param UserContract $userContract L'utente da verificare
<<<<<<< HEAD
<<<<<<< HEAD
     * @param string       $permission   Il permesso da controllare
     *
     * @return bool True se l'utente ha il permesso, false altrimenti
     */
    #[\Override]
=======
=======
>>>>>>> 87273113 (.)
     * @param string $permission Il permesso da controllare
     * @return bool True se l'utente ha il permesso, false altrimenti
     */
    #[Override]
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @param string       $permission   Il permesso da controllare
     *
     * @return bool True se l'utente ha il permesso, false altrimenti
     */
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function userHasPermission(UserContract $userContract, string $permission): bool
    {
        return $userContract->hasTeamPermission($this, $permission);
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * @return HasMany<TeamInvitation, Model>
     */
    #[\Override]
    public function teamInvitations(): HasMany
    {
        /** @var HasMany<TeamInvitation, Model> $relation */
        $relation = $this->hasMany(TeamInvitation::class);

        return $relation;
=======
=======
>>>>>>> 87273113 (.)
     * Ottiene tutti gli inviti utente pendenti per il team.
     *
     * @return HasMany<TeamInvitation, \Modules\User\Models\BaseTeam>
     * @phpstan-return HasMany<TeamInvitation, $this>
     */
    #[Override]
    public function teamInvitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return HasMany<TeamInvitation, Model>
     */
    #[\Override]
    public function teamInvitations(): HasMany
    {
        /** @var HasMany<TeamInvitation, Model> $relation */
        $relation = $this->hasMany(TeamInvitation::class);

        return $relation;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Rimuove l'utente specificato dal team.
     *
     * @param UserContract $userContract L'utente da rimuovere dal team
<<<<<<< HEAD
<<<<<<< HEAD
     */
    #[\Override]
=======
     * @return void
     */
    #[Override]
>>>>>>> f548be94 (.)
=======
     * @return void
     */
    #[Override]
=======
     */
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function removeUser(UserContract $userContract): void
    {
        if ($userContract->current_team_id === $this->id) {
            $userContract->forceFill([
                'current_team_id' => null,
            ])->save();
        }

        $this->users()->detach($userContract);
    }

    /**
     * Rimuove tutte le risorse del team.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    #[\Override]
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return void
     */
    #[Override]
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function purge(): void
    {
        $this->owner()->where('current_team_id', $this->id)->update(['current_team_id' => null]);

        $this->users()->where('current_team_id', $this->id)->update(['current_team_id' => null]);

        $this->users()->detach();

        $this->delete();
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'user_id' => 'string',
            'personal_team' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }
<<<<<<< HEAD
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
