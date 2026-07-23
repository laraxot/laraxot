<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Modules\AiAssistant\Speech\SpeechTranscriptionProviderInterface;
use function Safe\preg_match;

final class AiSttController extends Controller
{
    public function __construct(
        private readonly SpeechTranscriptionProviderInterface $provider,
    ) {
    }

    public function transcribe(Request $request): JsonResponse
    {
        Gate::authorize('use-aiassistant');

        $request->validate([
            'audio' => [
                'required',
                'file',
                'max:12288', // 12MB
                'mimetypes:audio/webm,audio/ogg,audio/wav,audio/x-wav,audio/mpeg,audio/mp4,video/webm,application/ogg',
            ],
            'lang' => ['nullable', 'string', 'max:16'],
        ]);

        /** @var UploadedFile $file */
        $file = $request->file('audio');

        // Converte BCP-47 (es. it-IT) in codice lingua iso (es. it) per Whisper
        $langValue = $request->input('lang');
        $langHeader = is_string($langValue) ? $langValue : 'it-IT';
        $language = 'it';
        $langHeader = strtolower(trim($langHeader));
        if (preg_match('/^[a-z]{2}/', $langHeader, $m)) {
            if (isset($m[0]) && is_string($m[0])) {
                $language = $m[0];
            }
        }

        try {
            Log::info('AiSttController: received audio', [
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'language' => $language,
                'provider' => is_string(config('speech.transcription.provider', 'groq_whisper'))
                    ? (string) config('speech.transcription.provider', 'groq_whisper')
                    : 'groq_whisper',
            ]);

            $text = $this->provider->transcribe($file, $language);
        } catch (\RuntimeException $e) {
            if (str_starts_with($e->getMessage(), 'TRANSCRIPTION_REJECTED:')) {
                return response()->json([
                    'ok' => false,
                    'error' => 'transcription_rejected',
                    'message' => $e->getMessage(),
                ], 422);
            }

            return response()->json([
                'ok' => false,
                'error' => 'Errore trascrizione audio',
                'message' => $e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'error' => 'Errore trascrizione audio',
                'message' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'ok' => true,
            'text' => $text,
            'language' => $language,
        ]);
    }
}

