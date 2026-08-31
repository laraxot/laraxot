<?php

declare(strict_types=1);

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Document\Models\Media;
use Modules\HR\Database\Factories\EmployeeMedicalVisitFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $employee_id
 * @property int|null $media_id
 * @property string|null $title
 * @property Carbon $visited_at
 * @property Carbon|null $expires_at
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Media|null $media
 */
final class EmployeeMedicalVisit extends XotBaseModel
{

    protected $table = 'employee_medical_visits';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'visited_at' => 'date',
            'expires_at' => 'date',
        ];
    }

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
