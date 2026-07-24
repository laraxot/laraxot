<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\User;
use Modules\Xot\Models\XotBaseModel;

final class EnergyBrokerImportBatch extends XotBaseModel
{
    use SoftDeletes;

    protected $table = 'energy_broker_import_batches';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EnergyBrokerDocument::class, 'energy_broker_import_batch_id');
    }
}
