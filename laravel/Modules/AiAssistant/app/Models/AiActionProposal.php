<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property string $public_id
 * @property int $ai_thread_id
 * @property int $proposed_by_user_id
 * @property string $type
 * @property array<string, mixed> $payload
 * @property string|null $preview
 * @property string $status
 * @property int|null $confirmed_by_user_id
 * @property Carbon|null $confirmed_at
 * @property Carbon|null $executed_at
 * @property array<string, mixed>|null $result
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class AiActionProposal extends BaseModel
{
    protected $table = 'ai_action_proposals';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'public_id' => 'string',
            'payload' => 'array',
            'result' => 'array',
            'confirmed_at' => 'datetime',
            'executed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<AiThread, $this> */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(AiThread::class, 'ai_thread_id');
    }

    /** @return BelongsTo<User, $this> */
    public function proposedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proposed_by_user_id');
    }

    /** @return BelongsTo<User, $this> */
    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by_user_id');
    }
}
