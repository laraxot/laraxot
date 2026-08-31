<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $wts_unit_id
 * @property string|null $label_code
 * @property string $zpl
 * @property int $copies
 * @property string $status_code
 * @property int $attempts
 * @property string|null $error
 * @property \Carbon\CarbonInterface|null $printed_at
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsPrintJob extends Model
{
    /** @use HasFactory<\Modules\Wts\Database\Factories\WtsPrintJobFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $table = 'wts_print_jobs';

    /** @var array<int, string> */
    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'copies' => 'integer',
            'attempts' => 'integer',
            'printed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<WtsUnit, $this> */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(WtsUnit::class, 'wts_unit_id');
    }

    protected static function newFactory(): \Modules\Wts\Database\Factories\WtsPrintJobFactory
    {
        return \Modules\Wts\Database\Factories\WtsPrintJobFactory::new();
    }
}
