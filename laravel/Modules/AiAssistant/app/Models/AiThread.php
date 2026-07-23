<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Models;

use Modules\User\Models\User;
use Modules\Xot\Models\XotBaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class AiThread extends XotBaseModel
{
    protected $table = 'ai_threads';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'public_id' => 'string',
            'last_message_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /** @return HasMany<AiMessage, $this> */
    public function messages(): HasMany
    {
        return $this->hasMany(AiMessage::class);
    }

    /** @return HasMany<AiActionProposal, $this> */
    public function proposals(): HasMany
    {
        return $this->hasMany(AiActionProposal::class);
    }

    /** @return HasMany<AiToolLog, $this> */
    public function toolLogs(): HasMany
    {
        return $this->hasMany(AiToolLog::class);
    }
}

