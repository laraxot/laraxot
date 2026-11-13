<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Arr;

<<<<<<< HEAD
use Illuminate\Support\Facades\File;
=======
>>>>>>> 77b1e2748 (first)
use Spatie\QueueableAction\QueueableAction;

use function Safe\file_put_contents;
use function Safe\json_encode;

class SaveJsonArrayAction
{
    use QueueableAction;

    /**
     * @param  array<int|string, mixed>  $data
     */
    public function execute(array $data, string $filename): bool
    {
        $content = json_encode($data, JSON_PRETTY_PRINT);

<<<<<<< HEAD
        // La cartella dei JSON (es. config/local/{tenant}/database/content) non esiste in un'installazione
        // nuova: senza crearla la scrittura falliva e i badge di navigazione mostravano "--".
        File::ensureDirectoryExists(\dirname($filename));

=======
        // if ($content === false) {
        //    return false;
        // }
>>>>>>> 77b1e2748 (first)
        return (bool) file_put_contents($filename, $content);
    }
}
