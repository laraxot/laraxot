<?php

declare(strict_types=1);

namespace Modules\Lang\Actions;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Modules\Xot\Actions\Arr\SaveArrayAction;
use Spatie\QueueableAction\QueueableAction;

class SaveTransAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>|int|string|Htmlable|null  $data
     */
    public function execute(string $key, int|string|array|Htmlable|null $data): void
    {
        $cont = [];

        $filename = app(GetTransPathAction::class)->execute($key);

        if (! File::exists($filename)) {
            app(SaveArrayAction::class)->execute(
                data: $cont,
                filename: $filename,
            );
        }

        try {
            $cont = File::getRequire($filename);
        } catch (\Exception $e) {
            // Il file di traduzione esiste ma non e' leggibile o non restituisce
            // un array. Qui c'era un dddx(), cioe' un dd(): un file corrotto
            // uccideva la richiesta invece di far ripartire il contenuto da zero.
            Log::warning('File di traduzione non leggibile, riparto da vuoto', [
                'filename' => $filename,
                'key' => $key,
                'error' => $e->getMessage(),
            ]);

            $cont = [];
        }

        if (! is_array($cont)) {
            $cont = [];
        }

        $piece = implode('.', array_slice(explode('.', $key), 1));
        if ($piece !== '') {
            Arr::set($cont, $piece, $data);
        } else {
            $cont = $data;
        }

        if (! is_array($cont)) {
            throw new \Exception('Error in SaveTransAction');
        }

        /** @var array<string, mixed> $saveData */
        $saveData = $cont;

        app(SaveArrayAction::class)->execute(
            data: $saveData,
            filename: $filename,
        );
    }
}
