<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan\Contracts;

/**
 * Interface for Artisan command handlers.
 *
 * Kind B (no-services-rule): a Strategy selected by CommandRegistry, not a
 * standalone use case — implementations are plain classes under
 * Actions/Artisan/Handlers/, not QueueableAction.
 */
interface CommandHandlerInterface
{
    /**
     * Handle the artisan command.
     */
    public function handle(string $moduleName = ''): string;

    /**
     * Check if this handler supports the given command.
     */
    public function supports(string $command): bool;
}
