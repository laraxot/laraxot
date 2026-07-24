<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;
use Modules\Document\Models\Media;
use Modules\Xot\Models\XotBaseModel;

final class EnergyBrokerDocument extends XotBaseModel
{
    use SoftDeletes;

    protected $table = 'energy_broker_documents';

    protected $guarded = [];

    protected static function booted(): void
    {
        self::creating(static function (self $document): void {
            if (! $document->public_id) {
                $document->public_id = (string) str()->uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'confidence_score' => 'decimal:4',
            'analysis_payload' => 'array',
            'normalized_payload' => 'array',
            'reviewed_payload' => 'array',
            'processed_at' => 'datetime',
            'imported_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(EnergyBrokerImportBatch::class, 'energy_broker_import_batch_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function customerMatches(): HasMany
    {
        return $this->hasMany(EnergyBrokerCustomerMatch::class, 'energy_broker_document_id');
    }
}
