<?php

declare(strict_types=1);

namespace Modules\AI\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/**
 * Class AiMessage.
 *
 * A single message (user|assistant|tool|system) within an AiThread.
 *
 * @property int $id
 * @property int $ai_thread_id
 * @property int|null $user_id
 * @property string $role
 * @property string|null $content
 * @property array<string, mixed>|null $payload
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AiThread $thread
 *
 * @method static Builder<static>|AiMessage newModelQuery()
 * @method static Builder<static>|AiMessage newQuery()
 * @method static Builder<static>|AiMessage query()
 *
 * @mixin \Eloquent
 */
class AiMessage extends XotBaseModel
{
    public const ROLE_USER = 'user';

    public const ROLE_ASSISTANT = 'assistant';

    public const ROLE_TOOL = 'tool';

    public const ROLE_SYSTEM = 'system';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'ai_thread_id',
        'user_id',
        'role',
        'content',
        'payload',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
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
