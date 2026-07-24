<?php

declare(strict_types=1);

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $notes
 *
 * @method static \Modules\Customer\Database\Factories\ResellerFactory factory($count = null, $state = [])
 */
final class Reseller extends BaseModel
{
    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
