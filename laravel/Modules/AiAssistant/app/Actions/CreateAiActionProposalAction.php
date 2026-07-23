<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Actions;

use Illuminate\Support\Str;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiThread;

final class CreateAiActionProposalAction
{
    /**
     * @param array<string, mixed> $payload
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
            'public_id' => (string) Str::uuid(),
            'ai_thread_id' => $thread->id,
            'proposed_by_user_id' => $proposedByUserId,
            'type' => $type,
            'payload' => $payload,
            'preview' => $preview,
            'status' => 'pending',
        ]);

        return $proposal;
    }
}

