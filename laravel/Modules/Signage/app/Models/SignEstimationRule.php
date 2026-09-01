<?php

declare(strict_types=1);

namespace Modules\Signage\Models;

use Illuminate\Support\Carbon;
use Modules\Signage\Database\Factories\SignEstimationRuleFactory;

/**
 * How to derive a component quantity from the drawing geometry:
 *   quantity_per_piece = base(strategy) * factor + base_value  (floored at min_per_piece)
 *   quantity           = quantity_per_piece * pieces
 * where base() is the sheet area (m²), the bounding-box perimeter (m) or 0.
 *
 * @property int $id
 * @property string $role_code
 * @property string $unit_code
 * @property string $strategy_code
 * @property numeric-string $factor
 * @property numeric-string $base_value
 * @property numeric-string|null $min_per_piece
 * @property string|null $notes
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class SignEstimationRule extends BaseModel
{

    protected $table = 'sign_estimation_rules';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'factor' => 'decimal:4',
            'base_value' => 'decimal:4',
            'min_per_piece' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    protected static function newFactory(): SignEstimationRuleFactory
    {
        return SignEstimationRuleFactory::new();
    }
}
