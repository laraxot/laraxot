<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan\Handlers;

use Modules\Xot\Actions\Artisan\ClearArtisanErrorLogAction;
use Modules\Xot\Actions\Artisan\Contracts\CommandHandlerInterface;
use Modules\Xot\Actions\Artisan\ShowArtisanErrorLogAction;
use Webmozart\Assert\Assert;

/**
 * Handles error-related artisan commands.
 */
class ErrorCommandHandler implements CommandHandlerInterface
{
    /** @var list<string> */
    private const array ERROR_COMMANDS = ['error', 'error-show', 'error-clear'];

    public function handle(string $moduleName = ''): string
    {
        $command = $this->getCurrentCommand();

        if ($command === 'error-clear') {
            return app(ClearArtisanErrorLogAction::class)->execute();
        }

        return app(ShowArtisanErrorLogAction::class)->execute()->render();
    }

    public function supports(string $command): bool
    {
        return in_array($command, self::ERROR_COMMANDS, true);
    }

    private function getCurrentCommand(): string
    {
        $command = request()->input('act', '');
        Assert::string($command);

        return $command;
    }
}
