<?php

declare(strict_types=1);

namespace Modules\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property Carbon $created_at
 * @property string|null $actor_user_id
 * @property string $action
 * @property string $subject_type
 * @property string $subject_id
 * @property string|null $panel_id
 * @property string|null $route
 * @property string|null $ip
 * @property string|null $user_agent
 * @property array<string,mixed>|null $before
 * @property array<string,mixed>|null $after
 */
final class AuditLog extends BaseModel
{
    protected $table = 'audit_logs';

    public $timestamps = false;

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'before' => 'array',
            'after' => 'array',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    /** @return MorphTo<Model, $this> */
    public function subject(): MorphTo
    {
        return $this->morphTo(name: 'subject');
    }
}
