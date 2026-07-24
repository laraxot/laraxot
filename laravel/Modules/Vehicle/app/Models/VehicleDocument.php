<?php

declare(strict_types=1);

namespace Modules\Vehicle\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Document\Models\Media;
use Modules\Vehicle\Database\Factories\VehicleDocumentFactory;

/**
 * @property int $id
 * @property string|null $uuid
 * @property int $vehicle_id
 * @property int $media_id
 * @property string|null $title
 *
 * @method static VehicleDocumentFactory factory($count = null, $state = [])
 */
final class VehicleDocument extends BaseModel
{
    /** @use HasFactory<VehicleDocumentFactory> */
    use HasFactory;

    protected $table = 'vehicle_documents';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** @return BelongsTo<Media, $this> */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
