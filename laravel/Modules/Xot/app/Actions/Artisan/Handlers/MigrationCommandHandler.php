<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan\Handlers;

use Illuminate\Support\Facades\DB;
use Modules\Xot\Actions\Artisan\Contracts\CommandHandlerInterface;
use Modules\Xot\Actions\Artisan\RunArtisanCommandAction;

/**
 * Handles migration-related artisan commands.
 */
class MigrationCommandHandler implements CommandHandlerInterface
{
    public function handle(string $moduleName = ''): string
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

    public function supports(string $command): bool
    {
        return $command === 'migrate';
    }
}
