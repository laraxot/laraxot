<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Xot\Models\XotBaseModel;

final class EnergyBrokerLargeUpload extends XotBaseModel
{
    use SoftDeletes;

    protected $table = 'energy_broker_large_uploads';

    protected $guarded = [];

    protected static function booted(): void
    {
        self::creating(static function (self $upload): void {
            if (! $upload->public_id) {
                $upload->public_id = (string) str()->uuid();
            }
        });
    }

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

    public function batch(): BelongsTo
    {
        return $this->belongsTo(EnergyBrokerImportBatch::class, 'energy_broker_import_batch_id');
    }
}
