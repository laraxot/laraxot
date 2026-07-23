<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiThread;

final class AiProposalsController extends Controller
{
    public function index(Request $request, string $threadPublicId): JsonResponse
    {
        Gate::authorize('use-aiassistant');

        $user = $request->user();
        if (! $user) {
            return response()->json(['ok' => false, 'error' => 'Non autenticato.'], 401);
        }

        /** @var AiThread|null $thread */
        $thread = AiThread::query()
            ->where('public_id', $threadPublicId)
            ->where('created_by_user_id', $user->id)
            ->first();

        if (! ($thread instanceof AiThread)) {
            return response()->json(['ok' => false, 'error' => 'Thread non trovato.'], 404);
        }

        $proposals = AiActionProposal::query()
            ->where('ai_thread_id', $thread->id)
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->limit(20)
            ->get([
                'public_id',
                'type',
                'preview',
                'payload',
                'status',
                'created_at',
            ])
            ->map(fn (AiActionProposal $p): array => [
                'public_id' => (string) $p->public_id,
                'type' => (string) $p->type,
                'type_label' => $this->proposalTypeLabel((string) $p->type),
                'preview' => (string) ($p->preview ?? ''),
                'payload' => (array) ($p->payload ?? []),
                'status' => (string) $p->status,
                'created_at' => $p->created_at instanceof \Carbon\CarbonInterface
                    ? $p->created_at->toIso8601String()
                    : null,
            ])
            ->all();

        return response()->json([
            'ok' => true,
            'thread' => (string) $thread->public_id,
            'proposals' => $proposals,
        ]);
    }

    private function proposalTypeLabel(string $type): string
    {
        return match ($type) {
            'start_time_punch' => 'Avvio timbratura',
            'stop_time_punch' => 'Stop timbratura',
            'upsert_daily_note' => 'Nota giornaliera',
            'change_work_order_status' => 'Cambio stato commessa',
            'finalize_intervention_report' => 'Chiusura intervento',
            'create_inventory_movement' => 'Movimento di magazzino',
            'allocate_inventory_to_work_order' => 'Materiale su commessa',
            'allocate_inventory_to_intervention' => 'Materiale su intervento',
            'create_production_order' => 'Ordine di produzione',
            'advance_production_phase' => 'Avanzamento produzione',
            'create_compliance_document' => 'Documento compliance',
            'create_work_order_change_order' => 'Extra lavoro',
            'generate_pos_draft' => 'Bozza POS',
            'apply_public_procurement_tender_draft' => 'Import gara pubblica',
            'create_public_procurement_contract_from_opportunity' => 'Crea appalto pubblico',
            'import_public_procurement_price_items' => 'Import computo appalto',
            'confirm_energy_broker_document_import' => 'Import documento Energy Broker',
            default => 'Azione proposta',
        };
    }
}

