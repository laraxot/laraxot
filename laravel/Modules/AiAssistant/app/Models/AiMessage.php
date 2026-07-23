<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Models;

use Modules\User\Models\User;
use Modules\Xot\Models\XotBaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AiMessage extends XotBaseModel
{
    protected $table = 'ai_messages';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    /** @return BelongsTo<AiThread, $this> */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(AiThread::class, 'ai_thread_id');
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

