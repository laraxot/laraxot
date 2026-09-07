<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;

/** A sealed refrigerant circuit subject to the F-Gas leak-check regime. */
final class RefrigerantCircuit extends BaseModel
{
    use SoftDeletes;

    protected $table = 'refrigerant_circuits';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'charge_kg' => 'decimal:3',
            'co2_equivalent_tonnes' => 'decimal:3',
            'is_hermetically_sealed' => 'boolean',
            'has_leak_detection_system' => 'boolean',
            'last_leak_check_on' => 'date',
            'next_leak_check_due_on' => 'date',
            'installation_date' => 'date',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** @return BelongsTo<HeatingSystem, $this> */
    public function heatingSystem(): BelongsTo
    {
        return $this->belongsTo(HeatingSystem::class, 'heating_system_id');
    }

    /** @return HasMany<FGasLeakCheck, $this> */
    public function leakChecks(): HasMany
    {
        return $this->hasMany(FGasLeakCheck::class, 'refrigerant_circuit_id');
    }
}
