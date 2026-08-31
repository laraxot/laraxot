<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Modules\User\Models\User;
use Modules\Wts\Concerns\WtsAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $code
 * @property string $label
 * @property string $value
 * @property string|null $unit
 * @property string|null $description
 * @property string|null $updated_by_user_id
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsOperationalParameter extends Model
{
    /** @use HasFactory<\Modules\Wts\Database\Factories\WtsOperationalParameterFactory> */
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

    protected static function newFactory(): \Modules\Wts\Database\Factories\WtsOperationalParameterFactory
    {
        return \Modules\Wts\Database\Factories\WtsOperationalParameterFactory::new();
    }
}
