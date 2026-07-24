<?php

declare(strict_types=1);

namespace Modules\Signature\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Signature\Database\Factories\SketchFactory;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property string $sketchable_type
 * @property int $sketchable_id
 * @property int $user_id
 * @property string|null $disk
 * @property string|null $path
 * @property string|null $mime_type
 * @property array<string, mixed>|null $payload
 *
 * @method static SketchFactory factory($count = null, $state = [])
 */
final class Sketch extends BaseModel
{
    /** @use HasFactory<SketchFactory> */
    use HasFactory;

    protected $table = 'sketches';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    /**
     * Generic polymorphic owner of the sketch (e.g. Intervention, WorkOrder, ...).
     *
     * @return MorphTo<Model, $this>
     */
    public function sketchable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
