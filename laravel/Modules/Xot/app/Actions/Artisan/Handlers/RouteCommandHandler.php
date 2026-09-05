<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan\Handlers;

use Modules\Xot\Actions\Artisan\Contracts\CommandHandlerInterface;
use Modules\Xot\Actions\Artisan\RunArtisanCommandAction;
use Modules\Xot\Actions\Artisan\ShowArtisanRouteListAction;
use Webmozart\Assert\Assert;

/**
 * Handles route-related artisan commands.
 */
class RouteCommandHandler implements CommandHandlerInterface
{
    /** @var array<string, string> */
    private const array ROUTE_COMMANDS = [
        'routelist' => 'listRoutes',
        'routelist1' => 'showRouteList',
        'routecache' => 'cacheRoutes',
        'routeclear' => 'clearRoutes',
    ];

    public function handle(string $moduleName = ''): string
    {
        $command = $this->getCurrentCommand();

        if (isset(self::ROUTE_COMMANDS[$command])) {
            $method = self::ROUTE_COMMANDS[$command];

            return $this->$method();
        }

        return '';
    }

    public function supports(string $command): bool
    {
        return isset(self::ROUTE_COMMANDS[$command]);
    }

    private function getCurrentCommand(): string
    {
        Assert::string($currentCommand = request()->input('act', ''));

        return $currentCommand;
    }

    private function listRoutes(): string
    {
        return app(RunArtisanCommandAction::class)->execute('route:list');
    }

    private function showRouteList(): string
    {
        return app(ShowArtisanRouteListAction::class)->execute();
    }

    private function cacheRoutes(): string
    {
        return app(RunArtisanCommandAction::class)->execute('route:cache');
    }

    private function clearRoutes(): string
    {
        return app(RunArtisanCommandAction::class)->execute('route:clear');
    }
}
