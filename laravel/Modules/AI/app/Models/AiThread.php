<?php

declare(strict_types=1);

namespace Modules\AI\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Xot\Models\XotBaseModel;

/**
 * Class AiThread.
 *
 * A persisted conversation thread between a user and the AI assistant.
 *
 * @property int $id
 * @property string $public_id
 * @property string $created_by_user_id
 * @property string $panel_id
 * @property \Carbon\Carbon|null $last_message_at
 * @property array<string, mixed>|null $meta
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read Collection<int, AiMessage> $messages
 * @property-read int|null $messages_count
 * @property-read Collection<int, AiActionProposal> $proposals
 * @property-read int|null $proposals_count
 * @property-read Collection<int, AiToolLog> $toolLogs
 * @property-read int|null $tool_logs_count
 *
 * @method static Builder<static>|AiThread newModelQuery()
 * @method static Builder<static>|AiThread newQuery()
 * @method static Builder<static>|AiThread query()
 *
 * @mixin \Eloquent
 */
class AiThread extends XotBaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'public_id',
        'created_by_user_id',
        'panel_id',
        'last_message_at',
        'meta',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'public_id' => 'string',
            'last_message_at' => 'datetime',
            'meta' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<AiMessage, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(AiMessage::class);
    }

    /**
     * @return HasMany<AiActionProposal, $this>
     */
    public function proposals(): HasMany
    {
        return $this->hasMany(AiActionProposal::class);
    }

    /**
     * @return HasMany<AiToolLog, $this>
     */
    public function toolLogs(): HasMany
    {
        return $this->hasMany(AiToolLog::class);
    }
}
