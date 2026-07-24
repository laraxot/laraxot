<?php

declare(strict_types=1);

namespace Modules\AI\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/**
 * Class AiToolLog.
 *
 * Audit trail of tool calls performed by the AI assistant.
 *
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
 * @property-read AiThread $thread
 * @property-read AiActionProposal|null $proposal
 *
 * @method static Builder<static>|AiToolLog newModelQuery()
 * @method static Builder<static>|AiToolLog newQuery()
 * @method static Builder<static>|AiToolLog query()
 *
 * @mixin \Eloquent
 */
class AiToolLog extends XotBaseModel
{
    public const STATUS_OK = 'ok';

    public const STATUS_ERROR = 'error';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'ai_thread_id',
        'ai_action_proposal_id',
        'user_id',
        'tool_name',
        'arguments',
        'response',
        'status',
        'error',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'arguments' => 'array',
            'response' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<AiThread, $this>
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(AiThread::class, 'ai_thread_id');
    }

    /**
     * @return BelongsTo<AiActionProposal, $this>
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(AiActionProposal::class, 'ai_action_proposal_id');
    }
}
