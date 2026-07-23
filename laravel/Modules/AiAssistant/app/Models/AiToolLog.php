<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Models;

use Modules\User\Models\User;
use Modules\Xot\Models\XotBaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AiToolLog extends XotBaseModel
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

