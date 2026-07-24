<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Speech;

use Illuminate\Http\UploadedFile;

interface SpeechTranscriptionProviderInterface
{
    /**
     * @throws \RuntimeException on configuration/runtime errors
     */
    public function transcribe(UploadedFile $audio, string $language): string;
}
