<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan\Handlers;

use Modules\Xot\Actions\Artisan\Contracts\CommandHandlerInterface;
use Modules\Xot\Actions\Artisan\RunArtisanCommandAction;

/**
 * Handles optimization-related artisan commands.
 */
class OptimizeCommandHandler implements CommandHandlerInterface
{
    public function handle(string $moduleName = ''): string
    {
        return app(RunArtisanCommandAction::class)->execute('optimize');
    }

    public function supports(string $command): bool
    {
        return $command === 'optimize';
    }
}
