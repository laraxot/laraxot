<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Exceptions;

use RuntimeException;

/**
 * An agent tried to propose something the policy refuses: a type nobody has
 * permitted, or one proposal too many inside the window.
 */
final class AiActionNotAllowedException extends RuntimeException
{
    public function __construct(
        public readonly string $actionType,
        public readonly string $reason,
    ) {
        parent::__construct("Azione '{$actionType}' rifiutata dalla policy: {$reason}");
    }
}
