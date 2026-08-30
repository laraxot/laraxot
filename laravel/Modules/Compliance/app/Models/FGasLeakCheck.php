<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Models\XotBaseModel;

/** One recorded leak check on a refrigerant circuit. */
final class FGasLeakCheck extends XotBaseModel
{
    protected $table = 'fgas_leak_checks';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'performed_on' => 'date',
            'next_due_on' => 'date',
            'gas_added_kg' => 'decimal:3',
            'gas_recovered_kg' => 'decimal:3',
        ];
    }

    /** @return BelongsTo<RefrigerantCircuit, $this> */
    public function circuit(): BelongsTo
    {
        return $this->belongsTo(RefrigerantCircuit::class, 'refrigerant_circuit_id');
    }
}
