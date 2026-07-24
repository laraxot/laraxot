<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property int $ai_thread_id
 * @property int|null $ai_action_proposal_id
 * @property int|null $user_id
 * @property string $tool_name
 * @property array<string, mixed>|null $arguments
 * @property array<string, mixed>|null $response
 * @property string $status
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class AiToolLog extends BaseModel
{
    protected $table = 'ai_tool_logs';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'arguments' => 'array',
            'response' => 'array',
        ];
    }

    /** @return BelongsTo<AiThread, $this> */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(AiThread::class, 'ai_thread_id');
    }

    /** @return BelongsTo<AiActionProposal, $this> */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(AiActionProposal::class, 'ai_action_proposal_id');
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
