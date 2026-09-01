<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $public_id
 * @property string|null $uploaded_by_user_id
 * @property int|null $energy_broker_import_batch_id
 * @property string $original_name
 * @property string|null $mime_type
 * @property int $total_size
 * @property int $chunk_size
 * @property int $total_chunks
 * @property int $received_chunks
 * @property string $status
 * @property string|null $stored_path
 * @property string|null $file_hash
 * @property array<array-key, mixed>|null $received_chunk_indexes
 * @property array<array-key, mixed>|null $meta
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class EnergyBrokerLargeUpload extends BaseModel
{
    use SoftDeletes;

    protected $table = 'energy_broker_large_uploads';

    /** @var array<int, string> */
    protected $guarded = [];

    protected static function booted(): void
    {
        self::creating(static function (self $upload): void {
            if (! $upload->public_id) {
                $upload->public_id = (string) Str::uuid();
            }
        });
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'total_size' => 'integer',
            'chunk_size' => 'integer',
            'total_chunks' => 'integer',
            'received_chunks' => 'integer',
            'received_chunk_indexes' => 'array',
            'meta' => 'array',
            'completed_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<EnergyBrokerImportBatch, $this> */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(EnergyBrokerImportBatch::class, 'energy_broker_import_batch_id');
    }
}
