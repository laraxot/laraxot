<?php

declare(strict_types=1);

namespace Modules\Lang\Actions;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
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
        // Scrivere la chiave mancante aiuta chi sviluppa e rovina i test: la suite
        // passa su migliaia di etichette e riscriverebbe i file di lingua dell'albero
        // di lavoro, lasciando in `git status` modifiche che nessuno ha fatto a mano.
        //
        // Il default e' "acceso, tranne che sotto test": la config del modulo non e'
        // caricata nell'app dei test, quindi un default preso solo da li' sarebbe
        // rimasto acceso proprio dove serve spento.
        if (Config::get('lang.save_missing_translations', ! app()->runningUnitTests()) !== true) {
            return;
        }

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
            dddx([
                'key' => $key,
                'data' => $data,
                'filename' => $filename,
                'message' => $e->getMessage(),
            ]);
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
