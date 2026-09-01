<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property string|null $uploaded_by_user_id
 * @property string $source_name
 * @property string $source_type
 * @property string $status
 * @property int $total_files
 * @property int $processed_files
 * @property array<array-key, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class EnergyBrokerImportBatch extends BaseModel
{
    use SoftDeletes;

    protected $table = 'energy_broker_import_batches';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    /** @return HasMany<EnergyBrokerDocument, $this> */
    public function documents(): HasMany
    {
        return $this->hasMany(EnergyBrokerDocument::class, 'energy_broker_import_batch_id');
    }
}
