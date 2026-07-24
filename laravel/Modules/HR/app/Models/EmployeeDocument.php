<?php

declare(strict_types=1);

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Document\Models\Media;

/**
 * @property int $id
 * @property int $employee_id
 * @property int $media_id
 * @property string|null $title
 * @property string|null $kind
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Media $media
 */
final class EmployeeDocument extends Model
{
    protected $table = 'employee_documents';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return BelongsTo<Employee, $this> */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /** @return BelongsTo<Media, $this> */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
