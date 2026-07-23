<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Customer\Models\Customer;

final class EnergyBrokerCustomerMatch extends Model
{
    protected $table = 'energy_broker_customer_matches';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'matched_on' => 'array',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(EnergyBrokerDocument::class, 'energy_broker_document_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
