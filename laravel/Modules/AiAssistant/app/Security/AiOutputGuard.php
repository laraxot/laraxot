<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Security;

use function Safe\preg_replace;

/**
 * Last check before a model's answer reaches a person.
 *
 * The input guard stops an attacker steering the model; this one assumes the
 * steering sometimes works anyway and catches what comes back out. Two things
 * are looked for: credentials the model should never repeat, and the framing
 * scaffolding of the system prompt, whose appearance in an answer means the
 * model has started reciting its own instructions rather than doing the job.
 *
 * Covers OWASP LLM02 (sensitive information disclosure) and LLM07 (system
 * prompt leakage).
 */
final class AiOutputGuard
{
    /**
     * Markers the framing puts around untrusted content. None of them belong in
     * an answer, so seeing one is a reliable sign of leakage rather than a
     * coincidence of wording.
     *
     * @var array<int, string>
     */
    private const LEAK_MARKERS = [
        '[security_notice]',
        '[trusted_context',
        '<user_request>',
        '[contesto',
    ];

    public function __construct(
        private readonly SensitiveDataRedactor $redactor,
    ) {}

    /**
     * @return array{text: string, redacted: bool, leaked_system_prompt: bool}
     */
    public function inspect(string $answer): array
    {
        $leaked = $this->leaksSystemPrompt($answer);
        $clean = $this->redactor->forUser($answer);

        if ($leaked) {
            $clean = $this->stripLeakMarkers($clean);
        }

        return [
            'text' => trim($clean),
            'redacted' => $clean !== $answer,
            'leaked_system_prompt' => $leaked,
        ];
    }

    public function leaksSystemPrompt(string $answer): bool
    {
        foreach (self::LEAK_MARKERS as $marker) {
            if (stripos($answer, $marker) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Strips the scaffolding but keeps whatever the model wrote around it. The
     * alternative — discarding the whole answer — turns a leak into an outage,
     * and an operator staring at an empty reply learns nothing.
     */
    private function stripLeakMarkers(string $answer): string
    {
        $cleaned = preg_replace(
            [
                '/\[security_notice\].*?\[\/security_notice\]/su',
                '/\[trusted_context[^\]]*\]/u',
                '/<\/?user_request>/u',
                '/\[contesto[^\]]*\]/iu',
                '/\[security_notice\]/u',
            ],
            '',
            $answer,
        );

        return is_string($cleaned) ? $cleaned : $answer;
    }
}
