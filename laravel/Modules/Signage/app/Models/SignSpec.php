<?php

declare(strict_types=1);

namespace Modules\Signage\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Signage\Database\Factories\SignSpecFactory;

/**
 * The extracted head of a Signage sign drawing: title-block data plus the
 * decoded sign code. Owns the component rows that become quotation lines.
 *
 * @property int $id
 * @property string $source_file
 * @property string|null $drawing_title
 * @property string|null $customer_name
 * @property string|null $referent_name
 * @property string|null $project
 * @property string|null $brand
 * @property string|null $shop
 * @property string|null $sign_type_code
 * @property int|null $quantity
 * @property string|null $family
 * @property string|null $color_ral
 * @property numeric-string|null $thickness_mm
 * @property int|null $width_mm
 * @property int|null $height_mm
 * @property string|null $painting
 * @property string|null $finishing
 * @property string|null $lighting
 * @property string|null $scale
 * @property string|null $sheet_size
 * @property string|null $revision
 * @property string|null $creator
 * @property array<string, mixed>|null $layers
 * @property array<string, mixed>|null $notes
 * @property array<string, mixed>|null $raw_extraction
 * @property string|null $created_by_user_id
 * @property string|null $artwork_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class SignSpec extends BaseModel
{

    protected $table = 'sign_specs';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'thickness_mm' => 'decimal:2',
            'width_mm' => 'integer',
            'height_mm' => 'integer',
            'layers' => 'array',
            'notes' => 'array',
            'raw_extraction' => 'array',
        ];
    }

    /** @return HasMany<SignComponent, $this> */
    public function components(): HasMany
    {
        return $this->hasMany(SignComponent::class, 'sign_spec_id')->orderBy('position');
    }

    /** @return SignSpecFactory */
    public static function factory(): SignSpecFactory
    {
        return SignSpecFactory::new();
    }

    protected static function newFactory(): SignSpecFactory
    {
        return SignSpecFactory::new();
    }
}
