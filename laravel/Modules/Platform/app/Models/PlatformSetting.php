<?php

declare(strict_types=1);

namespace Modules\Platform\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\User\Models\User;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property string $key
 * @property array<string,mixed>|null $value_json
 * @property string|null $updated_by_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class PlatformSetting extends XotBaseModel
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
