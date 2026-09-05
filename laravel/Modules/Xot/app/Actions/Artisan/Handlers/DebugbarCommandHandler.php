<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan\Handlers;

use Modules\Xot\Actions\Artisan\ClearArtisanDebugbarFilesAction;
use Modules\Xot\Actions\Artisan\Contracts\CommandHandlerInterface;

/**
 * Handles debugbar-related artisan commands.
 */
class DebugbarCommandHandler implements CommandHandlerInterface
{
    public function handle(string $moduleName = ''): string
    {
        return app(ClearArtisanDebugbarFilesAction::class)->execute();
    }

    public function supports(string $command): bool
    {
        return $command === 'debugbar:clear';
    }
}
