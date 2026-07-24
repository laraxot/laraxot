<?php

declare(strict_types=1);

namespace Modules\AI\Support;

use Modules\AI\Contracts\AiActionHandlerContract;

/**
 * Singleton registry mapping `AiActionProposal::type` values to their
 * corresponding {@see AiActionHandlerContract} implementation.
 *
 * Registered as a singleton in `AIServiceProvider`. Domain modules
 * (e.g. WorkOrder, Intervention) should register their own handlers
 * against this registry rather than the AI module knowing about them.
 */
class AiActionHandlerRegistry
{
    /**
     * @var array<string, AiActionHandlerContract>
     */
    protected array $handlers = [];

    public function register(AiActionHandlerContract $handler): static
    {
        $this->handlers[$handler->type()] = $handler;

        return $this;
    }

    public function has(string $type): bool
    {
        return isset($this->handlers[$type]);
    }

    public function get(string $type): ?AiActionHandlerContract
    {
        return $this->handlers[$type] ?? null;
    }

    /**
     * @return array<string, AiActionHandlerContract>
     */
    public function all(): array
    {
        return $this->handlers;
    }
}
