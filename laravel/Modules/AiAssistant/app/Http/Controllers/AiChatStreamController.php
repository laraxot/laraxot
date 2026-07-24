<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Modules\AiAssistant\Chat\AiAssistantChatHistory;
use Modules\AiAssistant\Models\AiThread;
use Modules\AiAssistant\Neuron\OperatorAssistantAgent;
use Modules\AiAssistant\Security\PromptInjectionGuard;
use Modules\AiAssistant\Speech\BuildSpokenSummaryAction;
use NeuronAI\Chat\Messages\Stream\Chunks\TextChunk;
use NeuronAI\Chat\Messages\UserMessage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

use function Safe\json_encode;
use function Safe\ob_flush;

final class AiChatStreamController extends Controller
{
    public function stream(Request $request): StreamedResponse
    {
        Gate::authorize('use-aiassistant');

        $user = $request->user();

        $message = (string) $request->query('message', '');
        $threadPublicId = (string) $request->query('thread', '');
        $panelId = (string) $request->query('panel', 'operator');
        $contextInterventionId = $request->integer('context_intervention_id');
        $contextWarehouseId = $request->integer('context_warehouse_id');
        if (! in_array($panelId, ['operator', 'admin'], true)) {
            $panelId = 'operator';
        }

        return new StreamedResponse(function () use ($user, $message, $threadPublicId, $panelId, $contextInterventionId, $contextWarehouseId) {
            $flush = function (array $payload, string $event = 'message'): void {
                echo "event: {$event}\n";
                echo 'data: '.json_encode($payload, JSON_UNESCAPED_UNICODE)."\n\n";
                @ob_flush();
                @flush();
            };

            if (! $user) {
                $flush(['error' => 'Non autenticato.'], 'error');
                $flush([], 'done');

                return;
            }

            $thread = null;
            if ($threadPublicId !== '') {
                $thread = AiThread::query()
                    ->where('public_id', $threadPublicId)
                    ->where('created_by_user_id', $user->id)
                    ->first();
            }

            if (! ($thread instanceof AiThread)) {
                $thread = AiThread::query()->create([
                    'public_id' => (string) Str::uuid(),
                    'created_by_user_id' => $user->id,
                    'panel_id' => $panelId,
                    'last_message_at' => now(),
                ]);
            }

            $flush(['thread' => (string) $thread->public_id], 'thread');
            $flush(['status' => 'started'], 'status');

            $guard = app(PromptInjectionGuard::class);
            $cleanMessage = $guard->stripUntrustedContextMarkers(trim($message));
            if ($cleanMessage === '') {
                $flush([], 'done');

                return;
            }

            $inspection = $guard->inspect($cleanMessage);
            if ($inspection['blocked'] === true) {
                $flush(['token' => $guard->blockedResponse()], 'token');
                $flush([], 'done');

                return;
            }

            $groqKey = config('services.groq.key', '');
            $openAiKey = config('services.openai.key', '');
            $hasGroq = is_string($groqKey) && $groqKey !== '';
            $hasOpenAi = is_string($openAiKey) && $openAiKey !== '';

            if (! $hasGroq && ! $hasOpenAi) {
                $flush(['error' => 'Configura GROQ_API_KEY (consigliato) o OPENAI_API_KEY.'], 'error');
                $flush([], 'done');

                return;
            }

            try {
                $history = new AiAssistantChatHistory($thread, (int) $user->id);

                $agent = new OperatorAssistantAgent();
                $agent->setChatHistory($history);
                $agent->forSession($thread, (int) $user->id);
                $agent->toolMaxRuns(10);

                $handler = $agent->stream(new UserMessage(
                    $guard->frameIncomingUserMessage(
                        message: $cleanMessage,
                        panelId: $panelId,
                        contextInterventionId: $contextInterventionId > 0 ? $contextInterventionId : null,
                        contextWarehouseId: $contextWarehouseId > 0 ? $contextWarehouseId : null,
                    )
                ));

                $fullText = '';
                foreach ($handler->events() as $event) {
                    if ($event instanceof TextChunk) {
                        $fullText .= $event->content;
                        $flush(['token' => $event->content], 'token');
                    }
                }

                // Emit a concise, plain spoken summary for optional text-to-speech.
                $spoken = app(BuildSpokenSummaryAction::class)->execute($fullText);
                if ($spoken !== '') {
                    $flush(['text' => $spoken], 'spoken');
                }

                $flush([], 'done');
            } catch (Throwable $e) {
                $flush(['error' => 'Errore AI: '.$e->getMessage()], 'error');
                $flush([], 'done');
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
