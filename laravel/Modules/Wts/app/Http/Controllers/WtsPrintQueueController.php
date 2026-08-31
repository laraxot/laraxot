<?php

declare(strict_types=1);

namespace Modules\Wts\Http\Controllers;

use Modules\Wts\Models\WtsPrintJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Coda di stampa etichette WTS per l'agente locale (Mojito sul PC di impianto):
 * l'agente PESCA i job ZPL in attesa e segna l'esito. Protetta da token bearer
 * (config wts.print_agent_token). Nessun accesso da browser: solo agente.
 */
final class WtsPrintQueueController
{
    /**
     * Job in attesa di stampa (ZPL pronto).
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorizeAgent($request);

        $jobs = WtsPrintJob::query()
            ->where('status_code', 'pending')
            ->orderBy('id')
            ->limit(50)
            ->get()
            ->map(static fn (WtsPrintJob $job): array => [
                'id' => $job->id,
                'label_code' => $job->label_code,
                'copies' => $job->copies,
                'zpl' => $job->zpl,
            ])
            ->all();

        return response()->json(['jobs' => $jobs]);
    }

    /**
     * L'agente conferma la stampa avvenuta.
     */
    public function markPrinted(Request $request, WtsPrintJob $printJob): JsonResponse
    {
        $this->authorizeAgent($request);

        $printJob->forceFill([
            'status_code' => 'printed',
            'printed_at' => now(),
            'attempts' => $printJob->attempts + 1,
        ])->save();

        return response()->json(['ok' => true]);
    }

    /**
     * L'agente segnala un errore di stampa (resta tracciato, non riprovato in automatico).
     */
    public function markFailed(Request $request, WtsPrintJob $printJob): JsonResponse
    {
        $this->authorizeAgent($request);

        $error = $request->string('error')->trim()->toString();

        $printJob->forceFill([
            'status_code' => 'failed',
            'error' => $error !== '' ? $error : null,
            'attempts' => $printJob->attempts + 1,
        ])->save();

        return response()->json(['ok' => true]);
    }

    private function authorizeAgent(Request $request): void
    {
        $configured = config('wts.print_agent_token', '');
        $token = is_string($configured) ? $configured : '';

        abort_if(
            $token === '' || ! hash_equals($token, (string) $request->bearerToken()),
            401,
            'Unauthorized print agent.',
        );
    }
}
