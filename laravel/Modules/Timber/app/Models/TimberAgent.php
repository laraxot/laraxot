<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberAgentFactory;

/**
 * @property string $full_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $zone
 * @property string|null $commission_percent
 * @property bool $is_active
 * @property string|null $notes
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class TimberAgent extends BaseModel
{

    use SoftDeletes;

    protected $table = 'timber_agents';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'commission_percent' => 'decimal:2',
            'is_active' => 'bool',
        ];
    }

    /** @return HasMany<TimberAgentCommission, $this> */
    public function commissions(): HasMany
    {
        return $this->hasMany(TimberAgentCommission::class, 'agent_id');
    }

    protected static function newFactory(): TimberAgentFactory
    {
        return TimberAgentFactory::new();
    }
}
