<?php

declare(strict_types=1);

namespace Modules\Email\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\Email\Database\Factories\EmailMessageFactory;
use Modules\Intervention\Models\Intervention;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * @property int $id
 * @property int $conversation_id
 * @property int|null $work_order_id
 * @property int|null $intervention_id
 * @property int|null $ai_action_proposal_id
 * @property string|null $external_message_id
 * @property string $direction
 * @property string|null $from_email
 * @property string|null $from_name
 * @property string|null $to_email
 * @property string|null $subject
 * @property string|null $body
 * @property string $status
 * @property string $processing_status
 * @property array<string, mixed>|null $payload
 * @property array<string, mixed>|null $ai_result
 * @property string|null $error
 * @property Carbon|null $received_at
 * @property Carbon|null $sent_at
 * @property Carbon|null $processed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Modules\Email\Database\Factories\EmailMessageFactory factory($count = null, $state = [])
 */
final class EmailMessage extends BaseModel
{
    protected $table = 'email_messages';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'ai_result' => 'array',
            'received_at' => 'datetime',
            'sent_at' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<EmailConversation, $this> */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(EmailConversation::class, 'conversation_id');
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    /** @return BelongsTo<Intervention, $this> */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class, 'intervention_id');
    }

    /** @return BelongsTo<AiActionProposal, $this> */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(AiActionProposal::class, 'ai_action_proposal_id');
    }

    protected static function newFactory(): EmailMessageFactory
    {
        return EmailMessageFactory::new();
    }
}
