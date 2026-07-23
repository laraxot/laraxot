<?php

declare(strict_types=1);

namespace Modules\Tenant\Actions\Config;

use InvalidArgumentException;
use Modules\Tenant\Actions\GetTenantNameAction;
use Spatie\QueueableAction\QueueableAction;

use function Safe\realpath;

class GetTenantFilePathAction
{
    use QueueableAction;

    public function execute(string $filename): string
    {
        $normalizedFilename = str_replace('\\', '/', $filename);
        if (str_starts_with($normalizedFilename, '/') || str_contains($filename, "\0") || in_array('..', explode('/', $normalizedFilename), true)) {
            throw new InvalidArgumentException('Tenant filename must be a relative path without traversal segments.');
        }

        if (isRunningTestBench()) {
            $basePath = realpath(__DIR__.'/../../Config');

            return $basePath.\DIRECTORY_SEPARATOR.$filename;
        }

        $tenantName = app(GetTenantNameAction::class)->execute();
        $path = base_path('config/'.$tenantName.'/'.$filename);

        return str_replace(['/', '\\'], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR], $path);
    }
}
