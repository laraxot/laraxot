<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Security;

use function Safe\preg_match;
use function Safe\preg_replace;

/**
 * Removes what must never leave the system, in two different strengths.
 *
 * Secrets are stripped everywhere: an API key or a bearer token in a model's
 * answer is a breach whoever is reading it. Personal data is stripped only on
 * the way into the logs, because redacting a customer's phone number out of an
 * assistant's reply would break the very thing the assistant is for. That split
 * is the whole design: minimise what is *retained*, not what is *shown* to the
 * person already entitled to see it.
 *
 * Covers OWASP LLM02 (sensitive information disclosure) on the output path and
 * GDPR data minimisation on the logging path.
 */
final class SensitiveDataRedactor
{
    public const MASK = '[redatto]';

    /**
     * Credentials and keys. Ordered longest-context first so a labelled secret
     * is caught by its label before a looser pattern grabs part of it.
     *
     * @var array<int, string>
     */
    private const SECRET_PATTERNS = [
        // key=value pairs for anything that names itself a credential
        '/\b(api[_-]?key|apikey|secret|token|password|passwd|pwd|authorization)\b\s*[:=]\s*["\']?[^\s"\',;]{6,}/iu',
        // Bearer / Basic auth headers
        '/\bBearer\s+[A-Za-z0-9\-._~+\/]{12,}=*/u',
        '/\bBasic\s+[A-Za-z0-9+\/]{12,}=*/u',
        // Provider-shaped keys
        '/\bsk-[A-Za-z0-9\-_]{16,}/u',
        '/\bAKIA[0-9A-Z]{16}\b/u',
        '/\bghp_[A-Za-z0-9]{20,}/u',
        // Private key blocks
        '/-----BEGIN [A-Z ]*PRIVATE KEY-----/u',
    ];

    /**
     * Personal and payment data, stripped from anything we keep.
     *
     * @var array<int, string>
     */
    private const PERSONAL_PATTERNS = [
        // IBAN, Italian and generic
        '/\b[A-Z]{2}\d{2}[A-Z0-9]{11,30}\b/u',
        // Payment card numbers, grouped or not
        '/\b(?:\d[ -]?){13,19}\b/u',
        // Italian tax code
        '/\b[A-Z]{6}\d{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]\b/iu',
        // Email addresses
        '/\b[\w.+-]+@[\w-]+\.[\w.-]{2,}\b/u',
    ];

    /**
     * For anything shown to an authenticated user: secrets only.
     */
    public function forUser(string $text): string
    {
        return $this->apply($text, self::SECRET_PATTERNS);
    }

    /**
     * For anything written to a log or stored for later: secrets and personal
     * data both.
     */
    public function forLogs(string $text): string
    {
        return $this->apply($text, array_merge(self::SECRET_PATTERNS, self::PERSONAL_PATTERNS));
    }

    /**
     * Field names whose value is a secret regardless of how it looks. In a tool
     * payload the label and the secret sit in separate array entries, so the
     * value on its own carries nothing to match against — `['token' => 'abc123']`
     * is indistinguishable from any other short string until you read the key.
     *
     * @var array<int, string>
     */
    private const SECRET_KEY_NAMES = [
        'api_key', 'apikey', 'api-key', 'secret', 'client_secret', 'token',
        'access_token', 'refresh_token', 'password', 'passwd', 'pwd',
        'authorization', 'auth', 'credentials', 'private_key',
    ];

    /**
     * Log payloads arrive as nested arrays of tool arguments and responses.
     *
     * @param  array<array-key, mixed>  $payload
     * @return array<array-key, mixed>
     */
    public function redactPayloadForLogs(array $payload): array
    {
        $redacted = [];

        foreach ($payload as $key => $value) {
            if (is_array($value)) {
                $redacted[$key] = $this->redactPayloadForLogs($value);

                continue;
            }

            if ($this->isSecretKey($key)) {
                $redacted[$key] = self::MASK;

                continue;
            }

            $redacted[$key] = is_string($value) ? $this->forLogs($value) : $value;
        }

        return $redacted;
    }

    private function isSecretKey(int|string $key): bool
    {
        if (! is_string($key)) {
            return false;
        }

        return in_array(strtolower(trim($key)), self::SECRET_KEY_NAMES, true);
    }

    public function containsSecret(string $text): bool
    {
        foreach (self::SECRET_PATTERNS as $pattern) {
            if (preg_match($pattern, $text) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, string>  $patterns
     */
    private function apply(string $text, array $patterns): string
    {
        foreach ($patterns as $pattern) {
            $replaced = preg_replace($pattern, self::MASK, $text);

            if (is_string($replaced)) {
                $text = $replaced;
            }
        }

        return $text;
    }
}
