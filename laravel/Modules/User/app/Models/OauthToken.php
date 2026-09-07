<?php

declare(strict_types=1);

namespace Modules\User\Models;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Carbon;
use Laravel\Passport\Token as PassportToken;
use Modules\User\Traits\ResolvesPassportTokenUserRelation;

/**
 * @property bool $revoked
 * @property int|string|null $user_id
 * @property string $id
 * @property string $client_id
 * @property string|null $name
 * @property array<array-key, mixed>|null $scopes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $expires_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @property OauthClient|null $client
 * @property OauthRefreshToken|null $refreshToken
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken existsIn(array<int, string> $haystack)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereScopes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereUserId($value)
 * @property-read \Modules\Xot\Contracts\UserContract|null $user
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Laravel\Passport\Token as PassportToken;
use Modules\Xot\Contracts\UserContract;

/**
 * Modules\User\Models\OauthAccessToken.
 *
 * @property string                 $id
 * @property string|null            $user_id
 * @property string                 $client_id
 * @property string|null            $name
 * @property array|null             $scopes
 * @property bool                   $revoked
 * @property Carbon|null            $created_at
 * @property Carbon|null            $updated_at
 * @property Carbon|null            $expires_at
 * @property string|null            $updated_by
 * @property string|null            $created_by
 * @property string|null            $deleted_at
 * @property string|null            $deleted_by
 * @property OauthClient|null       $client
 * @property UserContract|null      $user
 * @property OauthRefreshToken|null $refreshToken
 *
 * @method static Builder|OauthAccessToken newModelQuery()
 * @method static Builder|OauthAccessToken newQuery()
 * @method static Builder|OauthAccessToken query()
 * @method static Builder|OauthAccessToken whereClientId($value)
 * @method static Builder|OauthAccessToken whereCreatedAt($value)
 * @method static Builder|OauthAccessToken whereExpiresAt($value)
 * @method static Builder|OauthAccessToken whereId($value)
 * @method static Builder|OauthAccessToken whereName($value)
 * @method static Builder|OauthAccessToken whereRevoked($value)
 * @method static Builder|OauthAccessToken whereScopes($value)
 * @method static Builder|OauthAccessToken whereUpdatedAt($value)
 * @method static Builder|OauthAccessToken whereUserId($value)
 * @method static Builder|OauthAccessToken whereCreatedBy($value)
 * @method static Builder|OauthAccessToken whereDeletedAt($value)
 * @method static Builder|OauthAccessToken whereDeletedBy($value)
 * @method static Builder|OauthAccessToken whereUpdatedBy($value)
=======
use Illuminate\Support\Carbon;
use Laravel\Passport\Token as PassportToken;
use Modules\User\Traits\ResolvesPassportTokenUserRelation;

/**
 * @property bool $revoked
 * @property int|string|null $user_id
 * @property string $id
 * @property string $client_id
 * @property string|null $name
 * @property array<array-key, mixed>|null $scopes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $expires_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property string|null $deleted_at
 * @property string|null $deleted_by
 * @property OauthClient|null $client
 * @property OauthRefreshToken|null $refreshToken
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken existsIn(array<int, string> $haystack)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereRevoked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereScopes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OauthToken whereUserId($value)
>>>>>>> 2024e2e7 (.)
 *
>>>>>>> 60a2c9a9 (.)
 * @mixin \Eloquent
 */
class OauthToken extends PassportToken
{
<<<<<<< HEAD
<<<<<<< HEAD
    use ResolvesPassportTokenUserRelation;

=======
    /** @var string */
>>>>>>> 60a2c9a9 (.)
=======
    /** @var string */
=======
    use ResolvesPassportTokenUserRelation;

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    protected $connection = 'user';
}
