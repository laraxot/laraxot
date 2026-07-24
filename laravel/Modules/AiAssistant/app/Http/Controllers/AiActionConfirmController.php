<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\AiAssistant\Actions\ExecuteAiActionProposalAction;
use Modules\AiAssistant\Models\AiActionProposal;

final class AiActionConfirmController extends Controller
{
    public function confirm(Request $request, string $proposalPublicId): JsonResponse
    {
        Gate::authorize('use-aiassistant');

        $user = $request->user();
        if (! $user) {
            return response()->json(['ok' => false, 'error' => 'Non autenticato.'], 401);
        }

        /** @var AiActionProposal|null $proposal */
        $proposal = AiActionProposal::query()
            ->where('public_id', $proposalPublicId)
            ->first();

        if (! ($proposal instanceof AiActionProposal)) {
            return response()->json(['ok' => false, 'error' => 'Proposta non trovata.'], 404);
        }

        if ((int) $proposal->proposed_by_user_id !== (int) $user->id) {
            return response()->json(['ok' => false, 'error' => 'Non autorizzato a confermare questa proposta.'], 403);
        }

        if ($proposal->status !== 'pending') {
            return response()->json([
                'ok' => false,
                'error' => 'Proposta non confermabile.',
                'status' => $proposal->status,
            ], 422);
        }

        $result = app(ExecuteAiActionProposalAction::class)->execute($proposal, (int) $user->id);

        return response()->json([
            'ok' => (bool) ($result['ok'] ?? false),
            'proposal_public_id' => (string) $proposal->public_id,
            'proposal_status' => (string) $proposal->refresh()->status,
            'result' => $result,
        ], ($result['ok'] ?? false) ? 200 : 422);
    }
}
