<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
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
 */
class HandleArtisanActRequestAction
{
    use QueueableAction;

    /**
     * @throws FileNotFoundException
     */
    public function execute(string $act): string
    {
        $moduleName = Request::input('module', '');
        if (! is_string($moduleName)) {
            $moduleName = '';
        }

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
        };
    }

    private function migrate(string $moduleName): string
    {
        DB::purge('mysql');
        DB::reconnect('mysql');

        if ($moduleName !== '') {
            echo '<h3>Module '.$moduleName.'</h3>';

            // Dati sacri: mai --force (solo migrate additivo)
            return app(RunArtisanCommandAction::class)->execute('module:migrate', ['module' => $moduleName]);
        }

        return app(RunArtisanCommandAction::class)->execute('migrate');
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
}
