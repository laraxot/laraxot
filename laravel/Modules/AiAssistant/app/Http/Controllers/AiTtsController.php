<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

/**
 * Text-to-speech for the assistant's spoken replies, using OpenAI's natural
 * TTS voices (much better than the browser's robotic speechSynthesis).
 * Returns MP3 audio; the frontend plays it. Falls back client-side to the
 * browser voice when this endpoint is unavailable.
 */
final class AiTtsController extends Controller
{
    public function speak(Request $request): Response
    {
        Gate::authorize('use-aiassistant');

        $validated = $request->validate([
            'text' => ['required', 'string', 'max:1200'],
        ]);

        $apiKeyValue = config('services.openai.key');
        $apiKey = is_string($apiKeyValue) ? trim($apiKeyValue) : '';
        if ($apiKey === '') {
            return response('TTS non configurato', 503);
        }

        $modelValue = config('services.openai.tts_model', 'gpt-4o-mini-tts');
        $model = is_string($modelValue) ? $modelValue : 'gpt-4o-mini-tts';

        $voiceValue = config('services.openai.tts_voice', 'alloy');
        $voice = is_string($voiceValue) ? $voiceValue : 'alloy';

        $timeout = config('services.openai.tts_timeout', 30);

        $response = Http::withToken($apiKey)
            ->timeout(is_numeric($timeout) ? (int) $timeout : 30)
            ->post('https://api.openai.com/v1/audio/speech', [
                'model' => $model,
                'voice' => $voice,
                'input' => $validated['text'],
                'response_format' => 'mp3',
            ]);

        if (! $response->successful()) {
            return response('Errore TTS', 502);
        }

        return response($response->body(), 200, [
            'Content-Type' => 'audio/mpeg',
            'Cache-Control' => 'no-store',
        ]);
    }
}
