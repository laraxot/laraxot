<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\TimberBilling\Database\Factories\TimberAgentCommissionFactory;

/**
 * @property int $agent_id
 * @property string $scope_type
 * @property string|null $category
 * @property int|null $product_id
 * @property string $rate_percent
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberAgentCommission extends BaseModel
{
    /** @use HasFactory<TimberAgentCommissionFactory> */
    use HasFactory;

    protected $table = 'timber_agent_commissions';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'rate_percent' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<TimberAgent, $this> */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(TimberAgent::class, 'agent_id');
    }

    /** @return BelongsTo<TimberProduct, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(TimberProduct::class, 'product_id');
    }

    protected static function newFactory(): TimberAgentCommissionFactory
    {
        return TimberAgentCommissionFactory::new();
    }
}
