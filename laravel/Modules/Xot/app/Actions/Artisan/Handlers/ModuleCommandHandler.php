<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan\Handlers;

use Modules\Xot\Actions\Artisan\Contracts\CommandHandlerInterface;
use Modules\Xot\Actions\Artisan\RunArtisanCommandAction;
use Webmozart\Assert\Assert;

/**
 * Handles module-related artisan commands.
 */
class ModuleCommandHandler implements CommandHandlerInterface
{
    /** @var array<string, string> */
    private const array MODULE_COMMANDS = [
        'module-list' => 'listModules',
        'module-disable' => 'disableModule',
        'module-enable' => 'enableModule',
    ];

    public function handle(string $moduleName = ''): string
    {
        $command = $this->getCurrentCommand();

        if (isset(self::MODULE_COMMANDS[$command])) {
            $method = self::MODULE_COMMANDS[$command];

            return $this->$method($moduleName);
        }

        return '';
    }

    public function supports(string $command): bool
    {
        return isset(self::MODULE_COMMANDS[$command]);
    }

    private function getCurrentCommand(): string
    {
        $command = request()->input('act', '');
        Assert::string($command);

        return $command;
    }

    private function listModules(string $moduleName): string
    {
        return app(RunArtisanCommandAction::class)->execute('module:list');
    }

    private function disableModule(string $moduleName): string
    {
        return app(RunArtisanCommandAction::class)->execute('module:disable '.$moduleName);
    }

    private function enableModule(string $moduleName): string
    {
        return app(RunArtisanCommandAction::class)->execute('module:enable '.$moduleName);
    }
}
