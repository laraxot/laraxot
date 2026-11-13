<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan\Handlers;

<<<<<<< HEAD
=======
use Modules\Xot\Actions\Artisan\Contracts\CommandHandlerInterface;
>>>>>>> 77b1e2748 (first)
use Modules\Xot\Actions\Artisan\RunArtisanCommandAction;
use Webmozart\Assert\Assert;

/**
 * Handles module-related artisan commands.
<<<<<<< HEAD
 *
 * @deprecated Residuo: l'interfaccia dei handler e il registry sono stati rimossi, i comandi `module-*`
 *             li serve `HandleArtisanActRequestAction` tramite `ArtisanActEnum`. Nessun chiamante.
 */
class ModuleCommandHandler
{
    /** @var list<string> */
    private const array MODULE_COMMANDS = ['module-list', 'module-disable', 'module-enable'];

    public function handle(string $moduleName = ''): string
    {
        return match ($this->getCurrentCommand()) {
            'module-list' => $this->listModules(),
            'module-disable' => $this->disableModule($moduleName),
            'module-enable' => $this->enableModule($moduleName),
            default => '',
        };
=======
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
>>>>>>> 77b1e2748 (first)
    }

    public function supports(string $command): bool
    {
<<<<<<< HEAD
        return in_array($command, self::MODULE_COMMANDS, true);
=======
        return isset(self::MODULE_COMMANDS[$command]);
>>>>>>> 77b1e2748 (first)
    }

    private function getCurrentCommand(): string
    {
        $command = request()->input('act', '');
        Assert::string($command);

        return $command;
    }

<<<<<<< HEAD
    private function listModules(): string
=======
    private function listModules(string $moduleName): string
>>>>>>> 77b1e2748 (first)
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
