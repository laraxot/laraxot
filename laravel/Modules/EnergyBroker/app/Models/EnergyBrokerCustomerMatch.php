<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Customer;

/**
 * @property int $id
 * @property int $energy_broker_document_id
 * @property int|null $customer_id
 * @property string $score
 * @property string $status
 * @property array<array-key, mixed>|null $matched_on
 * @property string|null $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class EnergyBrokerCustomerMatch extends BaseModel
{
    protected $table = 'energy_broker_customer_matches';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'matched_on' => 'array',
        ];
    }

    /** @return BelongsTo<EnergyBrokerDocument, $this> */
    public function document(): BelongsTo
    {
        return $this->belongsTo(EnergyBrokerDocument::class, 'energy_broker_document_id');
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
