<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Spatie\QueueableAction\QueueableAction;

/**
 * Replaces Modules\Xot\Services\ArtisanService::exe().
 *
 * Runs a single artisan command via the Artisan facade and returns its
 * output wrapped in a `<pre>` block, or the exception message on failure.
 */
class RunArtisanCommandAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function execute(string $command, array $arguments = []): string
    {
        try {
            Artisan::call($command, $arguments);

            return '[<pre>'.Artisan::output().'</pre>]';
        } catch (Exception $exception) {
            return '[<pre>'.$exception->getMessage().'</pre>]';
        }
    }
}
