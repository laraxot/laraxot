<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

/**
 * @property int $id
 * @property int $wts_processing_step_id
 * @property int $wts_unit_id
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 */
final class WtsProcessingStepUnit extends BasePivot
{
    protected $table = 'wts_processing_step_unit';

    /** @var array<int, string> */
    protected $guarded = [];
}
