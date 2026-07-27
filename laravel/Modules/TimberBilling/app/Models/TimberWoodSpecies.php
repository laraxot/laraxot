<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Modules\TimberBilling\Database\Factories\TimberWoodSpeciesFactory;

/**
 * @property string $code
 * @property string $name
 * @property int $sort_order
 * @property bool $is_active
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberWoodSpecies extends BaseModel
{
    /** @use HasFactory<TimberWoodSpeciesFactory> */
    use HasFactory;

    protected $table = 'timber_wood_species';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
        ];
    }

    protected static function newFactory(): TimberWoodSpeciesFactory
    {
        return TimberWoodSpeciesFactory::new();
    }
}
