<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $labelable_type
 * @property int $labelable_id
 * @property string $label_code
 * @property string $qr_payload
 * @property string $print_reason_code
 * @property string|null $printed_by_user_id
 * @property \Carbon\CarbonInterface $printed_at
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsLabelPrint extends Model
{
    /** @use HasFactory<\Modules\Wts\Database\Factories\WtsLabelPrintFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $table = 'wts_label_prints';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'printed_at' => 'datetime',
        ];
    }

    /** @return MorphTo<Model, $this> */
    public function labelable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return BelongsTo<User, $this> */
    public function printedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'printed_by_user_id');
    }

    protected static function newFactory(): \Modules\Wts\Database\Factories\WtsLabelPrintFactory
    {
        return \Modules\Wts\Database\Factories\WtsLabelPrintFactory::new();
    }
}
