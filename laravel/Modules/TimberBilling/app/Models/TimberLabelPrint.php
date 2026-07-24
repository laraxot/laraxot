<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\TimberBilling\Database\Factories\TimberLabelPrintFactory;
use Modules\User\Models\User;

/**
 * @property string $label_code
 * @property string $qr_payload
 * @property string $print_reason_code
 * @property int|null $printed_by_user_id
 * @property Carbon $printed_at
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberLabelPrint extends Model
{
    /** @use HasFactory<TimberLabelPrintFactory> */
    use HasFactory;

    protected $table = 'timber_label_prints';

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

    protected static function newFactory(): TimberLabelPrintFactory
    {
        return TimberLabelPrintFactory::new();
    }
}
