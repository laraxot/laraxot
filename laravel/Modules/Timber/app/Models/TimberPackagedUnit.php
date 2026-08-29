<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberPackagedUnitFactory;

/**
 * @property int $derived_material_id
 * @property string $package_code
 * @property string $qr_code
 * @property string $package_type_code
 * @property string $quantity
 * @property string $unit_of_measure
 * @property string $status_code
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $article_code
 * @property string|null $traceable_code
 */
final class TimberPackagedUnit extends BaseModel
{
    /** @use HasFactory<TimberPackagedUnitFactory> */
    use HasFactory;

    protected $table = 'timber_packaged_units';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<TimberDerivedMaterial, $this> */
    public function derivedMaterial(): BelongsTo
    {
        return $this->belongsTo(TimberDerivedMaterial::class, 'derived_material_id');
    }

    /** @return MorphMany<TimberMovement, $this> */
    public function movements(): MorphMany
    {
        return $this->morphMany(TimberMovement::class, 'movable');
    }

    /** @return MorphMany<TimberLabelPrint, $this> */
    public function labelPrints(): MorphMany
    {
        return $this->morphMany(TimberLabelPrint::class, 'labelable');
    }

    protected static function newFactory(): TimberPackagedUnitFactory
    {
        return TimberPackagedUnitFactory::new();
    }
}
