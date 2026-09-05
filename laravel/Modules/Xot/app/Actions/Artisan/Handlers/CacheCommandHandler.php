<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan\Handlers;

use Modules\Xot\Actions\Artisan\ClearArtisanDebugbarFilesAction;
use Modules\Xot\Actions\Artisan\ClearArtisanErrorLogAction;
use Modules\Xot\Actions\Artisan\ClearArtisanSessionFilesAction;
use Modules\Xot\Actions\Artisan\Contracts\CommandHandlerInterface;
use Modules\Xot\Actions\Artisan\RunArtisanCommandAction;
use Webmozart\Assert\Assert;

/**
 * Handles cache-related artisan commands.
 */
class CacheCommandHandler implements CommandHandlerInterface
{
    /** @var array<string, string> */
    private const array CACHE_COMMANDS = [
        'clear' => 'clearAll',
        'clearcache' => 'clearCache',
        'configcache' => 'cacheConfig',
    ];

    public function handle(string $moduleName = ''): string
    {
        $command = $this->getCurrentCommand();

        if (isset(self::CACHE_COMMANDS[$command])) {
            $method = self::CACHE_COMMANDS[$command];

            return $this->$method();
        }

        return '';
    }

    public function supports(string $command): bool
    {
        return isset(self::CACHE_COMMANDS[$command]);
    }

    private function getCurrentCommand(): string
    {
        $command = request()->input('act', '');
        Assert::string($command);

        return $command;
    }

    private function clearAll(): string
    {
        $output = '';
        $output .= app(RunArtisanCommandAction::class)->execute('cache:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('config:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('event:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('route:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('view:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('debugbar:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('opcache:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('optimize:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('key:generate').PHP_EOL;
        $output .= app(ClearArtisanSessionFilesAction::class)->execute().PHP_EOL;
        $output .= app(ClearArtisanErrorLogAction::class)->execute().PHP_EOL;
        $output .= app(ClearArtisanDebugbarFilesAction::class)->execute().PHP_EOL;
        $output .= PHP_EOL.'DONE'.PHP_EOL;

        return $output;
    }

    private function clearCache(): string
    {
        return app(RunArtisanCommandAction::class)->execute('cache:clear');
    }

    private function cacheConfig(): string
    {
        return app(RunArtisanCommandAction::class)->execute('config:cache');
    }
}
