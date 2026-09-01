<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\User;
use Modules\Wts\Concerns\WtsAuditable;
use Modules\Wts\Database\Factories\WtsOperationalParameterFactory;

/**
 * @property int $id
 * @property string $code
 * @property string $label
 * @property string $value
 * @property string|null $unit
 * @property string|null $description
 * @property string|null $updated_by_user_id
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsOperationalParameter extends BaseModel
{
    /** @use HasFactory<WtsOperationalParameterFactory> */
    use HasFactory;

    use SoftDeletes;
    use WtsAuditable;

    protected $table = 'wts_operational_parameters';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return BelongsTo<User, $this> */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    protected static function newFactory(): WtsOperationalParameterFactory
    {
        return WtsOperationalParameterFactory::new();
    }
}
