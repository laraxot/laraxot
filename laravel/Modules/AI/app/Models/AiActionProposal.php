<?php

declare(strict_types=1);

namespace Modules\AI\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/**
 * Class AiActionProposal.
 *
 * An action the AI proposes to execute on the domain, subject to human
 * confirmation before being executed. Status lifecycle:
 * pending -> confirmed -> executed
 *        \-> cancelled
 *        \-> failed
 *
 * @property int $id
 * @property string $public_id
 * @property int $ai_thread_id
 * @property string $proposed_by_user_id
 * @property string $type
 * @property array<string, mixed> $payload
 * @property string|null $preview
 * @property string $status
 * @property string|null $confirmed_by_user_id
 * @property Carbon|null $confirmed_at
 * @property Carbon|null $executed_at
 * @property array<string, mixed>|null $result
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AiThread|null $thread
 *
 * @method static Builder<static>|AiActionProposal newModelQuery()
 * @method static Builder<static>|AiActionProposal newQuery()
 * @method static Builder<static>|AiActionProposal query()
 *
 * @mixin \Eloquent
 */
class AiActionProposal extends XotBaseModel
{
    public const string STATUS_PENDING = 'pending';

    public const string STATUS_CANCELLED = 'cancelled';

    public const string STATUS_CONFIRMED = 'confirmed';

    public const string STATUS_EXECUTED = 'executed';

    public const string STATUS_FAILED = 'failed';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'public_id',
        'ai_thread_id',
        'proposed_by_user_id',
        'type',
        'payload',
        'preview',
        'status',
        'confirmed_by_user_id',
        'confirmed_at',
        'executed_at',
        'result',
        'error',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'public_id' => 'string',
            'payload' => 'array',
            'result' => 'array',
            'confirmed_at' => 'datetime',
            'executed_at' => 'datetime',
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
}
