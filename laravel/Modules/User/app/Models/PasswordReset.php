<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Builder;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
=======
use Modules\Xot\Contracts\ProfileContract;
use Illuminate\Support\Carbon;
use Modules\User\Database\Factories\PasswordResetFactory;
>>>>>>> f548be94 (.)
=======
use Modules\Xot\Contracts\ProfileContract;
use Illuminate\Support\Carbon;
use Modules\User\Database\Factories\PasswordResetFactory;
=======
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\ProfileContract;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * Modules\User\Models\PasswordReset.
 *
<<<<<<< HEAD
<<<<<<< HEAD
 * @property int         $id
 * @property string      $email
 * @property string      $token
=======
 * @property int $id
 * @property string $email
 * @property string $token
>>>>>>> f548be94 (.)
=======
 * @property int $id
 * @property string $email
 * @property string $token
=======
 * @property int         $id
 * @property string      $email
 * @property string      $token
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $user_id
 * @property string|null $updated_by
 * @property string|null $created_by
<<<<<<< HEAD
<<<<<<< HEAD
=======
 * @method static PasswordResetFactory factory($count = null, $state = [])
>>>>>>> f548be94 (.)
=======
 * @method static PasswordResetFactory factory($count = null, $state = [])
=======
 *
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @method static Builder|PasswordReset newModelQuery()
 * @method static Builder|PasswordReset newQuery()
 * @method static Builder|PasswordReset query()
 * @method static Builder|PasswordReset whereCreatedAt($value)
 * @method static Builder|PasswordReset whereCreatedBy($value)
 * @method static Builder|PasswordReset whereEmail($value)
 * @method static Builder|PasswordReset whereId($value)
 * @method static Builder|PasswordReset whereToken($value)
 * @method static Builder|PasswordReset whereUpdatedAt($value)
 * @method static Builder|PasswordReset whereUpdatedBy($value)
 * @method static Builder|PasswordReset whereUserId($value)
<<<<<<< HEAD
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
<<<<<<< HEAD
 * @property string|null          $uuid
 * @method static Builder<static>|PasswordReset whereUuid($value)
 * @property ProfileContract|null $deleter
 * @method static \Modules\User\Database\Factories\PasswordResetFactory factory($count = null, $state = [])
=======
 * @property string|null $uuid
 * @method static Builder<static>|PasswordReset whereUuid($value)
 * @mixin IdeHelperPasswordReset
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
 *
 * @property ProfileContract|null $creator
 * @property ProfileContract|null $updater
 * @property string|null          $uuid
 *
 * @method static Builder<static>|PasswordReset whereUuid($value)
 *
 * @property ProfileContract|null $deleter
 *
 * @method static \Modules\User\Database\Factories\PasswordResetFactory factory($count = null, $state = [])
 *
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @mixin \Eloquent
 */
class PasswordReset extends BaseModel
{
    /**
     * @var list<string>
     *
     * @psalm-var list{'email', 'token', 'created_at', 'updated_at', 'created_by', 'updated_by'}
     */
    protected $fillable = ['email', 'token', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    /**
     * The table associated with the model.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @var string
>>>>>>> f548be94 (.)
=======
     *
     * @var string
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected $table = 'password_resets';
}

// end class PasswordReset
