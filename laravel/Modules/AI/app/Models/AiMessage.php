<?php

declare(strict_types=1);

namespace Modules\AI\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Models\XotBaseModel;

/**
 * Class AiMessage.
 *
 * A single message (user|assistant|tool|system) within an AiThread.
 *
 * @property-read AiThread|null $thread
 *
 * @method static Builder<static>|AiMessage newModelQuery()
 * @method static Builder<static>|AiMessage newQuery()
 * @method static Builder<static>|AiMessage query()
 *
 * @mixin \Eloquent
 */
class AiMessage extends XotBaseModel
{
    public const string ROLE_USER = 'user';

    public const string ROLE_ASSISTANT = 'assistant';

    public const string ROLE_TOOL = 'tool';

    public const string ROLE_SYSTEM = 'system';

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
