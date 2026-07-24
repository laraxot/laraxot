<?php

declare(strict_types=1);

namespace Modules\AI\Actions;

use Modules\AI\Models\AiActionProposal;
use Modules\AI\Models\AiThread;
use Ramsey\Uuid\Uuid;
use Spatie\QueueableAction\QueueableAction;

/**
 * Creates a new pending `AiActionProposal` for a thread.
 */
class CreateAiActionProposalAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function execute(
        AiThread $thread,
        int $proposedByUserId,
        string $type,
        array $payload,
        ?string $preview = null,
    ): AiActionProposal {
        /** @var AiActionProposal $proposal */
        $proposal = AiActionProposal::query()->create([
            'public_id' => Uuid::uuid4()->toString(),
            'ai_thread_id' => $thread->getKey(),
            'proposed_by_user_id' => $proposedByUserId,
            'type' => $type,
            'payload' => $payload,
            'preview' => $preview,
            'status' => AiActionProposal::STATUS_PENDING,
        ]);

        return $proposal;
    }
}
