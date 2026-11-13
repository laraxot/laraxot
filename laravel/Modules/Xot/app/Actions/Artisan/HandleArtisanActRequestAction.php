<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
<<<<<<< HEAD
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Modules\Xot\Enums\ArtisanActEnum;
use Spatie\QueueableAction\QueueableAction;

/**
 * Entrypoint unico del parametro legacy `act` (ex `ArtisanService::act()`).
 *
 * Gli act ammessi sono {@see ArtisanActEnum}: un act sconosciuto restituisce stringa vuota.
 * Ogni ramo compone una Action dedicata invece di duplicarne la logica.
=======
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Spatie\QueueableAction\QueueableAction;

/**
 * Replaces Modules\Xot\Services\ArtisanService::act().
 *
 * Dispatches a legacy "act" request parameter to the dedicated Artisan
 * Actions. Kept as a single entrypoint (rather than deleted outright)
 * because it is the historical shape callers of the `?act=` query
 * parameter expect; each branch composes a real Action via app()->execute()
 * instead of duplicating logic.
>>>>>>> 77b1e2748 (first)
 */
class HandleArtisanActRequestAction
{
    use QueueableAction;

    /**
     * @throws FileNotFoundException
     */
    public function execute(string $act): string
    {
<<<<<<< HEAD
        $case = ArtisanActEnum::tryFrom($act);
        if ($case === null) {
            return '';
        }

=======
>>>>>>> 77b1e2748 (first)
        $moduleName = Request::input('module', '');
        if (! is_string($moduleName)) {
            $moduleName = '';
        }

<<<<<<< HEAD
        return match ($case) {
            ArtisanActEnum::Migrate => $this->migrate($moduleName),
            ArtisanActEnum::RouteList => $this->run('route:list'),
            ArtisanActEnum::RouteListView => app(ShowArtisanRouteListAction::class)->execute(),
            ArtisanActEnum::RouteCache => $this->run('route:cache'),
            ArtisanActEnum::RouteClear => $this->run('route:clear'),
            ArtisanActEnum::QueueFlush => $this->run('queue:flush'),
            ArtisanActEnum::Optimize => $this->run('optimize'),
            ArtisanActEnum::Clear => $this->clearAll(),
            ArtisanActEnum::ClearCache => $this->run('cache:clear'),
            ArtisanActEnum::ConfigCache => $this->run('config:cache'),
            ArtisanActEnum::ViewClear => $this->run('view:clear'),
            ArtisanActEnum::DebugbarClear => app(ClearArtisanDebugbarFilesAction::class)->execute(),
            ArtisanActEnum::ModuleList => $this->run('module:list'),
            ArtisanActEnum::ModuleDisable => $this->run('module:disable '.$moduleName),
            ArtisanActEnum::ModuleEnable => $this->run('module:enable '.$moduleName),
            ArtisanActEnum::Error, ArtisanActEnum::ErrorShow => app(ShowArtisanErrorLogAction::class)->execute()->render(),
            ArtisanActEnum::ErrorClear => app(ClearArtisanErrorLogAction::class)->execute(),
=======
        return match ($act) {
            'migrate' => $this->migrate($moduleName),
            'routelist' => app(RunArtisanCommandAction::class)->execute('route:list'),
            'queue:flush' => app(RunArtisanCommandAction::class)->execute('queue:flush'),
            'routelist1' => app(ShowArtisanRouteListAction::class)->execute(),
            'optimize' => app(RunArtisanCommandAction::class)->execute('optimize'),
            'clear' => $this->clearAll(),
            'clearcache' => app(RunArtisanCommandAction::class)->execute('cache:clear'),
            'routecache' => app(RunArtisanCommandAction::class)->execute('route:cache'),
            'routeclear' => app(RunArtisanCommandAction::class)->execute('route:clear'),
            'viewclear' => app(RunArtisanCommandAction::class)->execute('view:clear'),
            'configcache' => app(RunArtisanCommandAction::class)->execute('config:cache'),
            'debugbar:clear' => app(ClearArtisanDebugbarFilesAction::class)->execute(),
            'module-list' => app(RunArtisanCommandAction::class)->execute('module:list'),
            'module-disable' => app(RunArtisanCommandAction::class)->execute('module:disable '.$moduleName),
            'module-enable' => app(RunArtisanCommandAction::class)->execute('module:enable '.$moduleName),
            'error', 'error-show' => app(ShowArtisanErrorLogAction::class)->execute()->render(),
            'error-clear' => app(ClearArtisanErrorLogAction::class)->execute(),
            default => '',
>>>>>>> 77b1e2748 (first)
        };
    }

    private function migrate(string $moduleName): string
    {
<<<<<<< HEAD
        // Il migrate gira sulla connessione di default: e' quella da rinfrescare (non 'mysql' fisso).
        $defaultConnection = Config::get('database.default');
        $connection = is_string($defaultConnection) && $defaultConnection !== '' ? $defaultConnection : 'mysql';
        DB::purge($connection);
        DB::reconnect($connection);
=======
        DB::purge('mysql');
        DB::reconnect('mysql');
>>>>>>> 77b1e2748 (first)

        if ($moduleName !== '') {
            echo '<h3>Module '.$moduleName.'</h3>';

            // Dati sacri: mai --force (solo migrate additivo)
<<<<<<< HEAD
            return $this->run('module:migrate', ['module' => $moduleName]);
        }

        return $this->run('migrate');
=======
            return app(RunArtisanCommandAction::class)->execute('module:migrate', ['module' => $moduleName]);
        }

        return app(RunArtisanCommandAction::class)->execute('migrate');
>>>>>>> 77b1e2748 (first)
    }

    private function clearAll(): string
    {
        $output = '';
<<<<<<< HEAD
        foreach (['cache:clear', 'config:clear', 'event:clear', 'route:clear', 'view:clear', 'debugbar:clear', 'opcache:clear', 'optimize:clear', 'key:generate'] as $command) {
            $output .= $this->run($command).PHP_EOL;
        }
=======
        $output .= app(RunArtisanCommandAction::class)->execute('cache:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('config:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('event:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('route:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('view:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('debugbar:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('opcache:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('optimize:clear').PHP_EOL;
        $output .= app(RunArtisanCommandAction::class)->execute('key:generate').PHP_EOL;
>>>>>>> 77b1e2748 (first)
        $output .= app(ClearArtisanSessionFilesAction::class)->execute().PHP_EOL;
        $output .= app(ClearArtisanErrorLogAction::class)->execute().PHP_EOL;
        $output .= app(ClearArtisanDebugbarFilesAction::class)->execute().PHP_EOL;
        $output .= PHP_EOL.'DONE'.PHP_EOL;

        return $output;
    }
<<<<<<< HEAD

    /**
     * @param  array<string, string>  $arguments
     */
    private function run(string $command, array $arguments = []): string
    {
        return app(RunArtisanCommandAction::class)->execute($command, $arguments);
    }
=======
>>>>>>> 77b1e2748 (first)
}
