<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Models\XotBaseModel;

final class EnergyBrokerSupplyHistory extends XotBaseModel
{
    protected $table = 'energy_broker_supply_histories';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'ended_at' => 'date',
            'meta' => 'array',
        ];
    }

    public function supplyPoint(): BelongsTo
    {
        return $this->belongsTo(EnergyBrokerSupplyPoint::class, 'energy_broker_supply_point_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(EnergyBrokerDocument::class, 'energy_broker_document_id');
    }
}
