<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

// use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
// use Astrotomic\Translatable\Translatable;
use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Blog\Database\Factories\ProfileFactory;
use Modules\Media\Models\Media;
use Modules\Rating\Models\Rating;
use Modules\Rating\Models\RatingMorph;
use Modules\Rating\Models\Traits\HasRating;
use Modules\User\Models\BaseProfile;
use Modules\User\Models\Device;
use Modules\User\Models\DeviceUser;
use Modules\User\Models\Membership;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Modules\User\Models\Team;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\SchemalessAttributes\SchemalessAttributes;

/**
 * Modules\Blog\Models\Profile.
 *
 * @property float                                                      $credits
 * @property int                                                        $id
 * @property string|null                                                $user_id
 * @property string|null                                                $first_name
 * @property string|null                                                $last_name
 * @property string|null                                                $email
 * @property Carbon|null                                                $created_at
 * @property Carbon|null                                                $updated_at
 * @property string|null                                                $updated_by
 * @property string|null                                                $created_by
 * @property string|null                                                $deleted_at
 * @property string|null                                                $deleted_by
 * @property string|null                                                $slug
 * @property SchemalessAttributes|null                                  $extra
 * @property \Illuminate\Database\Eloquent\Collection<int, Article>     $articles
 * @property int|null                                                   $articles_count
 * @property string                                                     $avatar
 * @property \Illuminate\Database\Eloquent\Collection<int, DeviceUser>  $deviceUsers
 * @property int|null                                                   $device_users_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Device>      $devices
 * @property int|null                                                   $devices_count
 * @property string|null                                                $full_name
 * @property MediaCollection<int, Media>                                $media
 * @property int|null                                                   $media_count
 * @property \Illuminate\Database\Eloquent\Collection<int, DeviceUser>  $mobileDeviceUsers
 * @property int|null                                                   $mobile_device_users_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Device>      $mobileDevices
 * @property int|null                                                   $mobile_devices_count
 * @property DatabaseNotificationCollection<int, DatabaseNotification>  $notifications
 * @property int|null                                                   $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Permission>  $permissions
 * @property int|null                                                   $permissions_count
 * @property \Illuminate\Database\Eloquent\Collection<int, RatingMorph> $ratingMorphs
 * @property int|null                                                   $rating_morphs_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Rating>      $ratings
 * @property int|null                                                   $ratings_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Role>        $roles
 * @property int|null                                                   $roles_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Team>        $teams
 * @property int|null                                                   $teams_count
 * @property UserContract|null                                          $user
 * @property string|null                                                $user_name
 *
 * @method static ProfileFactory                                factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static EloquentBuilder|BaseProfile                   permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 * @method static EloquentBuilder|BaseProfile                   role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUserId($value)
 * @method static EloquentBuilder|BaseProfile                   withExtraAttributes()
 * @method static EloquentBuilder|BaseProfile                   withoutPermission($permissions)
 * @method static EloquentBuilder|BaseProfile                   withoutRole($roles, $guard = null)
 *
 * @property DeviceUser           $pivot
 * @property Membership           $membership
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property int                  $oauth_enable
 * @property int                  $credentials_enable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Profile          whereCredentialsEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile          whereOauthEnable($value)
 * @method static Profile|null                                           first()
 * @method static \Illuminate\Database\Eloquent\Collection<int, Profile> get()
 * @method static Profile                                                create(array<string, mixed> $attributes = [])
 * @method static Profile                                                firstOrCreate(array<string, mixed> $attributes = [], array<string, mixed> $values = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile  where((string|Closure) $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile  whereNotNull((string|Expression) $columns)
 * @method static int                                                    count(string $columns = '*')
 *
 * @property string               $display_name
 * @property ProfileContract|null $deleter
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static> byUuid(string $uuid)
 * @method static \Illuminate\Database\Eloquent\Builder<static> childrenWith(array<string, mixed> $relations)
 * @method static \Illuminate\Database\Eloquent\Builder<static> childrenWithCount(array<string, mixed> $relations)
 *
 * @mixin \Eloquent
 */
class Profile extends BaseProfile
{
    use HasRating;

    protected $connection = 'blog';

    /** @var list<string> */
    protected $fillable = [
        'id',
        'user_id',
        'email',
        'first_name',
        'last_name',
        'slug',
        'extra',
    ];

    /** @var list<string> */
    protected array $schemalessAttributes = [
        'extra',
    ];

    /**
     * @return HasMany<Article, $this>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Get the route key for the user.
     */
    public function getFrontRouteKeyName(): string
    {
        return 'slug';
    }

    public function getAvatarUrl(): string
    {
        $url = $this->getFirstMediaUrl('photo_profile');
        if ('' === $url) {
            return asset('modules/blog/img/no_user.webp');
        }

        return $url;
    }

    /*
    public function rating111s(): HasManyThrough
    {
        $firstKey = 'user_id';
        $secondKey = 'id';
        $localKey = 'user_id';
        $secondLocalKey = 'rating_id';

        return $this->hasManyThrough(Rating::class, RatingMorph::class, $firstKey, $secondKey, $localKey, $secondLocalKey);
        // ->withPivot(['value'])
    }

    public function ratingMorphs(): HasMany
    {
        return $this->hasMany(RatingMorph::class, 'user_id', 'user_id');
    }
    */
    /** @return Collection<int, int|string> */
    public function getArticleTraded(): Collection
    {
        /** @var Collection<int, int|string> $ids */
        $ids = RatingMorph::where('user_id', $this->user_id)
            ->groupBy('model_id')
            ->pluck('model_id')
            ->filter(static fn (mixed $id): bool => is_int($id) || is_string($id))
            ->map(static fn (mixed $id): int|string => is_int($id) ? $id : (string) $id)
            ->values();

        return $ids;
    }
}
