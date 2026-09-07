<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Security;

/**
 * What an agent is allowed to even propose, and how often.
 *
 * Human confirmation already stops a hallucinating agent from changing
 * anything. What it does not stop is an agent looping: hundreds of proposals in
 * a minute, each individually harmless, together a denial of service against
 * the person who has to read them. So two limits sit in front of it — an
 * allow-list of action types, and a ceiling per user per window.
 *
 * The allow-list is deliberately a list of what is permitted rather than what
 * is forbidden: a new action type added later is refused until someone decides
 * it should be allowed, instead of slipping through because nobody thought to
 * ban it.
 *
 * Covers OWASP LLM06 (excessive agency) and LLM10 (unbounded consumption).
 */
final class AiActionPolicy
{
    public const string DENIED_UNKNOWN_TYPE = 'unknown_action_type';

    public const string DENIED_RATE_LIMIT = 'rate_limit_exceeded';

    /**
     * @param  list<string>  $allowedTypes
     */
    public function __construct(
        private readonly array $allowedTypes,
        private readonly int $maxProposalsPerWindow = 30,
    ) {}

    /**
     * @return array{allowed: bool, reason: ?string}
     */
    public function check(string $actionType, int $proposalsInWindow): array
    {
        if (! $this->isAllowedType($actionType)) {
            return ['allowed' => false, 'reason' => self::DENIED_UNKNOWN_TYPE];
        }

        if ($proposalsInWindow >= $this->maxProposalsPerWindow) {
            return ['allowed' => false, 'reason' => self::DENIED_RATE_LIMIT];
        }

        return ['allowed' => true, 'reason' => null];
    }

    public function isAllowedType(string $actionType): bool
    {
        return in_array(trim($actionType), $this->allowedTypes, true);
    }

    /**
     * @return list<string>
     */
    public function allowedTypes(): array
    {
        return $this->allowedTypes;
    }

    public function maxProposalsPerWindow(): int
    {
        return $this->maxProposalsPerWindow;
    }
}
