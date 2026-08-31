<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberMovementFactory;
use Modules\User\Models\User;

/**
 * @property string|null $from_area_code
 * @property string $to_area_code
 * @property int|null $moved_by_user_id
 * @property Carbon $moved_at
 * @property string|null $notes
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberMovement extends BaseModel
{

    protected $table = 'timber_movements';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'moved_at' => 'datetime',
        ];
    }

    /** @return MorphTo<Model, $this> */
    public function movable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<User, $this> */
    public function movedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moved_by_user_id');
    }

    protected static function newFactory(): TimberMovementFactory
    {
        return TimberMovementFactory::new();
    }
}
