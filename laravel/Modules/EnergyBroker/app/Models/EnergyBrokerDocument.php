<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Customer\Models\Customer;
use Modules\Document\Models\Media;
use Modules\EnergyBroker\Database\Factories\EnergyBrokerDocumentFactory;

/**
 * @property int $id
 * @property string|null $public_id
 * @property int|null $energy_broker_import_batch_id
 * @property int|null $customer_id
 * @property int|null $media_id
 * @property string $source_file_name
 * @property string|null $file_hash
 * @property string $document_type
 * @property string|null $service_type
 * @property string|null $supplier_name
 * @property string $analysis_status
 * @property string|null $confidence_score
 * @property string|null $raw_text
 * @property array<array-key, mixed>|null $analysis_payload
 * @property array<array-key, mixed>|null $normalized_payload
 * @property array<array-key, mixed>|null $reviewed_payload
 * @property Carbon|null $processed_at
 * @property Carbon|null $imported_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class EnergyBrokerDocument extends BaseModel
{
    use SoftDeletes;

    protected $table = 'energy_broker_documents';

    /** @var array<int, string> */
    protected $guarded = [];

    protected static function booted(): void
    {
        self::creating(static function (self $document): void {
            if (! $document->public_id) {
                $document->public_id = (string) Str::uuid();
            }
        });
    }

    /** @return array<string, string> */
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

    /** @return BelongsTo<EnergyBrokerImportBatch, $this> */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(EnergyBrokerImportBatch::class, 'energy_broker_import_batch_id');
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /** @return BelongsTo<Media, $this> */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /** @return HasMany<EnergyBrokerCustomerMatch, $this> */
    public function customerMatches(): HasMany
    {
        return $this->hasMany(EnergyBrokerCustomerMatch::class, 'energy_broker_document_id');
    }
}
