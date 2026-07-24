<?php

declare(strict_types=1);

namespace Modules\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

/**
 * @property int $id
 * @property string $key
 * @property array<string,mixed>|null $value_json
 * @property int|null $updated_by_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
final class PlatformSetting extends Model
{
    protected $table = 'platform_settings';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @var array<string, string> */
    protected $casts = [
        'value_json' => 'array',
    ];

    public static function getBool(string $key, bool $default = true): bool
    {
        /** @var array<string,mixed>|null $row */
        $row = self::query()->where('key', $key)->first()?->value_json;

        if ($row === null) {
            return $default;
        }

        return (bool) ($row['value'] ?? $default);
    }

    /** @return BelongsTo<User, $this> */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}

