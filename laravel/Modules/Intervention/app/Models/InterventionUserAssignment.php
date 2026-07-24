<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property int $intervention_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Intervention|null $intervention
 * @property-read User|null $user
 *
 * @method static \Modules\Intervention\Database\Factories\InterventionUserAssignmentFactory factory($count = null, $state = [])
 */
final class InterventionUserAssignment extends BaseModel
{
    protected $table = 'intervention_user_assignments';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return BelongsTo<Intervention, $this> */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
