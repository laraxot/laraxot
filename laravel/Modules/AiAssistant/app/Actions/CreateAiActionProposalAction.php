<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Actions;

use Illuminate\Support\Str;
use Modules\AiAssistant\Exceptions\AiActionNotAllowedException;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiThread;
use Modules\AiAssistant\Security\AiActionPolicy;
use Spatie\QueueableAction\QueueableAction;

final class CreateAiActionProposalAction
{
    use QueueableAction;

    public function __construct(
        private readonly AiActionPolicy $policy,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws AiActionNotAllowedException Quando la policy rifiuta il tipo o l'utente
     *                                     ha gia' superato il tetto nella finestra.
     */
    public function execute(
        AiThread $thread,
        int|string $proposedByUserId,
        string $type,
        array $payload,
        ?string $preview = null,
    ): AiActionProposal {
        $verdict = $this->policy->check($type, $this->recentProposalCount($proposedByUserId));

        if ($verdict['allowed'] === false) {
            throw new AiActionNotAllowedException($type, (string) $verdict['reason']);
        }

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

    /**
     * Proposte gia' consegnate a questo utente dentro la finestra. Il conteggio e'
     * per utente e non per thread: un agente che va in loop di solito apre thread
     * nuovi mentre gira, e un tetto per thread non verrebbe mai raggiunto.
     */
    private function recentProposalCount(int|string $userId): int
    {
        $configValue = config('ai_security.proposal_window_minutes', 10);
        $windowMinutes = max(1, is_int($configValue) ? $configValue : (is_numeric($configValue) ? (int) $configValue : 10));

        return AiActionProposal::query()
            ->where('proposed_by_user_id', $userId)
            ->where('created_at', '>=', now()->subMinutes($windowMinutes))
            ->count();
    }
}
