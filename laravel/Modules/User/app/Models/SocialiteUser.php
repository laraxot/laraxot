<?php

declare(strict_types=1);

/**
 * inspired by  DutchCodingCompany\FilamentSocialite.
 */

namespace Modules\User\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\UserContract;
use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Contracts\ProfileContract;
use Modules\User\Database\Factories\SocialiteUserFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Datas\XotData;

/**
 * Modules\User\Models\SocialiteUser.
 *
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int               $id
 * @property string            $user_id
 * @property string            $provider
 * @property string            $provider_id
 * @property string|null       $token
 * @property string|null       $name
 * @property string|null       $email
 * @property string|null       $avatar
 * @property Carbon|null       $created_at
 * @property Carbon|null       $updated_at
 * @property string|null       $updated_by
 * @property string|null       $created_by
=======
=======
>>>>>>> 87273113 (.)
 * @property int $id
 * @property string $user_id
 * @property string $provider
 * @property string $provider_id
 * @property string|null $token
 * @property string|null $name
 * @property string|null $email
 * @property string|null $avatar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
>>>>>>> f548be94 (.)
 * @property UserContract|null $user
=======
 * @property int               $id
 * @property string            $user_id
 * @property string            $provider
 * @property string            $provider_id
 * @property string|null       $token
 * @property string|null       $name
 * @property string|null       $email
 * @property string|null       $avatar
 * @property Carbon|null       $created_at
 * @property Carbon|null       $updated_at
 * @property string|null       $updated_by
 * @property string|null       $created_by
 * @property UserContract|null $user
 *
>>>>>>> 2024e2e7 (.)
 * @method static Builder|SocialiteUser newModelQuery()
 * @method static Builder|SocialiteUser newQuery()
 * @method static Builder|SocialiteUser query()
 * @method static Builder|SocialiteUser whereAvatar($value)
 * @method static Builder|SocialiteUser whereCreatedAt($value)
 * @method static Builder|SocialiteUser whereCreatedBy($value)
 * @method static Builder|SocialiteUser whereEmail($value)
 * @method static Builder|SocialiteUser whereId($value)
 * @method static Builder|SocialiteUser whereName($value)
 * @method static Builder|SocialiteUser whereProvider($value)
 * @method static Builder|SocialiteUser whereProviderId($value)
 * @method static Builder|SocialiteUser whereToken($value)
 * @method static Builder|SocialiteUser whereUpdatedAt($value)
 * @method static Builder|SocialiteUser whereUpdatedBy($value)
 * @method static Builder|SocialiteUser whereUserId($value)
<<<<<<< HEAD
 * @property string $uuid (DC2Type:guid)
 * @method static Builder|SocialiteUser whereUuid($value)
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
<<<<<<< HEAD
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\SocialiteUserFactory factory($count = null, $state = [])
=======
 * @mixin IdeHelperSocialiteUser
 * @method static SocialiteUserFactory factory($count = null, $state = [])
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 *
 * @property string $uuid (DC2Type:guid)
 *
 * @method static Builder|SocialiteUser whereUuid($value)
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property ProfileContract|null $deleter
 *
 * @method static \Modules\User\Database\Factories\SocialiteUserFactory factory($count = null, $state = [])
 *
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @mixin \Eloquent
 */
class SocialiteUser extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        // 'id',
        'user_id',
        'provider',
        'provider_id',
        'token',
        'name',
        'email',
        'avatar',
    ];

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return BelongsTo<Model, $this>
     */
=======
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return BelongsTo<Model, $this>
     */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function user(): BelongsTo
    {
        /** @var class-string<Model> */
        $user_class = XotData::make()->getUserClass();

        return $this->belongsTo($user_class);
    }
}
