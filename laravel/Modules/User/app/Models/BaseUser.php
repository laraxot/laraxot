<?php

declare(strict_types=1);

namespace Modules\User\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Models\Contracts\FilamentUser;
=======
use DateTime;
use Exception;
>>>>>>> f548be94 (.)
=======
use DateTime;
use Exception;
=======
use Filament\Models\Contracts\FilamentUser;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Model;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
<<<<<<< HEAD
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Modules\User\Contracts\HasAuthentications;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Traits\HasAuthenticationLogTrait;
use Modules\User\Models\Traits\HasDevices;
use Modules\User\Models\Traits\HasModules;
use Modules\User\Models\Traits\HasSocialite;
use Modules\User\Models\Traits\HasSpatiePermission;
use Modules\User\Models\Traits\HasTeams;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Models\Traits as XotTraits;
use Modules\Xot\Models\Traits\HasXotFactory;
use Parental\HasChildren;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Base User Model.
=======
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
<<<<<<< HEAD
use Laravel\Passport\HasApiTokens;
use Modules\TechPlanner\Models\Profile;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Traits\HasAuthenticationLogTrait;
use Modules\User\Models\Traits\HasTeams;
use Modules\Xot\Actions\Factory\GetFactoryAction;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Models\Traits\RelationX;
use Override;
use Parental\HasChildren;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Throwable;

/**
 * Base User Model
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Modules\User\Contracts\HasAuthentications;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Traits\HasAuthenticationLogTrait;
use Modules\User\Models\Traits\HasDevices;
use Modules\User\Models\Traits\HasModules;
use Modules\User\Models\Traits\HasSocialite;
use Modules\User\Models\Traits\HasSpatiePermission;
use Modules\User\Models\Traits\HasTeams;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Models\Traits as XotTraits;
use Modules\Xot\Models\Traits\HasXotFactory;
use Parental\HasChildren;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Base User Model.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 *
 * This is the base user model that provides the core authentication and authorization
 * functionality for the application. It extends Laravel's Authenticatable class
 * and implements the required interfaces for Filament and multi-tenancy.
 *
<<<<<<< HEAD
 * @property string $id
=======
>>>>>>> f548be94 (.)
 * @property Collection<int, OauthClient> $clients
 * @property int|null $clients_count
 * @property Team|null $currentTeam
 * @property Collection<int, Device> $devices
 * @property int|null $devices_count
 * @property string|null $full_name
 * @property DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property int|null $notifications_count
 * @property Collection<int, Team> $ownedTeams
 * @property int|null $owned_teams_count
 * @property Collection<int, Permission> $permissions
 * @property int|null $permissions_count
 * @property ProfileContract|null $profile
 * @property Collection<int, Role> $roles
 * @property int|null $roles_count
<<<<<<< HEAD
<<<<<<< HEAD
 * @property Collection<int, Team> $membershipTeams
 * @property int|null $membership_teams_count
 * @property Collection<int, Tenant> $tenants
 * @property int|null $tenants_count
 * @property Collection<int, OauthToken> $tokens
=======
=======
>>>>>>> 87273113 (.)
 * @property Collection<int, Team> $teams
 * @property int|null $teams_count
 * @property Collection<int, Tenant> $tenants
 * @property int|null $tenants_count
 * @property Collection<int, OauthAccessToken> $tokens
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property Collection<int, Team> $membershipTeams
 * @property int|null $membership_teams_count
 * @property Collection<int, Tenant> $tenants
 * @property int|null $tenants_count
 * @property Collection<int, OauthToken> $tokens
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @property int|null $tokens_count
 * @property string $last_name
 * @property string|null $facebook_id
 * @property Collection<int, SocialiteUser> $socialiteUsers
 * @property int|null $socialite_users_count
 * @property string|null $name
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $password
 * @property string|null $lang
 * @property string|null $current_team_id
 * @property bool|null $is_active
 * @property bool|null $is_otp
 * @property string|null $type
<<<<<<< HEAD
<<<<<<< HEAD
 * @property Carbon|null $password_expires_at
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
=======
=======
>>>>>>> 87273113 (.)
 * @property DateTime|null $password_expires_at
 * @property DateTime|null $email_verified_at
 * @property string|null $remember_token
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property \DateTime|null $password_expires_at
 * @property \DateTime|null $email_verified_at
 * @property string|null $remember_token
 * @property \DateTime|null $created_at
 * @property \DateTime|null $updated_at
 * @property \DateTime|null $deleted_at
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property string|null $profile_photo_path
 * @property Pivot|null $pivot
 *
<<<<<<< HEAD
<<<<<<< HEAD
=======
 * @method static UserFactory factory($count = null, $state = [])
>>>>>>> f548be94 (.)
=======
 * @method static UserFactory factory($count = null, $state = [])
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions, $without = false)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null, $without = false)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereCreatedBy($value)
 * @method static Builder|User whereCurrentTeamId($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereDeletedBy($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsActive($value)
 * @method static Builder|User whereLang($value)
 * @method static Builder|User whereLastName($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereProfilePhotoPath($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUpdatedBy($value)
 * @method static Builder|User withoutPermission($permissions)
 * @method static Builder|User withoutRole($roles, $guard = null)
 * @method static Builder|User whereFacebookId($value)
 * @method static Builder|User whereIsOtp($value)
 * @method static Builder|User wherePasswordExpiresAt($value)
 * @method static Builder|User whereSurname($value)
 *
 * @mixin \Eloquent
 */
<<<<<<< HEAD
<<<<<<< HEAD
abstract class BaseUser extends Authenticatable implements FilamentUser, HasAuthentications, HasMedia, HasName, HasTenants, MustVerifyEmail, OAuthenticatable, UserContract
=======
abstract class BaseUser extends Authenticatable implements HasMedia, HasName, HasTenants, MustVerifyEmail, UserContract
>>>>>>> f548be94 (.)
=======
abstract class BaseUser extends Authenticatable implements HasMedia, HasName, HasTenants, MustVerifyEmail, UserContract
=======
abstract class BaseUser extends Authenticatable implements FilamentUser, HasAuthentications, HasMedia, HasName, HasTenants, MustVerifyEmail, OAuthenticatable, UserContract
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    use HasApiTokens;
    use HasAuthenticationLogTrait;
    use HasChildren;
<<<<<<< HEAD
<<<<<<< HEAD
    use HasDevices;
    use HasModules;
    use HasSocialite;
    use HasSpatiePermission, HasTeams {
        HasSpatiePermission::teams insteadof HasTeams;
        HasTeams::teams as membershipTeams;
    }
    use HasUuids;

    use HasXotFactory;

    use InteractsWithMedia;
    use Notifiable;

    // use SoftDeletes;
    use Traits\HasTenants;
    use XotTraits\RelationX;

    public $incrementing = false;

    protected $connection = 'user';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected string $childColumn = 'type';

    protected $fillable = [
        'id',
        // 'ente',
        // 'matr',
=======
=======
>>>>>>> 87273113 (.)
    use HasFactory;
    use HasPermissions;
    use HasRoles;
    use HasTeams;
    use HasUuids;
    use InteractsWithMedia;
    use Notifiable;
    use RelationX;
    use Traits\HasTenants;

    public $incrementing = false;

    /** @var string */
    protected $connection = 'user';

    /** @var string */
    protected $primaryKey = 'id';

    /** @var string */
    protected $keyType = 'string';

    /** @var string */
    protected $childColumn = 'type';

    /** @var list<string> */
    protected $fillable = [
        'id',
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    use HasDevices;
    use HasModules;
    use HasSocialite;
    use HasSpatiePermission, HasTeams {
        HasSpatiePermission::teams insteadof HasTeams;
        HasTeams::teams as membershipTeams;
    }
    use HasUuids;

    use HasXotFactory;

    use InteractsWithMedia;
    use Notifiable;

    // use SoftDeletes;
    use Traits\HasTenants;
    use XotTraits\RelationX;

    public $incrementing = false;

    protected $connection = 'user';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    protected string $childColumn = 'type';

    protected $fillable = [
        'id',
        // 'ente',
        // 'matr',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'lang',
        'current_team_id',
        'is_active',
        'is_otp', // is One Time Password
        'password_expires_at',
<<<<<<< HEAD
<<<<<<< HEAD
        'email_verified_at',
        'type',
        'state',
=======
        'type',
>>>>>>> f548be94 (.)
=======
        'type',
=======
        'email_verified_at',
        'type',
        'state',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /** @var list<string> */
    protected $with = [
        // Removed 'roles' to reduce memory usage - load explicitly when needed
    ];

    /** @var list<string> */
    protected $appends = [
        // 'profile_photo_url',
    ];

    /** @var array<string, class-string> */
    protected $childTypes = [];

<<<<<<< HEAD
<<<<<<< HEAD
    protected $attributes = [
=======
=======
>>>>>>> 87273113 (.)
    /** @var array<string, mixed> */
    protected $attributes = [
        // 'state' => Pending::class,
        // 'state' => 'pending',
        'is_otp' => false,
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    protected $attributes = [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        'is_active' => true,
    ];

    /**
     * Guard coerente con Spatie/Permission: deve essere 'web'.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    protected string $guard_name = 'web';
=======
=======
>>>>>>> 87273113 (.)
     *
     * @var string
     */
    protected $guard_name = 'web';

    /** @var Pivot|null */
    public $pivot;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    protected string $guard_name = 'web';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    public function __construct(array $attributes = [])
    {
        // Concateno i fillable del parent con quelli della classe corrente
        // array_values() garantisce che sia un array indicizzato (list<string>)
        try {
            $this->fillable = array_values(array_merge(parent::getFillable(), $this->getFillable()));
            parent::__construct($attributes);
<<<<<<< HEAD
<<<<<<< HEAD
        } catch (\Throwable $e) {
            // Fallback in case database connection is not available (e.g., during testing)
            $this->fillable = array_values($this->getFillable());
            // Avoid calling parent constructor if database is not available
            foreach ($attributes as $key => $value) {
                $this->setAttribute($key, $value);
            }
        }
    }

    public function getProviderName(): string
    {
        $provider = $this->getAttribute('provider');
        if (\is_string($provider) && '' !== $provider) {
            return $provider;
        }

        $configured = config('auth.guards.api.provider', 'users');

        return \is_string($configured) ? $configured : 'users';
    }

    /*
    public function canAccessFilament(?Panel $panel = null): bool
    {
         dddx($panel->getId());
        // return $this->role_id === Role::ROLE_ADMINISTRATOR;
        return true;
    }
    */
=======
=======
>>>>>>> 87273113 (.)
        } catch (Throwable $e) {
            // Fallback in case database connection is not available (e.g., during testing)
            $this->fillable = array_values($this->getFillable());
            // Avoid calling parent constructor if database is not available
            $this->attributes = $attributes;
        }
    }

    public function canAccessFilament(?Panel $panel = null): bool
    {
        // return $this->role_id === Role::ROLE_ADMINISTRATOR;
        return true;
    }

<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        } catch (\Throwable $e) {
            // Fallback in case database connection is not available (e.g., during testing)
            $this->fillable = array_values($this->getFillable());
            // Avoid calling parent constructor if database is not available
            foreach ($attributes as $key => $value) {
                $this->setAttribute($key, $value);
            }
        }
    }

    public function getProviderName(): string
    {
        $provider = $this->getAttribute('provider');
        if (\is_string($provider) && '' !== $provider) {
            return $provider;
        }

        $configured = config('auth.guards.api.provider', 'users');

        return \is_string($configured) ? $configured : 'users';
    }

    /*
    public function canAccessFilament(?Panel $panel = null): bool
    {
         dddx($panel->getId());
        // return $this->role_id === Role::ROLE_ADMINISTRATOR;
        return true;
    }
    */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    /**
     * Get the user's name for Filament.
     */
    public function getFilamentName(): string
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $name = $this->name ?? '';
        $firstName = $this->first_name ?? '';
        $lastName = $this->last_name ?? '';

        $fullName = trim(\sprintf('%s %s %s', $name, $firstName, $lastName));

        // Ensure we always return a non-empty string
        if (empty($fullName)) {
            $email = $this->email ?? '';
=======
=======
>>>>>>> 87273113 (.)
        $name = (string) ($this->getAttribute('name') ?? '');
        $firstName = (string) ($this->getAttribute('first_name') ?? '');
        $lastName = (string) ($this->getAttribute('last_name') ?? '');

        $fullName = trim(sprintf('%s %s %s', $name, $firstName, $lastName));

        // Ensure we always return a non-empty string
        if (empty($fullName)) {
            $email = (string) ($this->getAttribute('email') ?? '');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $name = $this->name ?? '';
        $firstName = $this->first_name ?? '';
        $lastName = $this->last_name ?? '';

        $fullName = trim(\sprintf('%s %s %s', $name, $firstName, $lastName));

        // Ensure we always return a non-empty string
        if (empty($fullName)) {
            $email = $this->email ?? '';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

            return ! empty($email) ? $email : 'User';
        }

        return $fullName;
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return HasOne<Model&ProfileContract, Model&static>
     *
     * @phpstan-return HasOne<Model&ProfileContract, Model&static>
     */
    #[\Override]
    public function profile(): HasOne
    {
        $profileClass = XotData::make()->getProfileClass();
        if (class_exists($profileClass)) {
            /** @var HasOne<Model&ProfileContract, Model&static> $relation */
            $relation = $this->hasOne($profileClass);

            return $relation;
        }

        // Try direct module class if XotData failed
        $directClass = 'Modules\User\Models\Profile';
        if (class_exists($directClass)) {
            /** @var HasOne<Model&ProfileContract, Model&static> $relation */
            $relation = $this->hasOne($directClass);

            return $relation;
        }

        // Fallback: stay on current model if nothing found
        /** @var HasOne<Model&ProfileContract, Model&static> $relation */
        $relation = $this->hasOne(static::class, 'id', 'id')->whereRaw('1=0');

        return $relation;
=======
=======
>>>>>>> 87273113 (.)
    #[Override]
    public function profile(): HasOne
    {
        try {
            /** @var class-string<Model> */
            $profileClass = XotData::make()->getProfileClass();
            if (class_exists($profileClass)) {
                return $this->hasOne($profileClass);
            } else {
                // Fallback: se non riesce a ottenere la classe Profile, usa una relazione generica
                // Questo evita l'errore "Target [Illuminate\Database\Eloquent\Model] is not instantiable"
                // Utilizziamo una classe che sicuramente esiste nel sistema
                return $this->hasOne(Model::class);
            }
        } catch (Exception $e) {
            // Fallback: se non riesce a ottenere la classe Profile, usa una relazione generica
            // Questo evita l'errore "Target [Illuminate\Database\Eloquent\Model] is not instantiable"
            // Utilizziamo una classe che sicuramente esiste nel sistema
            return $this->hasOne(Model::class);
        }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return HasOne<Model&ProfileContract, Model&static>
     *
     * @phpstan-return HasOne<Model&ProfileContract, Model&static>
     */
    #[\Override]
    public function profile(): HasOne
    {
        $profileClass = XotData::make()->getProfileClass();
        if (class_exists($profileClass)) {
            /** @var HasOne<Model&ProfileContract, Model&static> $relation */
            $relation = $this->hasOne($profileClass);

            return $relation;
        }

        // Try direct module class if XotData failed
        $directClass = 'Modules\User\Models\Profile';
        if (class_exists($directClass)) {
            /** @var HasOne<Model&ProfileContract, Model&static> $relation */
            $relation = $this->hasOne($directClass);

            return $relation;
        }

        // Fallback: stay on current model if nothing found
        /** @var HasOne<Model&ProfileContract, Model&static> $relation */
        $relation = $this->hasOne(static::class, 'id', 'id')->whereRaw('1=0');

        return $relation;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Verifica se l'utente ha il ruolo di super-admin.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return bool True se l'utente Ã¨ super-admin, altrimenti false
=======
     * @return bool True se l'utente è super-admin, altrimenti false
>>>>>>> f548be94 (.)
=======
     * @return bool True se l'utente è super-admin, altrimenti false
=======
     * @return bool True se l'utente Ã¨ super-admin, altrimenti false
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function assignModule(string $module): void
    {
        $role_name = $module.'::admin';
        $role = Role::firstOrCreate(['name' => $role_name]);
        $this->assignRole($role);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // $panel->default('admin');
        if ($panel->getId() !== 'admin') {
            $role = $panel->getId();
<<<<<<< HEAD
<<<<<<< HEAD

            // App\Support\AccountFeatures non e' mai esistita (ne' la classe ne'
            // config/account_features.php): riferimento morto fin dal commit
            // iniziale del modulo, causava un Error fatale a runtime su ogni
            // pannello diverso da "admin". hasRole($role) resta l'unico controllo
            // reale, come gia' documentato qui sopra come fallback.
=======
=======
>>>>>>> 87273113 (.)
            /*
             * $xot = XotData::make();
             * if ($xot->super_admin === $this->email) {
             * $role = Role::firstOrCreate(['name' => $role]);
             * $this->assignRole($role);
             * }
             */

<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======

            // App\Support\AccountFeatures non e' mai esistita (ne' la classe ne'
            // config/account_features.php): riferimento morto fin dal commit
            // iniziale del modulo, causava un Error fatale a runtime su ogni
            // pannello diverso da "admin". hasRole($role) resta l'unico controllo
            // reale, come gia' documentato qui sopra come fallback.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $this->hasRole($role);
        }

        return true; // str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
    }

<<<<<<< HEAD
<<<<<<< HEAD
    public function detach(Model $model): void
    {
        $this->membershipTeams()->detach($model);
=======
=======
>>>>>>> 87273113 (.)
    public function canAccessSocialite(): bool
    {
        return true;
    }

    public function detach(Model $model): void
    {
        // @phpstan-ignore function.alreadyNarrowedType
        if (method_exists($this, 'teams')) {
            // @phpstan-ignore function.alreadyNarrowedType
            $this->teams()->detach($model);
        }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function detach(Model $model): void
    {
        $this->membershipTeams()->detach($model);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    public function attach(Model $model): void
    {
<<<<<<< HEAD
<<<<<<< HEAD
        $this->membershipTeams()->attach($model);
=======
=======
>>>>>>> 87273113 (.)
        // @phpstan-ignore function.alreadyNarrowedType
        if (method_exists($this, 'teams')) {
            // @phpstan-ignore function.alreadyNarrowedType
            $this->teams()->attach($model);
        }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $this->membershipTeams()->attach($model);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    public function treeLabel(): string
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return (string) ($this->name ?? $this->email);
    }

    /**
     * @return Collection<int, Team>
     */
    /**
     * @return Collection<int, Team>
     */
    public function treeSons(): Collection
    {
        return $this->membershipTeams ?? new Collection;
=======
=======
>>>>>>> 87273113 (.)
        return strval($this->name ?? $this->email);
    }

    public function treeSons(): Collection
    {
        return $this->teams ?? new Collection;
    }

    /**
     * Get the devices associated with the user.
     *
     * @return BelongsToMany<Device, static>
     */
    public function devices(): BelongsToMany
    {
        return $this->belongsToManyX(Device::class);
    }

    /**
     * Get the socialite users associated with the user.
     *
     * @return HasMany<SocialiteUser, $this>
     */
    public function socialiteUsers(): HasMany
    {
        return $this->hasMany(SocialiteUser::class);
    }

    public function getProviderField(string $provider, string $field): string
    {
        $socialiteUser = $this->socialiteUsers()->firstWhere(['provider' => $provider]);
        if ($socialiteUser === null) {
            throw new Exception('SocialiteUser not found');
        }

        $res = $socialiteUser->{$field};

        return (string) $res;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        return (string) ($this->name ?? $this->email);
    }

    /**
     * @return Collection<int, Team>
     */
    /**
     * @return Collection<int, Team>
     */
    public function treeSons(): Collection
    {
        return $this->membershipTeams ?? new Collection;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get the entity's notifications.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return MorphMany<Notification, $this>
     */
    public function notifications(): MorphMany
    {
=======
=======
>>>>>>> 87273113 (.)
     * @return MorphMany<Notification, static|$this>
     */
    public function notifications()
    {
        // @phpstan-ignore return.type
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return MorphMany<Notification, $this>
     */
    public function notifications(): MorphMany
    {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        return $this->morphMany(Notification::class, 'notifiable');
    }

    /**
     * Get the user's latest authentication log.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return MorphOne<AuthenticationLog, $this>
     */
    public function latestAuthentication(): MorphOne
    {
=======
=======
>>>>>>> 87273113 (.)
     * @return MorphOne<AuthenticationLog, static>
     */
    public function latestAuthentication(): MorphOne
    {
        // @phpstan-ignore return.type
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @return MorphOne<AuthenticationLog, $this>
     */
    public function latestAuthentication(): MorphOne
    {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        return $this->morphOne(AuthenticationLog::class, 'authenticatable')->latestOfMany();
    }

    public function getFullNameAttribute(?string $value): string
    {
        if ($value !== null) {
            return $value;
        }

        $fullName = trim(($this->first_name ?? '').' '.($this->last_name ?? ''));

        return $fullName !== '' ? $fullName : ($this->email ?? 'User');
    }

    public function getNameAttribute(?string $value): string
    {
        if ($value !== null) {
            return $value;
        }

        if ($this->getKey() === null) {
            return $this->email ?? 'User';
        }

        $name = Str::of((string) $this->email)->before('@')->toString();
        $i = 1;
        $candidate = $name.'-'.$i;

        // During unit tests, avoid any DB interaction.
<<<<<<< HEAD
<<<<<<< HEAD
        $isTesting = (static function (): bool {
=======
        $isTesting = (function (): bool {
>>>>>>> f548be94 (.)
=======
        $isTesting = (function (): bool {
=======
        $isTesting = (static function (): bool {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $app = app();
            if (method_exists($app, 'environment') && $app->environment('testing')) {
                return true;
            }

<<<<<<< HEAD
<<<<<<< HEAD
            return \PHP_SAPI === 'cli' && (getenv('APP_ENV') === 'testing' || getenv('ENV') === 'testing');
=======
            return PHP_SAPI === 'cli' && (getenv('APP_ENV') === 'testing' || getenv('ENV') === 'testing');
>>>>>>> f548be94 (.)
=======
            return PHP_SAPI === 'cli' && (getenv('APP_ENV') === 'testing' || getenv('ENV') === 'testing');
=======
            return \PHP_SAPI === 'cli' && (getenv('APP_ENV') === 'testing' || getenv('ENV') === 'testing');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        })();
        if ($isTesting) {
            // Do not call update() here to avoid hitting the database.
            $this->attributes['name'] = $candidate;

            return $candidate;
        }

        try {
            $value = $candidate;
            while (self::firstWhere(['name' => $value]) !== null) {
                $i++;
                $value = $name.'-'.$i;
            }
            $this->update(['name' => $value]);

            return $value;
<<<<<<< HEAD
<<<<<<< HEAD
        } catch (\Throwable $e) {
=======
        } catch (Throwable $e) {
>>>>>>> f548be94 (.)
=======
        } catch (Throwable $e) {
=======
        } catch (\Throwable $e) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            // If any issue occurs (e.g., missing connection/table), fall back without DB.
            $this->attributes['name'] = $candidate;

            return $candidate;
        }
    }

<<<<<<< HEAD
<<<<<<< HEAD
    // public function authentications(): MorphMany
    // {
    //    return $this->morphMany(\Modules\User\Models\Authentication::class, 'authenticatable');
    // }

    /**
     * Check if the user has a specific role.
     *
     * NOTE: This method has been moved to trait HasSpatiePermission.
     * If you need role checking functionality, use the trait method instead.
     *
     * @see HasSpatiePermission::hasRole()
     */
    public function setPasswordAttribute(?string $value): void
    {
        if (empty($value)) {
            unset($this->attributes['password']);

            return;
        }
        if (\strlen($value) < 32) {
            $this->attributes['password'] = Hash::make($value);

            return;
        }
        $this->attributes['password'] = $value;
    }

    /**
     * User possiede molti Clients OAuth (per autenticazione API).
     *
     * @return MorphMany<OauthClient, $this>
     */
    public function clients(): MorphMany
    {
        return $this->morphMany(OauthClient::class, 'owner');
    }

    /**
     * Find the user instance for the given username.
     */
    public static function findForPassport(string $username): ?self
    {
        return static::where('email', $username)->first();
    }

    /**
     * Validate the password of the user for the given password.
     */
    public function validateForPassportPasswordGrant(string $password): bool
    {
        return Hash::check($password, (string) $this->password);
=======
=======
>>>>>>> 87273113 (.)
    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory()
    {
        return app(GetFactoryAction::class)->execute(static::class);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    // public function authentications(): MorphMany
    // {
    //    return $this->morphMany(\Modules\User\Models\Authentication::class, 'authenticatable');
    // }

    /**
     * Check if the user has a specific role.
     *
     * NOTE: This method has been moved to trait HasSpatiePermission.
     * If you need role checking functionality, use the trait method instead.
     *
     * @see HasSpatiePermission::hasRole()
     */
    public function setPasswordAttribute(?string $value): void
    {
        if (empty($value)) {
            unset($this->attributes['password']);

            return;
        }
        if (\strlen($value) < 32) {
            $this->attributes['password'] = Hash::make($value);

            return;
        }
        $this->attributes['password'] = $value;
    }

    /**
     * User possiede molti Clients OAuth (per autenticazione API).
     *
     * @return MorphMany<OauthClient, $this>
     */
    public function clients(): MorphMany
    {
        return $this->morphMany(OauthClient::class, 'owner');
    }

    /**
     * Find the user instance for the given username.
     */
    public static function findForPassport(string $username): ?self
    {
        return static::where('email', $username)->first();
    }

    /**
     * Validate the password of the user for the given password.
     */
    public function validateForPassportPasswordGrant(string $password): bool
    {
        return Hash::check($password, (string) $this->password);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'email_verified_at' => 'datetime',
            // 'password' => 'hashed', //Call to undefined cast [hashed] on column [password] in model [Modules\User\Models\User].
            'is_active' => 'boolean',
            'roles.pivot.id' => 'string',
            // https://github.com/beitsafe/laravel-uuid-auditing
            // ALTER TABLE model_has_role CHANGE COLUMN `id` `id` CHAR(37) NOT NULL DEFAULT uuid();

            'is_otp' => 'boolean',
            'password_expires_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'updated_by' => 'string',
            'created_by' => 'string',
            'deleted_by' => 'string',
        ];
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)

    // public function authentications(): MorphMany
    // {
    //    return $this->morphMany(\Modules\User\Models\Authentication::class, 'authenticatable');
    // }

    /**
     * Check if the user has a specific role.
     *
     * @param  array|\Illuminate\Support\Collection|int|\Spatie\Permission\Contracts\Role|string  $roles
     */
    #[Override]
    public function hasRole($roles, ?string $guard = null): bool
    {
        // Se è una stringa semplice, utilizziamo il metodo interno tramite relazione roles
        if (is_string($roles)) {
            return once(fn (): bool => $this->roles()->where('name', $roles)->exists());
        }

        // Per gli altri tipi, implementiamo una logica di base
        if (is_array($roles) || $roles instanceof \Illuminate\Support\Collection) {
            foreach ($roles as $role) {
                if ($this->hasRole($role, $guard)) {
                    return true;
                }
            }

            return false;
        }

        if ($roles instanceof \Spatie\Permission\Contracts\Role) {
            return $this->roles()->where('id', $roles->id)->exists();
        }

        if (is_int($roles)) {
            return $this->roles()->where('id', $roles)->exists();
        }

        return false;
    }

    public function setPasswordAttribute(?string $value): void
    {
        if (empty($value)) {
            unset($this->attributes['password']);

            return;
        }
        if (strlen($value) < 32) {
            $this->attributes['password'] = Hash::make($value);

            return;
        }
        $this->attributes['password'] = $value;
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
