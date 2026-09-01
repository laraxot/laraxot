<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Wts\Database\Factories\WtsPrintJobFactory;

/**
 * @property int $id
 * @property int|null $wts_unit_id
 * @property string|null $label_code
 * @property string $zpl
 * @property int $copies
 * @property string $status_code
 * @property int $attempts
 * @property string|null $error
 * @property CarbonInterface|null $printed_at
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsPrintJob extends BaseModel
{
    /** @use HasFactory<WtsPrintJobFactory> */
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

    protected static function newFactory(): WtsPrintJobFactory
    {
        return WtsPrintJobFactory::new();
    }
}
