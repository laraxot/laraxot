<?php

declare(strict_types=1);

namespace Modules\User\Models;

<<<<<<< HEAD
<<<<<<< HEAD
// // use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Modules\Media\Models\Media;
use Modules\User\Models\Traits\IsProfileTrait;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Parental\HasChildren;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
=======
=======
>>>>>>> 87273113 (.)
// use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\DatabaseNotification;
use Override;
use Illuminate\Database\Eloquent\Collection;
use Modules\Media\Models\Media;
use Modules\Xot\Contracts\UserContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Modules\User\Models\Traits\IsProfileTrait;
use Modules\Xot\Contracts\ProfileContract;
use Parental\HasChildren;
use Spatie\MediaLibrary\InteractsWithMedia;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
// // use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Modules\Media\Models\Media;
use Modules\User\Models\Traits\IsProfileTrait;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Parental\HasChildren;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Spatie\Permission\Traits\HasRoles;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;

/**
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int|string                                                $id
 * @property string                                                    $uuid
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes         $extra
 * @property string                                                    $avatar
 * @property Collection<int, DeviceUser>                               $deviceUsers
 * @property int|null                                                  $device_users_count
 * @property Collection<int, Device>                                   $devices
 * @property int|null                                                  $devices_count
 * @property string|null                                               $first_name
 * @property string|null                                               $full_name
 * @property string|null                                               $last_name
 * @property string|null                                               $lang
 * @property MediaCollection<int, Media>                               $media
 * @property int|null                                                  $media_count
 * @property Collection<int, DeviceUser>                               $mobileDeviceUsers
 * @property int|null                                                  $mobile_device_users_count
 * @property Collection<int, Device>                                   $mobileDevices
 * @property int|null                                                  $mobile_devices_count
 * @property DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property int|null                                                  $notifications_count
 * @property Collection<int, Permission>                               $permissions
 * @property int|null                                                  $permissions_count
 * @property Collection<int, Role>                                     $roles
 * @property int|null                                                  $roles_count
 * @property UserContract|null                                         $user
 * @property string|null                                               $user_name
 *
 * @method static Builder<static> newModelQuery()
 * @method static Builder<static> newQuery()
 * @method static Builder<static> permission($permissions, $without = false)
 * @method static Builder<static> query()
 * @method static Builder<static> role($roles, $guard = null, $without = false)
 * @method static Builder<static> byUuid(string $uuid)
 * @method static Builder<static> withExtraAttributes()
 * @method static Builder<static> withoutPermission($permissions)
 * @method static Builder<static> withoutRole($roles, $guard = null)
 *
 * @mixin \Eloquent
 */
// @see Modules/Xot/docs/spatie-schemaless-attributes.md
=======
=======
>>>>>>> 87273113 (.)
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra
 * @property string $avatar
 * @property Collection<int, DeviceUser> $deviceUsers
 * @property int|null $device_users_count
 * @property Collection<int, Device> $devices
 * @property int|null $devices_count
 * @property string|null $first_name
 * @property string|null $full_name
 * @property string|null $last_name
 * @property string|null $lang
 * @property MediaCollection<int, Media> $media
 * @property int|null $media_count
 * @property Collection<int, DeviceUser> $mobileDeviceUsers
 * @property int|null $mobile_device_users_count
 * @property Collection<int, Device> $mobileDevices
 * @property int|null $mobile_devices_count
 * @property DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property int|null $notifications_count
 * @property Collection<int, Permission> $permissions
 * @property int|null $permissions_count
 * @property Collection<int, Role> $roles
 * @property int|null $roles_count
 * @property UserContract|null $user
 * @property string|null $user_name
 *
 * @method static Builder|ProfileContract newModelQuery()
 * @method static Builder|ProfileContract newQuery()
 * @method static Builder|ProfileContract permission($permissions, $without = false)
 * @method static Builder|ProfileContract query()
 * @method static Builder|ProfileContract role($roles, $guard = null, $without = false)
 * @method static Builder|BaseProfile withExtraAttributes()
 * @method static Builder|ProfileContract withoutPermission($permissions)
 * @method static Builder|ProfileContract withoutRole($roles, $guard = null)
 *
 * @mixin \Eloquent
 */
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 * @property int                                                       $id
 * @property string                                                    $uuid
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes         $extra
 * @property string                                                    $avatar
 * @property Collection<int, DeviceUser>                               $deviceUsers
 * @property int|null                                                  $device_users_count
 * @property Collection<int, Device>                                   $devices
 * @property int|null                                                  $devices_count
 * @property string|null                                               $first_name
 * @property string|null                                               $full_name
 * @property string|null                                               $last_name
 * @property string|null                                               $lang
 * @property MediaCollection<int, Media>                               $media
 * @property int|null                                                  $media_count
 * @property Collection<int, DeviceUser>                               $mobileDeviceUsers
 * @property int|null                                                  $mobile_device_users_count
 * @property Collection<int, Device>                                   $mobileDevices
 * @property int|null                                                  $mobile_devices_count
 * @property DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property int|null                                                  $notifications_count
 * @property Collection<int, Permission>                               $permissions
 * @property int|null                                                  $permissions_count
 * @property Collection<int, Role>                                     $roles
 * @property int|null                                                  $roles_count
 * @property UserContract|null                                         $user
 * @property string|null                                               $user_name
 *
 * @method static Builder<static> newModelQuery()
 * @method static Builder<static> newQuery()
 * @method static Builder<static> permission($permissions, $without = false)
 * @method static Builder<static> query()
 * @method static Builder<static> role($roles, $guard = null, $without = false)
 * @method static Builder<static> byUuid(string $uuid)
 * @method static Builder<static> withExtraAttributes()
 * @method static Builder<static> withoutPermission($permissions)
 * @method static Builder<static> withoutRole($roles, $guard = null)
 *
 * @mixin \Eloquent
 */
// @see Modules/Xot/docs/spatie-schemaless-attributes.md
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
abstract class BaseProfile extends BaseModel implements ProfileContract
{
    use HasChildren;
    use HasRoles;
<<<<<<< HEAD
<<<<<<< HEAD

    // use HasUuids;
=======
>>>>>>> f548be94 (.)
=======
=======

    // use HasUuids;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    use InteractsWithMedia;
    use IsProfileTrait;
    use Notifiable;
    use SchemalessAttributesTrait;

    /**
     * Undocumented variable.
     * Property Modules\Xot\Models\Profile::$guard_name is never read, only written.
     */
    // private string $guard_name = 'web';

    /** @var list<string> */
    protected $fillable = [
<<<<<<< HEAD
<<<<<<< HEAD
=======
        'id',
>>>>>>> f548be94 (.)
=======
        'id',
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        'uuid',
        'user_id',
        'type',
        'first_name',
        'last_name',
        'phone',
<<<<<<< HEAD
<<<<<<< HEAD
        'address',
        'birth_date',
        'gender',
        'email',
        'bio',
=======
        'email',
        'bio',
=======
        'address',
        'birth_date',
        'gender',
        'email',
        'bio',
>>>>>>> 87273113 (.)
        'avatar',
        'timezone',
        'locale',
        'preferences',
        'status',
<<<<<<< HEAD
=======
        'email',
        'bio',
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        'is_active',
        'extra',
    ];

    /** @var list<string> */
    protected $appends = [
        'full_name',
    ];

    /** @var list<string> */
    protected $with = [
        'user',
    ];

<<<<<<< HEAD
<<<<<<< HEAD
    /** @var list<string> */
    protected array $formlessAttributes = [
        'extra',
    ];

    /**
     * Scope per lookup da API/Android/Postgres (usa uuid, non id).
     *
     * @param Builder<static> $query
     *
     * @return Builder<static>
     */
    public function scopeByUuid(Builder $query, string $uuid): Builder
    {
        return $query->where('uuid', $uuid);
    }

    // ✅ CORRETTO: NON implementare scopeWithExtraAttributes() manualmente
    // Il trait SchemalessAttributesTrait lo fornisce automaticamente!
    // NOTA: BaseProfile ha attributo 'extra' diretto, non relazione 'extra'

=======
=======
>>>>>>> 87273113 (.)
    /** @var array */
    protected $formlessAttributes = [
        'extra',
    ];

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->extra->modelScope();
    }

<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /** @var list<string> */
    protected array $formlessAttributes = [
        'extra',
    ];

    /**
     * Scope per lookup da API/Android/Postgres (usa uuid, non id).
     *
     * @param Builder<static> $query
     *
     * @return Builder<static>
     */
    public function scopeByUuid(Builder $query, string $uuid): Builder
    {
        return $query->where('uuid', $uuid);
    }

    // ✅ CORRETTO: NON implementare scopeWithExtraAttributes() manualmente
    // Il trait SchemalessAttributesTrait lo fornisce automaticamente!
    // NOTA: BaseProfile ha attributo 'extra' diretto, non relazione 'extra'

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    /**
     * Ottiene l'URL dell'avatar dell'utente.
     *
     * @return string L'URL dell'avatar
     */
    public function getAvatarUrl(): string
    {
        $avatar = $this->getFirstMediaUrl('avatar');
<<<<<<< HEAD
<<<<<<< HEAD
        if ('' !== $avatar) {
=======
        if ($avatar !== '') {
>>>>>>> f548be94 (.)
=======
        if ($avatar !== '') {
=======
        if ('' !== $avatar) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $avatar;
        }

        // Corretto il controllo errato su $this
        $email = trim((string) $this->email);
        // 'MyEmailAddress@example.com'
        $email = mb_strtolower($email);
        // 'myemailaddress@example.com'
        $hash = hash('sha256', $email);
<<<<<<< HEAD
<<<<<<< HEAD

        return 'https://gravatar.com/avatar/'.$hash.'?s=64';
=======
        $avatar = 'https://gravatar.com/avatar/' . $hash . '?s=64';

        return $avatar;
>>>>>>> f548be94 (.)
=======
        $avatar = 'https://gravatar.com/avatar/' . $hash . '?s=64';

        return $avatar;
=======

        return 'https://gravatar.com/avatar/'.$hash.'?s=64';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        // https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80
        // in caso eseguire php artisan module:publish
        // dddx($this);
        // dddx(asset('blog/img/no_user.webp'));
        //    return asset('modules/blog/img/no_user.webp');
        // }
        // return $this->getFirstMediaUrl();
    }

    /**
     * Ottiene la lingua dell'utente.
     *
     * @return string Il codice della lingua
     */
    public function getUserLang(): string
    {
        $locale = config('app.locale');
        $defaultLocale = 'it';

<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $locale || ! is_string($locale)) {
=======
        if ($locale === null || !is_string($locale)) {
>>>>>>> f548be94 (.)
=======
        if ($locale === null || !is_string($locale)) {
=======
        if (null === $locale || ! is_string($locale)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $locale = $defaultLocale;
        }

        $userLang = $this->lang;

<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $userLang || ! is_string($userLang)) {
=======
        if ($userLang === null || !is_string($userLang)) {
>>>>>>> f548be94 (.)
=======
        if ($userLang === null || !is_string($userLang)) {
=======
        if (null === $userLang || ! is_string($userLang)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $locale;
        }

        return $userLang;
    }
<<<<<<< HEAD
<<<<<<< HEAD
    // use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(static function (self $model): void {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /** @return array<string, string> */
    #[\Override]
    protected function casts(): array
    {
        return [
=======
=======
>>>>>>> 87273113 (.)

    /** @return array<string, string> */
    #[Override]
    protected function casts(): array
    {
        return [
            'id' => 'string',
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    // use SoftDeletes;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(static function (self $model): void {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /** @return array<string, string> */
    #[\Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'uuid' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'updated_by' => 'string',
            'created_by' => 'string',
            'deleted_by' => 'string',
            'is_active' => 'boolean',
<<<<<<< HEAD
<<<<<<< HEAD
            'preferences' => 'json',
=======
>>>>>>> f548be94 (.)
=======
=======
            'preferences' => 'json',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'extra' => SchemalessAttributes::class,
        ];
    }
}
