<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $energy_broker_supply_point_id
 * @property int|null $energy_broker_document_id
 * @property string $supplier_name
 * @property string|null $offer_name
 * @property string|null $offer_code
 * @property string $status
 * @property Carbon|null $started_at
 * @property Carbon|null $ended_at
 * @property array<array-key, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class EnergyBrokerSupplyHistory extends XotBaseModel
{
    protected $table = 'energy_broker_supply_histories';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'ended_at' => 'date',
            'meta' => 'array',
        ];
    }

    /** @return BelongsTo<EnergyBrokerSupplyPoint, $this> */
    public function supplyPoint(): BelongsTo
    {
        return $this->belongsTo(EnergyBrokerSupplyPoint::class, 'energy_broker_supply_point_id');
    }

    /** @return BelongsTo<EnergyBrokerDocument, $this> */
    public function document(): BelongsTo
    {
        return $this->belongsTo(EnergyBrokerDocument::class, 'energy_broker_document_id');
    }
}
