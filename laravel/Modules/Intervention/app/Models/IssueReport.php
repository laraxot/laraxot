<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Intervention\Database\Factories\IssueReportFactory;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property int $intervention_id
 * @property int|null $created_by_user_id
 * @property string $body
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Intervention|null $intervention
 * @property-read User|null $createdBy
 *
 * @method static IssueReportFactory factory($count = null, $state = [])
 */
final class IssueReport extends BaseModel
{
    /** @use HasFactory<IssueReportFactory> */
    use HasFactory;

    protected $table = 'issue_reports';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return BelongsTo<Intervention, $this> */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
