<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Artisan;

<<<<<<< HEAD
=======
use Illuminate\Support\Str;
>>>>>>> laraxot/dev
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

use function Safe\preg_match_all;

/**
 * Replaces Modules\Xot\Services\ArtisanService::errorShow().
 */
class ShowArtisanErrorLogAction
{
    use QueueableAction;

    public function execute(): Renderable
    {
<<<<<<< HEAD
        /** @var view-string $view */
=======
>>>>>>> laraxot/dev
        $view = 'xot::acts.artisan.error-show';
        $files = File::files(storage_path('logs'));
        $log = request('log', '');
        if (! is_string($log)) {
            $log = '';
        }
        $content = '';
<<<<<<< HEAD
        if ($log !== '' && File::exists(storage_path('logs/'.$log))) {
            $content = File::get(storage_path('logs/'.$log));
        }

        $pattern = '/url":"([^"]*)"/';
        $matches = [];
        preg_match_all($pattern, $content, $matches);

        $urls = array_values(array_unique($matches[1]));
=======
        // basename(): `log` arriva dalla query string, niente path traversal fuori da storage/logs.
        $logPath = storage_path('logs/'.basename($log));
        if ($log !== '' && File::isFile($logPath)) {
            $content = File::get($logPath);
        }

        $pattern = '/url":"([^"]*)"/';
        // Str::matchAll restituisce il primo gruppo di ogni occorrenza (lista vuota se nessun match).
        $urls = Str::matchAll($pattern, $content)->unique()->values()->all();
>>>>>>> laraxot/dev
        $view_params = [
            'view' => $view,
            'lang' => app()->getLocale(),
            'files' => $files,
            'content' => $content,
            'urls' => $urls,
        ];

<<<<<<< HEAD
        $result = view($view, $view_params);
=======
        $result = view('xot::acts.artisan.error-show', $view_params);
>>>>>>> laraxot/dev
        Assert::isInstanceOf($result, View::class);

        return $result;
    }
}
