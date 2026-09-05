<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan;

use Modules\Xot\Actions\Artisan\Contracts\CommandHandlerInterface;
use Modules\Xot\Actions\Artisan\Handlers\CacheCommandHandler;
use Modules\Xot\Actions\Artisan\Handlers\DebugbarCommandHandler;
use Modules\Xot\Actions\Artisan\Handlers\ErrorCommandHandler;
use Modules\Xot\Actions\Artisan\Handlers\MigrationCommandHandler;
use Modules\Xot\Actions\Artisan\Handlers\ModuleCommandHandler;
use Modules\Xot\Actions\Artisan\Handlers\OptimizeCommandHandler;
use Modules\Xot\Actions\Artisan\Handlers\QueueCommandHandler;
use Modules\Xot\Actions\Artisan\Handlers\RouteCommandHandler;
use Modules\Xot\Actions\Artisan\Handlers\ViewCommandHandler;

/**
 * Registry for artisan command handlers.
 *
 * Kind B (no-services-rule): a plain registry/factory selecting a Strategy,
 * not a use case with a single execute() — stays a plain class, relocated
 * here rather than force-fit into QueueableAction.
 */
class CommandRegistry
{
    /**
     * @var array<CommandHandlerInterface>
     */
    private array $handlers = [];

    public function __construct()
    {
        $this->registerDefaultHandlers();
    }

    /**
     * Register a command handler.
     */
    public function register(CommandHandlerInterface $handler): self
    {
        $this->handlers[] = $handler;

        return $this;
    }

    /**
     * Find a handler for the given command.
     */
    public function findHandler(string $command): ?CommandHandlerInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($command)) {
                return $handler;
            }
        }

        return null;
    }

    /**
     * Register all default command handlers.
     */
    private function registerDefaultHandlers(): void
    {
        $this->register(new MigrationCommandHandler())
            ->register(new CacheCommandHandler())
            ->register(new RouteCommandHandler())
            ->register(new ViewCommandHandler())
            ->register(new ErrorCommandHandler())
            ->register(new ModuleCommandHandler())
            ->register(new OptimizeCommandHandler())
            ->register(new QueueCommandHandler())
            ->register(new DebugbarCommandHandler());
    }
}
