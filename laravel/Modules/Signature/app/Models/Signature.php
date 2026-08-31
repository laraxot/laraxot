<?php

declare(strict_types=1);

namespace Modules\Signature\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Signature\Database\Factories\SignatureFactory;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property string $signable_type
 * @property int $signable_id
 * @property int $user_id
 * @property string|null $disk
 * @property string|null $path
 * @property string|null $mime_type
 * @property Carbon|null $signed_at
 * @property array<string, mixed>|null $payload
 *
 * @method static SignatureFactory factory($count = null, $state = [])
 */
final class Signature extends BaseModel
{

    protected $table = 'signatures';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
            'payload' => 'array',
        ];
    }

    /**
     * Generic polymorphic owner of the signature (e.g. Intervention, WorkOrder, ...).
     *
     * @return MorphTo<Model, $this>
     */
    public function signable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
